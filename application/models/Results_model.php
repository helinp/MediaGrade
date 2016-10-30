<?php
Class Results_model extends CI_Model
{

	/**
	 * @var integer
	 */
    public $max_vote;

	/**
	 * @var mixed (int | string)
	 * 'NE' for 'Non évalué' (Not Graded)
	 */
    public $user_vote = 'NE';

	/**
	 * @var string
	 */
    public $skills_group;

	/**
	 * @var string
	 */
    public $criterion;

	/**
	 * @var string
	 */
    public $cursor;

    function __construct()
    {
        $this->load->helper('school');
        $this->current_school_year = get_school_year();
    }

	/**
	 * Generates gaussian bell curve of student's votes
	 *
	 * @param 	string		$class
	 * @param 	string		$school_year
	 * @param 	boolean		$admin_id
	 * @return	array
	 */
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

	/**
	 * Returns average vote of a student
	 *
	 * @param 	integer		$user_id
	 * @param 	string		$term
	 * @param 	string		$school_year
	 * @return	float
	 */
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
	  * Returns body of results table
	  *
	  * @param 	string		$class
	  * @param 	string		$term			FALSE
	  * @param 	string		$school_year	FALSE
	  * @return	array
	  */
    public function tableBodyClassResultsBySkillsGroup($class, $term = FALSE, $school_year = FALSE)
    {
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

        if($school_year)
		{
			$this->db->where("school_year", $school_year);
		}
		else
		{
			$this->db->where("school_year", $this->current_school_year);
		}

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


	/**
	 * Returns either or not a user's project is graded
	 *
	 * @param 	integer		$user_id
	 * @param 	integer		$project_id
	 * @return	boolean
	 */
    public function IsProjectGraded($user_id, $project_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('project_id', $project_id);
        $check = $this->db->get('results', 1);

        if($check->row())
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
    }

	/**
	 * Returns an user's project votes
	 *
	 * @param 	integer		$project_id
	 * @param 	integer		$user_id	= Current session user_id
	 * @return	object
	 */
    public function getResultsByProjectAndUser($project_id, $user_id = FALSE)
    {
        if ( ! $user_id) $user_id = $this->session->id;

		$this->db->from('results');
		$this->db->join('projects', 'results.project_id = projects.id');
		$this->db->join('assessments', 'assessments.id = results.assessment_id');
		$this->db->select('assessments.max_vote, user_vote, skills_group, criterion, `cursor`');

		$this->db->where('results.project_id', $project_id);
		$this->db->where('results.user_id', $user_id);

		$this->db->order_by('assessments.skills_group', 'ASC');
		$this->db->order_by('assessments.id', 'ASC');

        $results = $this->db->get()->result();
        if( ! $results) $results = $this->_getAssessmentsUngraded($project_id);

        return $results;
    }

	/**
	 * Returns assessments from a specific project_id
	 *
	 * @param 	integer		$project_id
	 * @return	object
	 */
    private function _getAssessmentsUngraded($project_id)
    {
        $this->db->distinct();
        $this->db->select('skills_group, criterion, cursor');
        $this->db->join('assessments', 'projects_assessments.project_id = assessments.id', 'left');
        $this->db->where('project_id', $project_id);

        return $this->db->get('projects_assessments')->result('Results_model');
    }

	/**
	 * Returns results table of a student's project
	 *
	 * @param 	integer		$user_id
	 * @param 	integer		$project_id
	 * @return	object
	 */
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

	/**
	 * Returns single assessment result of a student's project
	 *
	 * @param 	integer		$user_id
	 * @param 	integer		$project_id
	 * @param 	integer		$assessment_id
	 * @return	object
	 */
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

	/**
	 * Returns result table (averaged by skill) of a student's project
	 *
	 * @param 	integer		$user_id
	 * @param 	integer		$project_id
	 * @return	object
	 */
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

	/**
	 * Returns final averaged vote of a student's project
	 *
	 * @param 	integer		$user_id = session->user_id
	 * @param 	integer		$project_id
	 * @return	object
	 */
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
