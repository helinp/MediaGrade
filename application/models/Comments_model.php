<?php
Class Comments_model extends CI_Model
{

	/**
	 * @var int
	 */
	public $user_id;

	/**
	 * @var int
	 */
	public $project_id;

	/**
	 * @var string
	 */
	public $comment = '';

	/**
	 * Returns teacher comment of an user's submitted project
	 *
	 * @param integer $project_id
	 * @param integer $user_id
	 * @return object
	 */
	public function getCommentsByProjectIdAndUserId($project_id, $user_id = false)
	{
		if ( ! $project_id)
		{
			return FALSE;
		}
		elseif ( ! $user_id)
		{
			$user_id = $this->session->id;
		}

		$this->db->select('comment');
		$this->db->from('comments');
		$this->db->where('project_id', $project_id);
		$this->db->where('user_id', $user_id);
		$this->db->limit(1);

		$result = $this->db->get()->row();

		if(empty($result)) return new Comments_model;

		return $result;
	}

	/**
	 * Saves comment in DB for a determined user submitted project
	 *
	 * @param integer $project_id
	 * @param integer $user_id
	 * @param string $comment
	 * @return boolean
	 */
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
		else
		{
			$this->db->insert('comments', $data);
		}

		return TRUE;
	}
}


?>
