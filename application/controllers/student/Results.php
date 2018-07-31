<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Results extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Welcome_model','',TRUE);

		$this->load->helper('text');
		$this->load->helper('school');
		$this->load->helper('graph');
		$this->load->helper('assessment');

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
			$this->projects[$key]->results = $this->Results_model->getResultsByProjectAndUser($project->project_id, FALSE);
			$this->projects[$key]->submitted = $this->Submit_model->getSubmittedFilesPathsByProjectAndUser($project->project_id, FALSE);
			$this->projects[$key]->graded = $this->Grade_model->isProjectGradedByProjectAndUser($project->project_id, FALSE);
			$this->projects[$key]->comments = @$this->data['comments'] = preg_replace("/\r\n|\r|\n/",'<br/>', $this->Comments_model->getCommentsByProjectIdAndUserId($project->project_id)->comment);
		}

		//dump(	$this->data['projects']);
	//	$this->data['projects'] = $this->projects;
		$this->load->helper('deadline');
		$this->load->helper('assessment');
		$this->data['page_title'] = _('Mes résultats');
		$this->load->template('student/results_overview', $this->data);
	}
}
