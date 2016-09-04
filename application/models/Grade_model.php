<?php
Class Grade_model extends CI_Model
{

	public function boolGradedProjectByProjectAndUser($project_id, $user_id = FALSE, $term = FALSE)
	{
		if( ! $user_id) $user_id = $this->session->id;

		$this->db->from('results');
		$this->db->where('project_id', $project_id);
		$this->db->where('user_id', $user_id);
		if ($term) $this->db->where('term', $term);

		return ( ! empty($this->db->get()->result()));
	}


	public function listNotGradedProjects($class = FALSE, $school_year = FALSE)
	{

		$this->db->select('projects.class, projects.term, users.name, users.last_name,
		projects.project_name, users.id as user_id, projects.id as project_id');
		$this->db->from('submitted, users, projects');

		$this->db->where(' 	NOT EXISTS(SELECT NULL
							FROM results
							WHERE submitted.user_id = results.user_id
							AND submitted.project_id = results.project_id)
						');

		if($class) $this->db->where('projects.class', $class);
		if($school_year) $this->db->where('school_year', $school_year);

		$this->db->where('projects.id = submitted.project_id');
		$this->db->where('users.id = submitted.user_id');
		$this->db->where('admin_id', $this->session->id);
		$this->db->group_by('project_id');

		return $this->db->get()->result();
	}


	public function grade($project_id, $user_id, $assessment_id, $user_vote)
	{

		// if not assessed
		if($user_vote == -1) return TRUE;

		// get max_vote from assessments DB
		$this->db->where('assessments.id', $assessment_id);
		$q = $this->db->get('assessments', 1);
		$max_vote = $q->row('max_vote');

		// get balanced vote
		$user_vote = $user_vote * ($max_vote / 10);

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



}
?>
