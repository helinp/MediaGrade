<?php
Class Classes_model extends CI_Model
{
	public function listAllClasses()
	{
		$sql ="SELECT DISTINCT class FROM users WHERE role = 'student' ORDER BY class ASC";

		$query = $this->db->query($sql);

		if($query)
		{
			return array_column($query->result_array(), 'class');
		}
		else
		{
			return false;
		}
	}

}
?>
