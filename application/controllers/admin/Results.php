<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Results extends MY_AdminController {

	private $data;

	function __construct()
	{
		parent::__construct();

		$this->load->helper('school');
		$this->load->helper('text');

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
		// get project info
		$project = $this->Projects_model->getProjectDataByProjectId($project_id);
		$class = $project->class;

		// if one student only, create dummy array
		if ($user_id)
		{
			$students_in_class[0] = $this->Users_model->getUserInformations($user_id);
		}
		else
		{
			// get all students
			$students_in_class = $this->Users_model->getAllUsersByClass('student', $class)[$class];
		}

		$students_assessments_results = array();
		$assessments = $this->Assessment_model->getAssessmentsByProjectId($project_id);

		// make array for table in view
		foreach($assessments as $key => $assessment)
		{
			foreach($students_in_class as $student)
			{
				$assessment_result = $this->Results_model->getStudentResultsByAssessmentIdAndStudentId($assessment->id, $student->id);

				$students_assessments_results[$key] = $assessment;
				if(isset($assessment_result->user_vote))
				{
					if($assessment_result->user_vote === -1)  // student has not been graded, keep it for future compability
					{
						$students_assessments_results[$key]->results[$student->id] = 'NE';
					}
					else
					{
						$students_assessments_results[$key]->results[$student->id] = $assessment_result->user_vote;
					}
				}
				else // student has not been graded
				{
					$students_assessments_results[$key]->results[$student->id] = '--';
				}
			}
		}

		$this->data['submitted'] = $this->Submit_model->getSubmittedFilesPathsByProjectAndUser($project_id, $user_id);
		$this->data['project_name'] = $project->project_name;
		$this->data['students_assessments_results'] = $students_assessments_results;
		$this->data['students'] = $students_in_class;

		$this->load->helper('round');
		$this->load->template('admin/result_details', $this->data, TRUE);
	}
}
