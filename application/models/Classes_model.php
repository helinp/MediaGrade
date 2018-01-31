<?php
Class Classes_model extends CI_Model
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

	/*
	 * Get class by id
	 */
	function getClass($id)
	{
		 return $this->db->get_where('classes',array('id'=>$id))->row();
	}

	function getClassIdByName($name)
	{
		 return $this->db->get_where('classes',array('name'=>$name))->row()->id;
	}

	/*
	 * Get all classes
	 */
	function getAllClasses()
	{
		 $this->db->order_by('name', 'asc');
		 return $this->db->get('classes')->result();
	}

	/*
	 * function to add new class
	 */
	function addClass($params)
	{
		 $this->db->insert('classes',$params);
		 return $this->db->insert_id();
	}

	/*
	 * function to update class
	 */
	function updateClass($id, $params)
	{
		 $this->db->where('id',$id);
		 return $this->db->update('classes',$params);
	}

	/*
	 * function to delete class
	 */
	function deleteClass($id)
	{
		 return $this->db->delete('classes',array('id'=>$id));
	}

	/*
	 * Saves student class in history
	 */
}
?>
