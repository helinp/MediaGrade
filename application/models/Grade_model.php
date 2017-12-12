<?php
Class Grade_model extends CI_Model
{

	/**
	* Returns if a student project is already graded
	*
	* @param 	integer		$project_id
	* @param 	integer		$user_id = Current user_id
	* @param 	string		$term = All terms
	* @return	boolean
	*/
	public function isProjectGradedByProjectAndUser($project_id, $user_id = FALSE, $term = FALSE)
	{
		if( ! $user_id) $user_id = $this->session->id;

		$this->db->from('results');
		$this->db->where('project_id', $project_id);
		$this->db->where('user_id', $user_id);

		if ($term) $this->db->where('term', $term);

		return ( ! empty($this->db->get()->result()));
	}

	/**
	* Returns NOT graded projects
	*
	* @param 	integer		$class = all classes
	* @param 	integer		$school_year = all school years
	* @return	object
	*/
	public function listUngradedProjects($class = FALSE, $school_year = FALSE)
	{

		$this->db->select('projects.class, projects.term, users.name, users.last_name,
		projects.project_name, users.id as user_id, projects.id as project_id');
		$this->db->distinct();
		$this->db->from('submitted, users, projects');

		$this->db->where(' 	NOT EXISTS(
									SELECT NULL
									FROM results
									WHERE submitted.user_id = results.user_id
									AND submitted.project_id = results.project_id
									)
			');

		if($class) $this->db->where('projects.class', $class);
		if($school_year) $this->db->where('school_year', $school_year);

		$this->db->where('projects.id = submitted.project_id');
		$this->db->where('users.id = submitted.user_id');
		$this->db->where('admin_id', $this->session->id);

		return $this->db->get()->result();
	}

	public function listUngradedProjectsByProjectId($project_id)
	{

		$this->db->select('projects.class, projects.term, users.name, users.last_name,
		projects.project_name, users.id as user_id, projects.id as project_id');
		$this->db->select("DATE_FORMAT(`time`, '%d %M %Y à %H:%i') as `time`", FALSE);
		$this->db->distinct();
		$this->db->from('submitted, users, projects');

		$this->db->where(' 	NOT EXISTS(
									SELECT NULL
									FROM results
									WHERE submitted.user_id = results.user_id
									AND submitted.project_id = results.project_id
									)
			');
		$this->db->where('projects.id', $project_id);
		$this->db->where('projects.id = submitted.project_id');
		$this->db->where('users.id = submitted.user_id');

		return $this->db->get()->result();
	}

	/**
	* Saves or update votes on DB
	*
	* @param 	integer		$project_id
	* @param 	integer		$user_id
	* @param 	integer		$assessment_id
	* @param 	integer		$user_vote
	* @return	boolean
	*/
	public function grade($project_id, $user_id, $assessment_id, $user_vote)
	{
		// get max_vote from assessments DB
		$this->db->where('assessments.id', $assessment_id);
		$q = $this->db->get('assessments', 1);
		$max_vote = $q->row('max_vote');

		// avoid calulation errors
		if($user_vote > 0)
		{
			// get percentual vote
			$user_vote = $user_vote * round($max_vote / 10, 1, PHP_ROUND_HALF_DOWN);
		}

		$data = array(
			'user_id' => $user_id,
			'project_id' => $project_id,
			'assessment_id' => $assessment_id,
			'user_vote' => $user_vote,
			'max_vote' => $max_vote
		);

		$where = array(
			'user_id' => $user_id,
			'project_id' => $project_id,
			'assessment_id' => $assessment_id
		);

		// checks if record already exists
		$this->db->where($where);
		$q = $this->db->get_where('results', $where, 1);

		// if true, update
		if ($q->num_rows() > 0)
		{
			$this->db->where($where);
			$this->db->update('results', $data);
		}
		// else insert
		else
		{
			$this->db->insert('results', $data);
		}
		return TRUE;
	}

	/**
	* removes vote on DB
	*
	* @param 	integer		$user_id
	* @param 	integer		$assessment_id
	* @return	void
	*/
	public function removeVote($assessment_id, $user_id)
	{
		$this->db->delete('results', array('assessment_id' => $assessment_id, 'user_id' => $user_id));
	}

	/**
	* Checks if user is already graded
	*
	* @param 	integer		$user_id
	* @param 	integer		$assessment_id
	* @return	boolean
	*/
	public function isAssessmentGraded($assessment_id, $user_id)
	{
		$query = $this->db->get_where('results', array('assessment_id' => $assessment_id, 'user_id' => $user_id), 1);
		return $query->num_rows() <> 0;
	}
}
?>
