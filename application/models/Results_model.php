<?php
Class Results_model extends CI_Model
{

	/**
	* @var integer
	*/
	public $project_id;

	/**
	* @var integer
	*/
	public $max_vote;

	/**
	* @var integer
	*/
	public $percentage;

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
	public function getGaussDataByClassAndSchoolYearAndAdmin($class = FALSE, $school_year = FALSE, $admin_id = FALSE, $skills_group = FALSE)
	{
		$this->db->select('ROUND(SUM(user_vote) / SUM(results.max_vote) * 10, 0) AS percentage, skills_group');
		$this->db->join('projects', 'projects.id = results.project_id', 'left');
		$this->db->join('assessments', 'assessments.id = results.assessment_id', 'left');
		$this->db->where('projects.is_activated', TRUE);
		$this->db->where('user_vote >= 0');
		$this->db->where('projects.assessment_type !=', 'diagnostic');

		if($class) $this->db->where('class', $class);
		if($school_year) $this->db->where('school_year', $school_year);
		if($admin_id) $this->db->where('admin_id', $admin_id);
		if($skills_group) $this->db->where('skills_group', $skills_group);

		$this->db->group_by('user_id');
		$this->db->order_by('percentage', 'ASC');
		$result = $this->db->get('results')->result();

		$this->load->helper('graph');
		$data['percentage'] = gauss($result, 'percentage');

		if($skills_group && isset($result[0]))
		{
			$data['skills_group'] = js_special_chars($result[0]->skills_group);
		}
		elseif($skills_group && ! isset($result[0]))
		{
			$data['skills_group'] = js_special_chars($skills_group);
		}

		return $data;
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
		if($term) $this->db->where('term', $term);

		$this->db->select('ROUND(SUM(user_vote) / SUM(max_vote) * 100, 0) as average, user_vote, terms.name AS term_name');
		//$this->db->select('max_vote, user_vote, terms.name AS term_name, projects.assessment_type, school_year, projects.project_name');
		$this->db->join('projects', 'projects.id = results.project_id', 'RIGHT');
		$this->db->join('terms', 'projects.term = terms.id');
		$this->db->where('user_id', $user_id);
		$this->db->where('user_vote >= 0');
		$this->db->where('projects.is_activated', TRUE);
		$this->db->where('projects.assessment_type <>', 'diagnostic');
		$this->db->where('school_year', $school_year);


		$result = $this->db->get('results');

		if ($result)
		{
			return $result->row('average');
		}
		else
		{
			return 'NE';
		}
	}
	public function getUserVoteAverageByCourseIdAndTermAndSchoolYear($user_id, $course_id, $term = FALSE, $school_year = FALSE)
	{
		if ( ! $user_id) $user_id = $this->session->id;
		if ( ! $school_year) $school_year = $this->current_school_year;
		if($term) $this->db->where('term', $term);

		$this->db->select('ROUND(SUM(user_vote) / SUM(max_vote) * 100, 0) as average, user_vote, terms.name AS term_name');
		//$this->db->select('max_vote, user_vote, terms.name AS term_name, projects.assessment_type, school_year, projects.project_name');
		$this->db->join('projects', 'projects.id = results.project_id', 'RIGHT');
		$this->db->join('terms', 'projects.term = terms.id');
		$this->db->where('user_id', $user_id);
		$this->db->where('user_vote >= 0');
		$this->db->where('course_id', $course_id);
		$this->db->where('projects.is_activated', TRUE);
		$this->db->where('projects.assessment_type <>', 'diagnostic');
		$this->db->where('school_year', $school_year);


		$result = $this->db->get('results');

		if ($result)
		{
			return $result->row('average');
		}
		else
		{
			return 'NE';
		}
	}

	public function getUserVoteAverageBySchoolYear($user_id, $school_year = FALSE)
	{
		if ( ! $user_id) $user_id = $this->session->id;
		if ( ! $school_year) $school_year = $this->current_school_year;

		$this->db->select('ROUND(SUM(user_vote) / SUM(max_vote) * 100, 0) as average, user_vote, terms.name AS term_name');
		$this->db->join('projects', 'projects.id = results.project_id', 'LEFT');
		$this->db->join('terms', 'projects.term = terms.id');
		$this->db->where('user_vote >= 0');
		$this->db->where('user_id', $user_id);
		$this->db->where('projects.is_activated', TRUE);
		$this->db->where('projects.assessment_type <>', 'diagnostic');
		$this->db->where('school_year', $school_year);

		$result = $this->db->get('results');

		if ($result)
		{
			return $result->row('average');
		}
		else
		{
			return 'NE';
		}
	}

	public function getUserDeviationByCourseIdAndTermAndSchoolYear($user_id, $course_id, $term = FALSE, $school_year = FALSE)
	{
		if ( ! $user_id) $user_id = $this->session->id;
		if ( ! $school_year) $school_year = $this->current_school_year;

		$this->db->select('ROUND(SUM(user_vote) / SUM(max_vote) * 100, 0) as class_average');
		$this->db->join('projects', 'projects.id = results.project_id', 'LEFT');
		$this->db->where('projects.is_activated', TRUE);
		$this->db->where('user_vote >= 0');
		$this->db->where('projects.assessment_type !=', 'diagnostic');
		$this->db->where('school_year', $school_year);
		$this->db->where('course_id', $course_id);
		if($term)
		{
			$this->db->where('term', $term);
		}
		$result = $this->db->get('results');

		$class_average = $result->row('class_average');
		$user_average = $this->getUserVoteAverageByCourseIdAndTermAndSchoolYear($user_id, $course_id, $term, $school_year);
		$deviation = $user_average - $class_average;

		if ($result)
		{
			return $deviation;
		}
		else
		{
			return '--';
		}
	}

	/**
	* Returns students ranking
	*
	* @param 	string		'asc'
	* @param	integer		60
	* @param 	string		$term
	* @param 	string		$school_year
	* @return	float
	*/
	public function getStudentsRankingByTermAndClassAndSchoolYear($type = 'ASC', $threshold  = 60, $limit = 5, $term = FALSE, $class = FALSE, $school_year = FALSE)
	{
		if ( ! $school_year) $school_year = $this->current_school_year;

		$this->db->limit($limit);
		$this->db->select('ROUND(SUM(user_vote) / SUM(max_vote) * 100, 0) as average, user_vote, first_name, last_name, users.class, user_id, classes.name AS class_name');
		$this->db->join('projects', 'projects.id = results.project_id', 'LEFT');
		$this->db->join('users', 'users.id = results.user_id', 'LEFT');
		$this->db->join('classes', 'classes.id = projects.class');
		$this->db->group_by('user_id');
		$this->db->where('user_vote >= 0');
		$this->db->where('projects.assessment_type !=', 'diagnostic');
		$this->db->where('projects.is_activated', TRUE);
		$this->db->where('school_year', $school_year);
		if($class) $this->db->where('users.class', $class);
		if($term) $this->db->where('term', $term);

		if($type === 'desc' || $type === 'DESC')
		{
			$this->db->having('average >', $threshold);
			$this->db->order_by('average', 'DESC');
		}
		else
		{
			$this->db->having('average <=', $threshold);
			$this->db->order_by('average', 'ASC');
		}

		return $this->db->get('results')->result();
	}

	/**
	* Returns detailled results (by cursor)
	*
	* @param 	integer		$user_id
	* @param 	string		$term
	* @param 	string		$school_year
	* @return	float
	*/
	public function getDetailledResults($group_by, $user_id = FALSE, $term = FALSE, $school_year = FALSE)
	{
		if ( ! $user_id) $user_id = $this->session->id;
		if ( ! $school_year) $school_year = $this->current_school_year;

		$this->db->select('ROUND(SUM(user_vote) / SUM(results.max_vote) * 100, 0) as average, COUNT(results.max_vote) as count, user_vote, criterion, cursor');
		$this->db->select('CONCAT (`' . $group_by . '`, " (", COUNT(results.max_vote), ")") conca', TRUE);
		$this->db->join('projects', 'projects.id = results.project_id', 'LEFT');
		$this->db->join('assessments', 'results.assessment_id = assessments.id', 'LEFT');

		$this->db->where('user_id', $user_id);
		$this->db->where('projects.is_activated', TRUE);
		$this->db->where('school_year', $school_year);
		if($term) $this->db->where('term', $term);

		$this->db->group_by('assessments.' . $group_by);
		$this->db->order_by('criterion', 'ASC');

		return $this->db->get('results')->result_array();
	}



	public function getBestCursorResults($limit = 0, $pivot = 79, $user_id = FALSE, $term = FALSE, $school_year = FALSE)
	{
		if ( ! $user_id) $user_id = $this->session->id;
		if ( ! $school_year) $school_year = $this->current_school_year;

		$this->db->select('ROUND(SUM(user_vote) / SUM(results.max_vote) * 100, 0) as average, COUNT(results.max_vote) as count, user_vote, criterion, cursor');
		$this->db->select('CONCAT (`cursor`, " (", COUNT(results.max_vote), ")") conca', TRUE);
		$this->db->join('projects', 'projects.id = results.project_id', 'LEFT');
		$this->db->join('assessments', 'results.assessment_id = assessments.id', 'LEFT');

		$this->db->where('projects.assessment_type !=', 'diagnostic');
		$this->db->where('user_id', $user_id);
		$this->db->where('projects.is_activated', TRUE);
		$this->db->where('school_year', $school_year);
		$this->db->having("average > $pivot");
		if($term) $this->db->where('term', $term);

		$this->db->group_by('assessments.cursor');
		$this->db->order_by('count', 'DESC');
		$this->db->limit($limit);

		return $this->db->get('results')->result_array();
	}


	public function getWorstCursorResults($limit = 0, $pivot = 60, $user_id = FALSE, $term = FALSE, $school_year = FALSE)
	{
		if ( ! $user_id) $user_id = $this->session->id;
		if ( ! $school_year) $school_year = $this->current_school_year;

		$this->db->select('ROUND(SUM(user_vote) / SUM(results.max_vote) * 100, 0) as average, COUNT(results.max_vote) as count, user_vote, criterion, cursor');
		$this->db->select('CONCAT (`cursor`, " (", COUNT(results.max_vote), ")") conca', TRUE);
		$this->db->join('projects', 'projects.id = results.project_id', 'LEFT');
		$this->db->join('assessments', 'results.assessment_id = assessments.id', 'LEFT');

		$this->db->where('projects.assessment_type !=', 'diagnostic');
		$this->db->where('user_id', $user_id);
		$this->db->where('projects.is_activated', TRUE);
		$this->db->where('school_year', $school_year);
		$this->db->having("average < $pivot");
		if($term) $this->db->where('term', $term);

		$this->db->group_by('assessments.cursor');
		$this->db->order_by('count', 'DESC');
		$this->db->limit($limit);

		return $this->db->get('results')->result_array();
	}

	/**
	* Returns criteria ranking
	*
	* @param 	integer		$user_id
	* @param 	string		$term
	* @param 	string		$school_year
	* @return	float
	*/
	public function getCriteriaRanking($user_id = FALSE, $order = 'ASC', $limit = 5, $school_year = FALSE)
	{
		if ( ! $user_id) $user_id = $this->session->id;
		if ( ! $school_year) $school_year = $this->current_school_year;

		$this->db->select('ROUND(SUM(user_vote) / SUM(results.max_vote) * 100, 0) as average, COUNT(results.max_vote) as count, user_vote, criterion, cursor');
		$this->db->join('projects', 'projects.id = results.project_id', 'LEFT');
		$this->db->join('assessments', 'results.assessment_id = assessments.id', 'LEFT');
		$this->db->limit($limit);
		$this->db->where('user_id', $user_id);
		$this->db->where('projects.is_activated', TRUE);
		$this->db->where('projects.assessment_type !=', 'diagnostic');
		$this->db->where('school_year', $school_year);

		$this->db->group_by('assessments.criterion');
		$this->db->order_by('average', $order);

		return $this->db->get('results')->result_array();
	}

	/**
	* Returns result for project and student
	*
	* @param 	string		$class
	* @param 	string		$term			FALSE
	* @param 	string		$school_year	FALSE
	* @return	array
	*/
	/* DEPRECATED? */
	public function studentProjectResults($student_id, $project_id, $term = FALSE)
	{
		// This Query include not graded criteria
		$this->db->select("skills_group, project_id, user_id,
		(CASE WHEN ISNULL(results.id) THEN '" . _('NE') . "' ELSE SUM(user_vote) END ) AS user_vote,
		SUM(results.max_vote) as max_vote", FALSE);

		$this->db->from('results');
		$this->db->join('assessments', 'assessments.id = results.assessment_id');
		$this->db->join('projects', 'projects.id = results.project_id');

		$this->db->where('results.user_id', $student_id);
		$this->db->where('results.project_id', $project_id);
		if($term) $this->db->where('projects.term', $term);

		$this->db->group_by('user_id, skills_group, projects.id', 'ASC');

		$results = $this->db->get()->result();

		if( ! empty($results))
		{
			return $results;
		}
		else
		{
			return $this;
		}
	}

	/**
	* Returns body of results table (OBSOLETE)
	*
	* @param 	string		$class
	* @param 	string		$term			FALSE
	* @param 	string		$school_year	FALSE
	* @return	array
	*/
	public function tableBodyClassResultsBySkillsGroup($class, $term = FALSE, $school_year = FALSE)
	{
		$this->db->select("users.id as user_id, first_name, last_name, project_name, skills_group, projects.id as project_id,
		(CASE WHEN ISNULL(results.id) THEN '" . _('NE') . "' ELSE SUM(user_vote) END ) AS user_vote,
		SUM(assessments.max_vote) as max_vote", FALSE);

		$this->db->from('users');
		$this->db->join('projects', 'projects.class = users.class');
		$this->db->join('projects_assessments', 'projects_assessments.project_id = projects.id');
		$this->db->join('assessments', 'projects_assessments.assessment_id = assessments.id');
		$this->db->join('results', 'results.project_id = projects.id AND results.user_id = users.id AND results.assessment_id = assessments.id', 'left');

		$this->db->where('role', 'student');
		$this->db->where('projects.assessment_type !=', 'diagnostic');
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

		$this->db->group_by('users.id, skills_group, projects.id', 'ASC');

		$this->db->order_by('users.last_name', 'ASC');
		$this->db->order_by('deadline', 'ASC');
		$this->db->order_by('term', 'DESC');
		$this->db->order_by('skills_group', 'ASC');

		$results = $this->db->get()->result();

		$table = array();

		foreach($results as $result)
		{
			$table[$result->user_id]['first_name'] = $result->first_name;
			$table[$result->user_id]['last_name'] =$result->last_name;
			$table[$result->user_id]['average'] = $this->getUserVoteAverageByTermAndSchoolYear($result->user_id, $term);

			unset($result->first_name, $result->last_name);
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
		$this->db->join('skills', 'assessments.skill_id = skills.id');
		$this->db->select('assessments.max_vote, user_vote, skills.skills_group, skills.skill_id AS skill_id, skills.skill AS skill_description, criterion, `cursor`');
		$this->db->select('(user_vote / assessments.max_vote * 100 ) AS percentage');

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

	/* TODO Should be in controller or helper*/
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

		$assessment_grid = $this->Assessment_model->getAssessmentsByProjectId($project_id);

		$filled_table = array();

		foreach ($assessment_grid as $row)
		{
			$result = $this->getResultsByAssessmentId($user_id, $project_id, $row->id);

			$row->acquis = NULL;
			$row->user_vote  = '--';

			if($result)
			{
				//dump($result);
				$row->acquis = $result[0]->acquis;
				$row->user_vote = $result[0]->user_vote;
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
	*
	** TODO CONTROLE!!!!
	*
	*/
	public function getResultsByAssessmentId($user_id, $project_id, $assessment_id)
	{
		$this->db->select("assessments.max_vote, user_vote, user_id, assessment_id, date, project_id, assessment_type,
		(CASE WHEN (user_vote >= assessments.max_vote / 2) THEN TRUE ELSE FALSE END) as acquis,
		SUM(user_vote) as total_max, (CASE WHEN ISNULL(SUM(results.max_vote)) THEN '--' ELSE SUM(results.max_vote) END) as total_user", FALSE);
		$this->db->from('results');
		$this->db->join('projects', 'results.project_id = projects.id');
		$this->db->join('assessments', 'assessments.id = results.assessment_id');

		if ($user_id !== FALSE) $this->db->where('results.user_id', $user_id);
		if ($project_id !== FALSE) $this->db->where('results.project_id', $project_id);
		$this->db->where('assessments.id', $assessment_id);

		if ($project_id === FALSE) $this->db->group_by('user_id'); //new

		$this->db->order_by('assessments.skills_group');

		return $this->db->get()->result();
	}

	public function getStudentResultsByAssessmentIdAndStudentId($assessment_id, $student_id)
	{
		$this->db->select('max_vote, user_vote');
		//$this->db->select('(user_vote / max_vote * 100 ) AS percentage', FALSE);
		$this->db->from('results');
		$this->db->limit(1);

		$this->db->where('assessment_id', $assessment_id);
		$this->db->where('user_id', $student_id);

		return $this->db->get()->row();
	}

	/**
	* Returns result table (averaged by skill) of a student's project
	*
	* @param 	integer		$user_id
	* @param 	array		$projects
	* @return	object

	* TODO Should be two separate methods
	*/
	public function getUserOverallResults($skills_groups, $projects, $user_id = FALSE, $school_year = FALSE)
	{
		if ( ! $user_id) $user_id = $this->session->id;
		if ( ! $school_year) $school_year = get_school_year();
		// declare array for empty results
		$results = array();

		if( ! $skills_groups)
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
				AND projects.assessment_type != 'diagnostic'
				AND project_id = ?
				AND school_year = ?
				GROUP BY project_id
				ORDER BY school_year, term, project_id";

				$query = $this->db->query($sql, array($user_id, $project->project_id, $school_year));
				$query = $query->result_array();

				if( ! $query)
				{
					$query[0]['user_percentage'] = 'null';
				}

				array_push($results, $query[0]['user_percentage']);
			}
			return $results;
		}

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
				AND school_year = ?
				GROUP BY project_id, skills_group
				ORDER BY school_year, term, project_id";

				$query = $this->db->query($sql, array($user_id, $project->project_id, $skills_group->name, $school_year));
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
	* Returns student last progression
	*
	* @return 	0 (no progression) | 1 (progression) | -1 (drop)
	*/
	public function getLastProgression($projects_list, $student_id, $school_year)
	{
		$results = $this->getUserOverallResults(FALSE, $projects_list, $student_id, $school_year);
		$n = count($results);

		if( ! $n) return 0;
		if($results[$n - 1] > $results[$n - 2]) return 1;
		if($results[$n - 1] < $results[$n - 2]) return -1;
		else return 0;

	}


	/**
	* Returns result table (averaged by skill) of a student
	*
	* @param 	string		$skills_groups
	* @param 	integer		$user_id
	* @return	float|string
	*/
	public function getUserOverallResultsBySkillGroup($skills_group, $user_id = FALSE, $school_year = FALSE)
	{
		if ( ! $user_id) $user_id = $this->session->id;
		if ( ! $school_year) $school_year = get_school_year();

		// get results for each skills group , returns 'null' if no results
		$sql = "SELECT school_year, skills_group,
		ROUND(SUM(user_vote) / SUM(results.max_vote) * 100) as user_percentage
		FROM results
		LEFT JOIN projects
		ON projects.id = project_id
		LEFT JOIN  assessments
		ON assessments.id = assessment_id
		WHERE results.user_id = ?
		AND skills_group = ?
		AND school_year = ?
		GROUP BY skills_group
		ORDER BY skills_group
		LIMIT 1";

		$query = $this->db->query($sql, array($user_id, $skills_group, $school_year));
		$result = $query->result();

		if( ! $result)
		{
			return '--';
		}
		return $result[0]->user_percentage;
	}

	public function getSkillsResultsByClassAndSchoolYear($class = FALSE, $school_year = FALSE)
	{
		if ( ! $school_year) $school_year = get_school_year();

		// get results for each skills group , returns 'null' if no results
		$this->db->from('results');
		$this->db->select('school_year, skill_id, skills_group');
		$this->db->select('ROUND(SUM(user_vote) / SUM(results.max_vote) * 100) as percentage', FALSE);
		$this->db->join('projects', 'projects.id = project_id');
		$this->db->join('assessments', 'assessments.id = assessment_id');

		if($class)
		{
			$this->db->where('class', $class);
		}
		$this->db->where('school_year', $school_year);
		$this->db->group_by('skill_id');
		$results = $this->db->get()->result();

		return $results;
	}

	public function getSkillsStatsByClassAndSchoolYear($class = FALSE, $school_year = FALSE)
	{
		if ( ! $school_year) $school_year = get_school_year();

		// get results for each skills group , returns 'null' if no results
		$this->db->from('results');
		$this->db->select('school_year, assessments.skill_id, assessments.skills_group, user_id, skills.skill_id AS skill_shortname');
		$this->db->select('ROUND(SUM(user_vote) / SUM(results.max_vote) * 100) as percentage', FALSE);
		$this->db->join('projects', 'projects.id = project_id');
		$this->db->join('assessments', 'assessments.id = assessment_id');
		$this->db->join('skills', 'skills.id = assessments.skill_id');

		if($class)
		{
			$this->db->where('class', $class);
		}
		$this->db->where('school_year', $school_year);
		$this->db->group_by('user_id, skill_id');
		$results = $this->db->get()->result();

		$global = array();

		foreach ($results as $result)
		{
			if( ! isset($global[$result->skill_id]))
			{
				$global[$result->skill_id]['success'] = 0;
				$global[$result->skill_id]['failed'] = 0;
				$global[$result->skill_id]['skill'] = $result->skill_id;
				$global[$result->skill_id]['skill_name'] = $result->skill_shortname;
			}

			if($result->percentage < 50)
			{
				$global[$result->skill_id]['failed']++;
			}
			else
			{
				$global[$result->skill_id]['success']++;
			}
		}
	//	dump($global);
		return $global;




	}

	public function getUserResultsBySkill($skill_id, $user_id = FALSE, $school_year = FALSE)
	{
		if ( ! $user_id) $user_id = $this->session->id;
		if ( ! $school_year) $school_year = get_school_year();

		// get results for each skills group , returns 'null' if no results
		$this->db->from('results');

		$this->db->select('user_vote / results.max_vote * 100 as percentage', FALSE);
		$this->db->select('criterion, cursor');
		$this->db->select('projects.project_name, projects.id, projects.instructions_txt');
		$this->db->select('COUNT(results.max_vote) as n_assessments', FALSE);

		$this->db->join('projects', 'projects.id = project_id');
		$this->db->join('assessments', 'assessments.id = assessment_id');
		$this->db->where('results.user_id', $user_id);
		$this->db->where('skill_id', $skill_id);
		$this->db->where('school_year', $school_year);

		$this->db->group_by('criterion');
		$this->db->order_by('term', 'deadline');

		$result = $this->db->get()->result();

		if( ! $result)
		{
			return '--';
		}
		return $result;
	}

	public function getUserResultBySkillId($skill_id, $user_id = FALSE, $school_year = FALSE)
	{
		if ( ! $user_id) $user_id = $this->session->id;
		if ( ! $school_year) $school_year = get_school_year();

		// get results for each skills group , returns 'null' if no results
		$this->db->from('results');

		$this->db->select('SUM(user_vote) / SUM(results.max_vote) * 100 as percentage', FALSE);
		$this->db->where('results.user_id', $user_id);
		$this->db->where('skill_id', $skill_id);
		$this->db->where('school_year', $school_year);
		$this->db->join('assessments', 'assessments.id = assessment_id');
		$this->db->join('projects', 'projects.id = project_id');

		$this->db->group_by('skill_id');
		$this->db->order_by('term', 'deadline');

		$result = $this->db->get()->row('percentage');
		if( ! $result)
		{
			return '--';
		}
		return $result;
	}

/*
	public function getUserResultsBySkillAndProjectId($skill_id, $user_id = FALSE, $school_year = FALSE)
	{
		if ( ! $user_id) $user_id = $this->session->id;
		if ( ! $school_year) $school_year = get_school_year();

		// get results for each skills group , returns 'null' if no results
		$this->db->from('results');
		$this->db->select('school_year', 'skill_id', 'skills_group');
		$this->db->select('SUM(user_vote) as user_vote SUM(results.max_vote) as max_vote', FALSE);
		$this->db->join('projects', 'projects.id = project_id');
		$this->db->join('assessments', 'assessments.id = assessment_id');
		$this->db->where('results.user_id', $user_id);
		$this->db->where('skill_id', $user_id);
		$this->db->where('project_id', $project_id);
		$this->db->where('school_year', $school_year);
		$this->db->group_by('skill_id');
		$this->db->limit(1);query($sql, array($user_id, $skill_id, $school_year));
		$result = $this->db->row();

		if( ! $result)
		{
			return '--';
		}
		return $result;
	}
*/
	public function getResultsBySkillsGroupAndUserIdAndProjectId($skill_group_name, $user_id, $project_id)
	{
		// get results for each skills group , returns 'null' if no results
		$this->db->from('results');
		$this->db->select('skills_group, SUM(user_vote) as user_vote, SUM(results.max_vote) as max_vote, project_id, user_id');
		$this->db->join('assessments', 'assessments.id = results.assessment_id');
		$this->db->where('results.user_id', $user_id);
		$this->db->where('assessments.skills_group', $skill_group_name);
		$this->db->where('project_id', $project_id);
		$this->db->group_by('assessments.skills_group');
	//	$this->db->limit(1);
		$result = $this->db->get()->row();

		return $result;
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
		if ( ! $user_id) $user_id = $this->session->id;

		$this->db->select('user_id, SUM(user_vote) as total_user, SUM(results.max_vote) as total_max', FALSE);
		$this->db->join('projects', 'results.project_id = projects.id');
		$this->db->where('results.user_id', $user_id);
		$this->db->where('project_id', $project_id);

		return $this->db->get('results')->row();
	}


	/***** Projects Stats ******/
	public function getStudentsAverageByProjectId($project_id)
	{
		$this->db->from('results');
		$this->db->join('projects', 'results.project_id = projects.id');
		//		$this->db->select('(user_vote / assessments.max_vote * 100 ) AS percentage');
		$this->db->select('project_id, user_id');
		$this->db->select('SUM(max_vote) AS max_vote', FALSE);
		$this->db->select('SUM(user_vote) AS user_vote', FALSE);
		$this->db->where('results.project_id', $project_id);

		$this->db->group_by('user_id');

		$results = $this->db->get()->result();

		return $results;
	}

	/* TODO Make this two functions below a magic one*/
	public function getSkillsGroupsAverageByProjectIdAndSkillGroup($project_id, $skills_group)
	{
		$this->db->from('results');
		$this->db->join('projects', 'results.project_id = projects.id');
		$this->db->join('assessments', 'results.assessment_id = assessments.id');
		$this->db->select('project_id, skills_group');
		$this->db->select('SUM(results.max_vote) AS max_vote', FALSE);
		$this->db->select('SUM(user_vote) AS user_vote', FALSE);
		$this->db->where('projects.id', $project_id);
		$this->db->where('assessments.skills_group', $skills_group);

		$results = $this->db->get()->row();
		if(! $results->user_vote) return FALSE;
		return $results;
	}

	public function getAverageByProjectIdAndStudentIdAndSkillsGroup($project_id, $student_id, $skills_group)
	{
		$this->db->from('results');
		$this->db->join('projects', 'results.project_id = projects.id');
		$this->db->join('assessments', 'results.assessment_id = assessments.id');
		$this->db->select('project_id, skills_group');
		$this->db->select('SUM(results.max_vote) AS max_vote', FALSE);
		$this->db->select('SUM(user_vote) AS user_vote', FALSE);
		$this->db->where('projects.id', $project_id);
		$this->db->where('user_id', $student_id);
		$this->db->where('skills_group', $skills_group);

		$results = $this->db->get()->row();
		return $results;
	}

	public function getCriterionAverageByProjectId($project_id, $criterion)
	{
		$this->db->from('results');
		$this->db->join('projects', 'results.project_id = projects.id');
		$this->db->join('assessments', 'results.assessment_id = assessments.id');
		$this->db->select('project_id, criterion');
		$this->db->select('SUM(results.max_vote) AS max_vote', FALSE);
		$this->db->select('SUM(user_vote) AS user_vote', FALSE);
		$this->db->where('projects.id', $project_id);
		$this->db->where('assessments.criterion', $criterion);

		$results = $this->db->get()->row();
		if(! $results->user_vote) return FALSE;
		return $results;
	}



}
?>
