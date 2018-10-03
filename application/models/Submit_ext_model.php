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

	public function IsSubmittedByUserAndProjectId($user_id = FALSE, $project_id)
	{
		if( ! $user_id)
		{
			$user_id = $this->session->id;
		}

		$check = $this->db->get_where('submitted_ext', array('user_id' => $user_id, 'project_id' => $project_id));
		if($check->row())
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function getSubmittedFilesPathsByProjectAndUser($project_id, $user_id = FALSE)
	{
		if (!$user_id) $user_id = $this->session->id;

		$sql = "SELECT CONCAT('/assets/', file_path, file_name) as file,
		CONCAT(file_path, file_name) as thumbnail,
		RIGHT(file_name, 3) as extension
		FROM submitted_ext WHERE user_id = ? AND project_id = ?";
		$query = $this->db->query($sql, array($user_id, $project_id));
		$submitted = $query->result();

		return $submitted;
	}

	public function getSubmittedInfosByUserIdAndProjectId($user_id, $project_id)
	{
		// format data language in french TODO set a config file
		$sql = "SET lc_time_names = 'fr_FR'";
		$this->db->query($sql);

		$sql = "SELECT file_name, file_path, time as raw_time, DATE_FORMAT(`time`, '%d %M %Y à %H:%i') as `time`,  RIGHT(file_name, 3) as extension,
		CONCAT(file_path, file_name) as thumbnail
		FROM submitted_ext
		WHERE user_id = ?
		AND project_id = ?";

		$query = $this->db->query($sql, array($user_id, $project_id));
		return $query->result();
	}

}
?>
