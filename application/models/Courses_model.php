<?php
Class Courses_model extends CI_Model
{

	/**
	 * @var int
	 */
	public $id;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $description;

	/**
	* @var int
	*/
	public $teacher_id;


	public function orderBy($column, $asc = 'ASC')
	{
		$this->db->order_by($column, $asc);
	}
	
	/*
	 * Get Course by id
	 */
	function getCourse($id)
	{
		 return $this->db->get_where('courses', array('id'=>$id))->row();
	}
	function getClassIdFromCourseId($id)
	{
		 return $this->db->get_where('courses', array('id'=>$id))->row('class_id');
	}


	function getAllCoursesByTeacherId($teacher_id)
	{
		 return $this->db->get_where('courses',array('teacher_id'=>$teacher_id))->result();
	}

	/*
	 * Get all courses
	 */
	function getAllCourses()
	{
		 $this->db->select('courses.*, classes.description AS class_description, classes.name AS class_name, users.last_name AS teacher_last_name');
		 $this->db->order_by('class_id, teacher_id, courses.name', 'asc');
		 $this->db->join('classes', 'classes.id = class_id');
		 $this->db->join('users', 'users.id = teacher_id');
		 return $this->db->get('courses')->result();
	}

	/*
	 * function to add new Course
	 */
	function addCourse($params)
	{
		 $this->db->insert('courses',$params);
		 return $this->db->insert_id();
	}

	/*
	 * function to update Course
	 */
	function updateCourse($id, $params)
	{
		 $this->db->where('id',$id);
		 return $this->db->update('courses',$params);
	}

	/*
	 * function to delete Course
	 */
	function deleteCourse($id)
	{
		 return $this->db->delete('courses',array('id'=>$id));
	}

	/*
	 * Saves student Course in history
	 */
}
?>
