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
	public function updateProject($data, $project_id)
	{
		$this->addProject($data, $project_id);
	}

	/**
	 * Saves project data in DB
	 * If second param FALSE, creates new entry in DB
	 *
	 * @param 	mixed[]		$data
	 * @param 	integer		$project_id = FALSE
	 * @return	void
	 */
	public function addProject($data = array(), $project_id = FALSE)
	{
		// saves NEWS self-assessments
		$self_assessment_ids = array();

		if ( ! empty($data['new_self_assessment'][0]))
		{
			foreach($data['new_self_assessment'] as $row)
			{
				array_push($self_assessment_ids, $this->addSelfAssessment($row));
			}
		}

		// adds SELECTED self-assessements
		if (isset($data['self_assessment_id']))
		{
			foreach($data['self_assessment_id'] as $row)
			{
				array_push($self_assessment_ids, $row);
			}
		}

		$project = array(
			'project_name' 			=> $this->input->post('project_name'),
			'assessment_type' 		=> $this->input->post('assessment_type'),
			'term' 					=> $this->input->post('term'),
			'class' 				=> $this->input->post('class'),
			'deadline' 				=> $this->input->post('deadline'),
			'school_year'			=> $this->current_school_year,
			'skill_ids' 			=> implode(',', $this->input->post('skill_ids')),
			'extension' 			=> $this->input->post('extension'),
			'instructions_txt' 		=> serialize(array(
										'instructions'  => $this->input->post('instructions_txt'),
										'context'		=>  $this->input->post('context_txt')
										)),
			'number_of_files'		=> $this->input->post('number_of_files'),
			'self_assessment_ids' 	=> implode(',', $self_assessment_ids),
			'is_activated' 			=> '1',
			'admin_id'				=> $this->session->id
			);

		// add instructions to array
		if ($data['instructions_pdf'])
		{
			$project['instructions_pdf'] = $data['instructions_pdf'];
		}

		if($project_id)
		{
			$this->db->where('id', $project_id);
			$this->db->update('projects', $project);
		}
		else
		{
			$this->db->insert('projects', $project);

			// get id for projects_assesments table
			$project_id = $this->db->insert_id();
		}

		// saves assessments
		$assessment_ids = array();

		foreach($data['skills_group'] as $key => $row)
		{
			if( ! empty($data['assessment_id'][$key]))
			{
				$data_assessment_id = $data['assessment_id'][$key];
			}
			else
			{
				$data_assessment_id = NULL;
			}

			$assessment = array(
				'id' =>  $data_assessment_id,
				'skills_group' => $data['skills_group'][$key],
				'criterion' => $data['criterion'][$key],
				'cursor' => $data['cursor'][$key],
				'max_vote' => $data['max_vote'][$key],
				);

			// saves in DBs
			$assessment_id = $this->addAssessment($assessment);
			$this->addProjects_Assessments($project_id, $assessment_id);
		}
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
			return TRUE;
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

		// create dir if not exists
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
	 * Add a row in table assessment
	 * array keys must be [skills_group, criterion, cursor, max_vote]
	 *
	 * @param 	array		$assessment
	 * @TODO	move in Assessment model
	 * @return	integer
	 */
	public function addAssessment($assessment = array())
	{
		$data = $assessment;

		if( ! is_null($data['id']))
		{
			$this->db->where('id', $data['id']);
			$this->db->update('assessments', $data);
			return $data['id'];
		}
		else
		{
			// else insert
			$this->db->insert('assessments', $data);
			return $this->db->insert_id();
		}
	}

	/**
	 * Add a row into projects_assessments table
	 *
	 * @param 	integer		$project_id
	 * @param 	integer		$assessment_id
	 * @return	integer
	 */
	private function addProjects_Assessments($project_id, $assessment_id)
	{
		$data = array('project_id' => $project_id, 'assessment_id' => $assessment_id);

		// checks if record exists
		$q = $this->db->get_where('projects_assessments', $data, 1);

		if( ! $q->row())
		{
			$this->db->insert('projects_assessments', $data);
			return $this->db->insert_id();
		}
		else
		{
			return $q->row('id');
		}
	}

	/**
	 * Will check projects_assessments table for orphans
	 * and delete them
	 * @todo implement method
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
