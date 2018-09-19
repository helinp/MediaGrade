<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Welcome_model','',TRUE);

		$this->load->helper('text');
		$this->load->helper('school');
		$this->load->helper('graph');

		if($this->config->item('mode') === 'development')
		{
			$this->output->enable_profiler(TRUE);
		}

		if( ! empty($this->input->get('school_year')))
		{
			$this->school_year = $this->input->get('school_year');
		}
		else
		{
			$this->school_year = get_school_year();
		}

		$this->projects = $this->Projects_model->getAllActiveProjectsByClassAndSchoolYearAndOrder($this->session->class, $this->school_year, 'ASC');
		$this->data['projects'] = $this->projects;

		$this->data['school_year'] = $this->school_year;
		$this->data['school_years'] = $this->Projects_model->getSchoolYears();
	}

	public function index()
	{
		$this->load->model('Achievements_model','',TRUE);

		foreach($this->projects as $key => $project)
		{
			$this->projects[$key]->achievements = $this->Achievements_model->getAllAchievementsByProject($project->project_id, TRUE);
			$this->projects[$key]->result = $this->Results_model->getUserProjectOverallResult(FALSE, $project->project_id);
			$this->projects[$key]->submitted = $this->Submit_model->IsSubmittedByUserAndProjectId(FALSE, $project->project_id);
			$this->projects[$key]->submitted_media = $this->Submit_model->getSubmittedFilesPathsByProjectAndUser($project->project_id);
			$this->projects[$key]->graded = $this->Grade_model->isProjectGradedByProjectAndUser($project->project_id, FALSE);
		}
dump($this->session);
		$this->load->helper('deadline');
		$this->load->helper('assessment');
		$this->data['page_title'] = _('Mes projets');
		$this->load->template('student/overview_row', $this->data);
		//$this->load->template('student/overview', $this->data);
	}
}
