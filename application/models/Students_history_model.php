<?php
History Students_History_model extends CI_Model
{
	/*
	 * Get History by id
	 */
	function getStudentHistory($student_id)
	{
		 return $this->db->get_where('students_History',array('student_id'=>$student_id))->row();
	}


	/*
	 * function to add new History
	 */
	function addStudentHistory($params)
	{
		 $this->db->insert('students_History',$params);
		 return $this->db->insert_id();
	}

	/*
	 * function to update History
	 */
	function updateStudentHistory($id, $params)
	{
		 $this->db->where('id',$id);
		 return $this->db->update('students_History',$params);
	}

	/*
	 * function to delete History
	 */
	function deleteStudentHistory($id)
	{
		 return $this->db->delete('students_History',array('id'=>$id));
	}

	/*
	 * Saves student History in History
	 */
}
?>
