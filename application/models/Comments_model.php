<?php
Class Comments_model extends CI_Model
{

	public $user_id;
	public $project_id;
	public $comment = '';

	public function getCommentsByProjectIdAndUserId($project_id, $user_id = false)
	{
		if ( ! $project_id)
			return FALSE;
		elseif ( ! $user_id)
			$user_id = $this->session->id;

		$this->db->select('comment');
		$this->db->from('comments');
		$this->db->where('project_id', $project_id);
		$this->db->where('user_id', $user_id);
		$this->db->limit(1);

		$result = $this->db->get()->row();

		if(empty($result))
			return new Comments_model;
		else
			return $result;
	}


	public function comment($project_id, $user_id, $comment = NULL)
	{
		// Do not record empty comment
		if($comment === NULL) return FALSE;

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

		// todo RETURN COMMENT ID
		return TRUE;
	}

}


?>
