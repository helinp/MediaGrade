<?php
Class Results_model extends CI_Model
{


    public function getUserVoteAverage($user_id, $periode = FALSE)
    {
        if (!$user_id) $user_id = $this->session->id;


        $sql = "SELECT ROUND(SUM(user_vote) / SUM(max_vote) * 100, 0) as average, user_vote
                FROM results
                LEFT JOIN projects
                    ON projects.id = results.project_id
                WHERE user_id = ?
                AND projects.is_activated = TRUE
                ";

        if($periode)
        {
            $sql .= " AND periode = ?";
            $query = $this->db->query($sql, array($user_id, $periode));
        }
        else
        {
            $query = $this->db->query($sql, array($user_id));
        }
        return $query->row('average');
    }

    public function tableBodyClassResultsBySkillsGroup($class = FALSE, $periode = FALSE)
    {
        if($class === FALSE) return FALSE;

        /*$sql = "SELECT users.id as user_id, name, last_name, project_name, skills_group,
                    (CASE WHEN ISNULL(user_vote) THEN '--' ELSE SUM(user_vote) END ) AS user_vote,
                    SUM(assessments.max_vote) as max_vote
                FROM users
                LEFT JOIN projects
                	ON projects.class = users.class
                LEFT JOIN results
                	ON results.project_id = projects.id
                    AND results.user_id = users.id
                LEFT JOIN assessments
                	ON results.assessment_id = assessments.id
                WHERE role='student' AND projects.is_activated = 1 AND users.class = ?
                GROUP by users.id, projects.id, skills_group
                ORDER BY users.class, users.last_name, projects.periode, projects.deadline";*/

                $sql ="SELECT users.id as user_id, name, last_name, project_name, skills_group, projects.id as project_id,
                            (CASE WHEN ISNULL(user_vote) THEN '--' ELSE SUM(user_vote) END ) AS user_vote,
                            SUM(assessments.max_vote) as max_vote
                        FROM users
                        LEFT JOIN projects
                        	ON projects.class = users.class
                       	LEFT JOIN projects_assessments
                        	ON projects_assessments.project_id = projects.id
                         LEFT JOIN assessments
                        	ON projects_assessments.assessment_id = assessments.id
                        LEFT JOIN results
                        	ON results.project_id = projects.id
                            AND results.user_id = users.id
                            AND results.assessment_id = assessments.id
                        WHERE role='student'
                            AND users.class = ?
                            AND is_activated = TRUE ";
                if ($periode) $sql .= "AND periode = ? ";

                $sql .= "GROUP by users.id, projects.id, skills_group
                        ORDER BY users.class, users.last_name, projects.periode, projects.deadline, skills_group";

                if ($periode)
                    $query = $this->db->query($sql, array($class, $periode));
                else
                    $query = $this->db->query($sql, array($class));

                $results = $query->result();

                $table = array();

                foreach($results as $result)
                {

                    $table[$result->user_id]['name'] = $result->name;
                    $table[$result->user_id]['last_name'] =$result->last_name;

                    $table[$result->user_id]['average'] = $this->getUserVoteAverage($result->user_id, $periode);

                    unset($result->name, $result->last_name);
                    $table[$result->user_id]['results'][] = $result;
                }

                return $table;

    }


    public function isProjectGraded($user_id, $project_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('project_id', $project_id);
		$check = $this->db->get('results', 1);
		if($check->row()) return TRUE;
		return FALSE;
	}

    public function getResults($project_id, $user_id = false)
	{
			if (!$user_id) $user_id = $this->session->id;

			$sql = "SELECT assessments.max_vote, user_vote, skills_group, criterion, `cursor`
                    FROM results, projects, assessments
                    WHERE results.project_id = projects.id
              		    AND assessments.id = results.assessment_id
                        AND results.project_id = ?
                        AND results.user_id = ?
                    ORDER BY assessments.skills_group";

			$query = $this->db->query($sql, array($project_id, $user_id));
			$results = $query->result();

			return $results;
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

			$sql = "SELECT assessments.max_vote, user_vote, (CASE WHEN (user_vote > assessments.max_vote / 2) THEN TRUE ELSE FALSE END) as acquis,
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

    public function getUserOverallResults($user_id = FALSE, $raw = FALSE)
    {

			if (!$user_id) $user_id = $this->session->id;

			// declare empty variable if no result
			$results = '';

			$sql = "SELECT user_vote, project_name, assessments.max_vote, skills_group, project_id,
							ROUND(SUM(user_vote) / SUM(results.max_vote)  * 100, 0) as user_percentage,
                            name, last_name
					FROM results
					LEFT JOIN  assessments
                        ON assessments.id = assessment_id
					LEFT JOIN projects
						ON projects.id = project_id
                    LEFT JOIN users
                        ON users.id = user_id
					WHERE results.user_id = ?
                    GROUP BY project_id, skills_group
					ORDER BY project_id ASC, skills_group ASC";

			$query = $this->db->query($sql, array($user_id));

			$query = $query->result();

            if($raw) return $query;
			return $this->graphResults($query);
		}

        public function getUserProjectOverallResult($user_id = FALSE, $project_id)
        {

    			if (!$user_id) $user_id = $this->session->id;

    			$sql = "SELECT SUM(user_vote) as total_user, SUM(results.max_vote) as total_max
    					FROM results
    					WHERE results.user_id = ?
                            AND project_id = ?
                        GROUP BY project_id";

    			$query = $this->db->query($sql, array($user_id, $project_id));

    			$query = $query->row();

                if($query) return $query;

    		}

        public function getClassResults($class, $period = FALSE)
        {
    			// declare empty variable if no result
    			$results = '';

    			$sql = "SELECT user_vote, project_name, assessments.max_vote, skills_group, project_id,
    							ROUND(user_vote / results.max_vote * 100, 0) as user_percentage
    					FROM results
    					LEFT JOIN  assessments
                            ON assessments.id = assessment_id
    					LEFT JOIN projects
    						ON projects.id = project_id
    					WHERE results.user_id = ?
                        GROUP BY project_id, skills_group
    					ORDER BY project_id ASC, skills_group ASC";

    			$query = $this->db->query($sql, array($user_id));

    			$query = $query->result();


    			return $this->graphResults($query);
    		}

    /**
     *  Generate formatted data for highcharts
     *
     */
    private function graphResults($results)
    {

			// TODO:  Add date

			$string = '';
			$tmp_objective = '';

			foreach ($results as $row)
        {
					if ($tmp_objective !== $row->skills_group)
					{
						if (!empty($tmp_objective))
						{
								$string = substr("$string", 0, -2);
								$string .= "]}, {";
						}

						$tmp_objective = $row->skills_group;


						$string .= "name:'$row->skills_group',\ndata:[";
					}

					$string .= $row->user_percentage;

					$string .= ', ';
        }

				$string = substr("$string", 0, -2);
				$string .= "]";

				return($string);
    }

// TODO: replace by function on projects model
        public function getUserProjects($user_id = false)
		{

			if (!$user_id) $user_id = $this->session->id;

			// declare empty variable if no result
			$results = '';

			$sql = "SELECT project_name
								FROM results
								LEFT JOIN  assessments
									ON assessments.id = assessment_id
								LEFT JOIN projects
									ON projects.id = project_id
								WHERE results.user_id = ?
								GROUP BY project_id, skills_group
								ORDER BY project_id ASC, skills_group ASC";

			$query = $this->db->query($sql, array($user_id));

			$query = $query->result();


			return $this->graphProjects($query);
		}

        /**
         *  Generate x axis names for highcharts
         *
         */
		private function graphProjects($projects)
		{

			// TODO:  Add date

			$string = '';

			foreach ($projects as $project)
				{

						$string .= "'" . $project->project_name . "'";
						$string .= ', ';
				}

				$string = substr("$string", 0, -2);

				return($string);
		}
}
?>
