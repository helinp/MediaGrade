<?php
Class Submit_ext_model extends CI_Model
{

	function __construct()
	{
	}

	/**
	* Insert submitted project in database
	*/
	public function add($project_id, $user_id, $file_path, $file_name)
	{
		$data = array(
			'project_id' => $project_id,
			'user_id' => $user_id,
			'file_path' => $file_path,
			'file_name' => $file_name,
		);

		$where = array(
			'project_id' => $project_id,
			'user_id' => $user_id
		);

		// checks if record exists
		$q = $this->db->where($where);

		// if true, update
		if ($q->get('submitted_ext')->num_rows() > 0)
		{
			$this->db->where($where);
			$this->db->update('submitted_ext', $data);
		}
		// else insert
		else
		{
			$this->db->insert('submitted_ext', $data);
		}
	}


}
?>
