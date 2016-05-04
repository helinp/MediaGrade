<?php
Class Grade_model extends CI_Model
{
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

		// checks if record exists
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

	public function comment($project_id, $user_id, $comment)
	{

		$data = array(
				'user_id' => $user_id,
				'project_id' => $project_id,
				'comment' => $comment
		);

		$where = array(
			'user_id' => $user_id,
			'project_id' => $project_id
		);

		// checks if record exists
		$this->db->where($where);
		$q = $this->db->get_where('comments', $where, 1);

		// if true, update
		if ($q->num_rows() > 0)
		{
			$this->db->where($where);
			$this->db->update('comments', $data);
		}
		// else insert
		else
		{
			$this->db->insert('comments', $data);
		}

		return TRUE;
	}

}
?>
