<?php
Class Classes_model extends CI_Model
{
	public function getAllClasses()
	{
		$query = $this->db->select('class')
					->distinct()
					->from('users')
					->where('role', 'student')
					->order_by('class', 'ASC')
					->get();

		if($query)
			return array_column($query->result_array(), 'class');
		else
			return false;
	}
}
?>
