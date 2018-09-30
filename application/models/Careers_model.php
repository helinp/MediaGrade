<?php
Class Careers_model extends CI_Model
{

	public function orderBy($column, $asc = 'ASC')
	{
		$this->db->order_by($column, $asc);
	}

	/**
	* //
	* @return	boolean
	*/
	public function firstBuildt()
	{



	}

	public function stats() // not used
	{
		$this->db->select('COUNT(user_id) AS n_students, class_name, school_year');
		$this->db->distinct();
		$this->db->group_by('class_name');
		$this->db->group_by('school_year');

		$this->db->order_by('class_name');
		$this->db->order_by('school_year', 'DESC');
		$query = $this->db->get('careers');
		return $query->result();
		// réussites?
	}
	public function statsBySchoolYearAndClassId($school_year, $class_id)
	{
		$this->db->select('COUNT(user_id) AS n_students');
		$this->db->where('class_id', $class_id);
		$this->db->where('school_year', $school_year);
		$this->db->distinct();
		$this->db->group_by('class_name');
		$this->db->group_by('school_year');

		$query = $this->db->get('careers');
		return ($query->row_array() ? $query->row_array() : array('n_students' => "'-'"));
		// réussites?
	}

	public function add($params)
	{
		$this->db->insert('careers', $params);
		return $this->db->insert_id();
	}

	public function delete()
	{

	}

	public function update()
	{

	}

	public function getClassOnSchoolYear($school_year)
	{

	}

	public function getClass($class_id)
	{

	}


}
?>
