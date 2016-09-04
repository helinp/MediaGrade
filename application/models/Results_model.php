<?php
Class Results_model extends CI_Model
{

    public $max_vote;
    public $user_vote = 'NE';
    public $skills_group;
    public $criterion;
    public $cursor;

    function __construct()
    {
        $this->load->helper('school');
        $this->current_school_year = get_school_year();
    }

    public function getGaussDataByClassAndSchoolYearAndAdmin($class = FALSE, $school_year = FALSE, $admin_id = FALSE)
    {
        $this->db->select('ROUND(SUM(user_vote) / SUM(max_vote) * 10, 0) AS percentage');
        $this->db->join('projects', 'projects.id = results.project_id', 'left');
        $this->db->where('projects.is_activated', TRUE);

        if($class) $this->db->where('class', $class);
        if($school_year) $this->db->where('school_year', $school_year);
        if($admin_id) $this->db->where('admin_id', $admin_id);

        $this->db->group_by('user_id');
        $this->db->order_by('percentage', 'ASC');
        $result = $this->db->get('results')->result();

        $this->load->helper('graph');
        return gauss($result, 'percentage');
    }

    public function getUserVoteAverageByTermAndSchoolYear($user_id, $term = FALSE, $school_year = FALSE)
    {
        if ( ! $user_id) $user_id = $this->session->id;
        if ( ! $school_year) $school_year = $this->current_school_year;

        $this->db->select('ROUND(SUM(user_vote) / SUM(max_vote) * 100, 0) as average, user_vote');
        $this->db->join('projects', 'projects.id = results.project_id', 'LEFT');
        $this->db->where('user_id', $user_id);
        $this->db->where('projects.is_activated', TRUE);
        $this->db->where('school_year', $school_year);

        return $this->db->get('results')->row('average');
    }


    /**
    *
    * Body of table on admin/results
    *
    */
    public function tableBodyClassResultsBySkillsGroup($class = FALSE, $term = FALSE, $school_year = FALSE)
    {
        if($class === FALSE) throw new Exception("Error: Argument $class missing", 1);

        $this->db->select("users.id as user_id, name, last_name, project_name, skills_group, projects.id as project_id,
        (CASE WHEN ISNULL(results.id) THEN '" . _('NE') . "' ELSE SUM(user_vote) END ) AS user_vote,
        SUM(assessments.max_vote) as max_vote", FALSE);

        $this->db->from('users');
        $this->db->join('projects', 'projects.class = users.class');
        $this->db->join('projects_assessments', 'projects_assessments.project_id = projects.id');
        $this->db->join('assessments', 'projects_assessments.assessment_id = assessments.id');
        $this->db->join('results', 'results.project_id = projects.id AND results.user_id = users.id AND results.assessment_id = assessments.id', 'left');

        $this->db->where('role', 'student');
        $this->db->where('users.class', $class);
        $this->db->where('is_activated', TRUE);
        $this->db->where('admin_id', $this->session->id);

        if($school_year) $this->db->where("school_year", $school_year);
        else $this->db->where("school_year", $this->current_school_year);

        if($term) $this->db->where('term', $term);

        $this->db->group_by('users.id,  skills_group, projects.id', 'ASC');
        $this->db->order_by('users.last_name, projects.term, projects.id, skills_group');

        $results = $this->db->get()->result();

        $table = array();

        foreach($results as $result)
        {
            $table[$result->user_id]['name'] = $result->name;
            $table[$result->user_id]['last_name'] =$result->last_name;
            $table[$result->user_id]['average'] = $this->getUserVoteAverageByTermAndSchoolYear($result->user_id, $term);

            unset($result->name, $result->last_name);
            $table[$result->user_id]['results'][] = $result;
        }
        return $table;
    }


    public function boolIfGradedProject($user_id, $project_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('project_id', $project_id);
        $check = $this->db->get('results', 1);
        if($check->row()) return TRUE;
        return FALSE;
    }

    public function getResultsByProjectAndUser($project_id, $user_id = FALSE)
    {
        if (!$user_id) $user_id = $this->session->id;

        $sql = "SELECT assessments.max_vote, user_vote, skills_group, criterion, `cursor`
        FROM results, projects, assessments
        WHERE results.project_id = projects.id
        AND assessments.id = results.assessment_id
        AND results.project_id = ?
        AND results.user_id = ?
        ORDER BY assessments.skills_group, assessments.id";

        $query = $this->db->query($sql, array($project_id, $user_id));
        $results = $query->result();

        if(!$results) $results = $this->getAssessmentsUngraded($project_id);

        return $results;
    }

    private function getAssessmentsUngraded($project_id)
    {
        $this->db->distinct();
        $this->db->select('skills_group, criterion, cursor');
        $this->db->join('assessments', 'projects_assessments.project_id = assessments.id', 'left');
        $this->db->where('project_id', $project_id);

        return $this->db->get('projects_assessments')->result('Results_model');
    }


    public function getResultsTable($user_id, $project_id)
    {
        $this->load->model('Assessment_model','',TRUE);

        $assessment_grid = $this->Assessment_model->getAssessmentTable($project_id);

        $filled_table = array();

        foreach ($assessment_grid as $row)
        {
            $result = $this->getResultsByAssessmentId($user_id, $project_id, $row->id);

            $row->acquis = NULL;
            $row->user_vote  = '--';

            if($result)
            {
                $row->acquis = $result->acquis;
                $row->user_vote = $result->user_vote;
            }

            array_push($filled_table, $row);
        }
        return $filled_table;
    }

    private function getResultsByAssessmentId($user_id, $project_id, $assessment_id)
    {
        $sql = "SELECT assessments.max_vote, user_vote,
        (CASE WHEN (user_vote >= assessments.max_vote / 2) THEN TRUE ELSE FALSE END) as acquis,
        SUM(user_vote) as total_max, (CASE WHEN ISNULL(SUM(results.max_vote)) THEN '--' ELSE SUM(results.max_vote) END) as total_user
        FROM results, projects, assessments
        WHERE results.project_id = projects.id
            AND assessments.id = results.assessment_id
            AND results.project_id = ?
            AND results.user_id = ?
            AND assessments.id = ?
        ORDER BY assessments.skills_group";

        $query = $this->db->query($sql, array($project_id, $user_id, $assessment_id));
        $results = $query->row();

        return $results;
    }

    public function getUserOverallResults($skills_groups, $projects, $user_id = FALSE)
    {
        if ( ! $user_id) $user_id = $this->session->id;

        // declare array for empty results
        $results = array();

        // get results for each skill group project, returns 'null' if no results
        foreach ($skills_groups as $skills_group)
        {
            foreach ($projects as $project)
            {
                $sql = "SELECT school_year, project_name, SUM(assessments.max_vote), skills_group, project_id,
                ROUND(SUM(user_vote) / SUM(results.max_vote) * 100) as user_percentage
                FROM results
                LEFT JOIN  assessments
                    ON assessments.id = assessment_id
                LEFT JOIN projects
                    ON projects.id = project_id
                WHERE results.user_id = ?
                    AND project_id = ?
                    AND skills_group = ?
                GROUP BY project_id, skills_group
                ORDER BY school_year, term, project_id";

                $query = $this->db->query($sql, array($user_id, $project->project_id, $skills_group->name));
                $query = $query->result();

                if( ! $query)
                {
                    $query[0] = new Results_model;
                    $query[0]->max_vote = 'null';
                    $query[0]->user_percentage = 'null';
                    $query[0]->project_name = $project->project_name;
                    $query[0]->skills_group = $skills_group->name;
                }

                array_push($results, $query[0]);
            }
        }
        return $results;
    }

    public function getUserProjectOverallResult($user_id = FALSE, $project_id)
    {
        if (!$user_id) $user_id = $this->session->id;

        $this->db->select('SUM(user_vote) as total_user, SUM(results.max_vote) as total_max', FALSE);
        $this->db->where('results.user_id', $user_id);
        $this->db->where('project_id', $project_id);

        return $this->db->get('results')->row();
    }

}
    ?>
