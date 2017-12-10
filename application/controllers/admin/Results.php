<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Results extends MY_AdminController {

	private $data;

	function __construct()
	{
		parent::__construct();

		$this->load->helper('school');

		$this->data['classes'] = $this->Classes_model->getAllClasses();
		$this->data['terms'] = $this->Terms_model->getAll();

		if($this->input->get('school_year'))
		{
			$this->school_year = $this->input->get('school_year');
		}
		else
		{
			$this->school_year = get_school_year();
		}
		$this->data['school_years'] = $this->Projects_model->getSchoolYears();
	}
	public function index($class)
	{
		$this->load->helper('assessment');

		if($this->input->get('term'))
		{
			$term = $this->input->get('term');
		}
		else
		{
			$term = FALSE;
		}
		$students_in_class = $this->Users_model->getAllUsersByClass('student', $class);

		$this->load->helper('text');
		$this->load->helper('round');

		$this->load->model('Results_model','',TRUE);
		$this->load->model('Assessment_model','',TRUE);

		// make table header
		$header = array();
		$projects = $this->Projects_model->getAllActiveProjectsByClassAndTermAndSchoolYear($class, $term, $this->school_year);

		// prepare data for body table
		foreach ($projects as $project)
		{
			$skills_group = $this->Assessment_model->getSkillsGroupByProject($project->project_id);

			$header[$project->project_id] = array(
				'project_name' => $project->project_name,
				'skills_groups' => $skills_group,
				'project_id' => $project->project_id
				);
		}

		$this->data['class'] = $class;
		$this->data['table_header'] = $header;
		// OBSOLETE $this->data['table_body'] = $this->Results_model->tableBodyClassResultsBySkillsGroup($class, $term, $this->school_year);

		$this->data['table_body'] = array();

		$index = 0;
		foreach ($students_in_class[$class] as $student)
		{
			$user_info = $this->Users_model->getUserInformations($student->id);

			$this->data['table_body'][$index]['user_id'] = $student->id;
			$this->data['table_body'][$index]['name'] = $user_info->name;
			$this->data['table_body'][$index]['last_name'] = $user_info->last_name;
			$this->data['table_body'][$index]['term'] = $term;
			$this->data['table_body'][$index]['average'] = $this->Results_model->getUserVoteAverageByTermAndSchoolYear($student->id, $term);
			$this->data['table_body'][$index]['deviation'] = $this->Results_model->getUserDeviationByTermAndSchoolYear($student->id, $term);

			if ( ! $projects) $this->data['table_body'][$index]['results'][0][0] = $this->Results_model;
			foreach($projects as $project)
			{
				$this->data['table_body'][$index]['results'][] = $this->Results_model->studentProjectResults($student->id, $project->project_id, $term);


			}
			$index++;
		}
	//dump($this->data['table_body']);
		$this->load->template('admin/results', $this->data);
	}


	public function details($project_id, $user_id = FALSE)
	{
		$this->load->model('Results_model','',TRUE);
		$this->load->model('Assessment_model','',TRUE);

		// get project info
		$project = $this->Projects_model->getProjectDataByProjectId($project_id);
		$class = $project->class;
		$first_col = $this->Assessment_model->getAssessmentsByProjectId($project_id);

		// if one student only, create dummy array
		if ($user_id)
		{
			$students_in_class[$class][0] = $this->Users_model->getUserInformations($user_id);
			//$students_in_class[$class][0]->id = $user_id;
		}
		else
		{
			// get all students
			$students_in_class = $this->Users_model->getAllUsersByClass('student', $class);
		}

		foreach($first_col as $key => $row)
		{
			foreach($students_in_class[$class] as $user)
			{
				$first_col[$key]->results[$user->id] = @$this->Results_model->getResultsByProjectAndUser($project_id, $user->id)[$key]->user_vote;
			}
		}
		$this->data['submitted'] = $this->Submit_model->getSubmittedFilesPathsByProjectAndUser($project_id, $user_id);
		$this->data['project_name'] = $project->project_name;
		$this->data['results'] = $first_col;
		$this->data['students'] = $students_in_class;

		$this->load->helper('round');
		$this->load->template('admin/result_details', $this->data, TRUE);
	}
}
