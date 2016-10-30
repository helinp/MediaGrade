<?php
Class Classes_model extends CI_Model
{
	/**
	 * Returns all classes from DB
	 *
	 * @return array
	 */
	public function getAllClasses()
	{
		$query = $this->db->select('class')
					->distinct()
					->from('users')
					->where('role', 'student')
					->order_by('class', 'ASC')
					->get();

		if( ! $query) return false;
		return array_column($query->result_array(), 'class');
	}
}
?>
