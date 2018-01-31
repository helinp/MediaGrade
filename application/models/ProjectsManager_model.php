<?php
Class ProjectsManager_model extends CI_Model
{

	function __construct ()
	{
		$this->load->helper('school');
		$this->current_school_year = get_school_year();
	}

	/**
	* Dummy function for future code improvement
	*
	* @param 	mixed[]		$data
	* @param 	integer		$project_id
	* @return	void
	*/
	public function updateProject($project = array())
	{
		$this->db->where('id', $project['id']);
		$this->db->update('projects', $project);

		return $project['id'];
	}

	/**
	* Saves project data in DB
	* If second param FALSE, creates new entry in DB
	*
	* @param 	mixed[]		$data
	* @param 	integer		$project_id = FALSE
	* @return	void
	*/
	public function addProject($project = array())
	{
		unset($project['id']);
		$this->db->insert('projects', $project);

		// get id for projects_assessments table
		$project_id = $this->db->insert_id();

		return $project_id;

	}

	/**
	* Upload PDF instruction file
	*
	* @param 	array		$config 	do_upload CI config
	* @param 	string		$field_name form field name
	* @return	boolean|show_error()
	*/
	public function uploadPDF($config, $field_name)
	{
		$this->upload->initialize($config);

		if ( ! $this->upload->do_upload($field_name))
		{
			$error = array('error' => $this->upload->display_errors());
			show_error($error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			return true;
		}
	}

	/**
	* Creates do_upload CI config for PDF instructions file
	*
	* @param 	string		$class
	* @param 	string		$term
	* @param 	string		$project_name
	* @return	array
	*/
	public function getUploadPDFConfig($class, $term, $project_name)
	{
		$this->load->helper('school');
		$this->load->helper('format');

		$file_name = $class . '_' . $term . '_' . $project_name;
		$file_path = 'uploads/' . get_school_year() . '/' . strtoupper($class) . '/instructions/';

		// create dir if no exists
		if (!is_dir('assets/' . $file_path)) mkdir('assets/' . $file_path, 0755, TRUE);

		$config['file_name']            = sanitize_name($file_name);
		$config['overwrite']            = TRUE;
		$config['file_ext_tolower']     = TRUE;
		$config['upload_path']          = './assets/' . $file_path;
		$config['file_db_path']         = $file_path;
		$config['allowed_types']        = 'pdf';

		return($config);
	}



	/**
	* Will check projects_assessments table for orphans
	* and delete them
	* @todo write method
	* @return boolean
	*/
	public function cleanProjects_assessmentsTable()
	{
		// TODO
	}

	/**
	* Switch project state (activated / disactivates)
	* @return void
	*/
	public function switchProjectState($project_id)
	{
		$this->db->where('id', $project_id);
		$this->db->set('is_activated', 'NOT is_activated', FALSE);
		$this->db->update('projects');
	}

	/**
	* Deletes a project
	* @return void
	*/
	public function deleteProject($project_id)
	{
		$this->db->delete('projects', array('id' => $project_id));
	}

	/**
	* Add or updates self_assessment row in DB
	* @return integer
	*/
	public function addSelfAssessment($self_assessment)
	{
		$data = array(
			'question' => $self_assessment
		);

		$where = array(
			'question' => $self_assessment
		);

		// checks if record exists
		$q = $this->db->get_where('self_assessments', $where, 1);

		// if true, return id
		if ($q->num_rows() > 0)
		{
			return $q->row('id');
		}
		else
		{
			$this->db->insert('self_assessments', $data);
		}
		return $this->db->insert_id();
	}
}
?>
