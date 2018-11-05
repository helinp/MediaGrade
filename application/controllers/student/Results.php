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

		$submenu = array(
			array('title' => 'Par projet', 'url' => '/student/results'),
			array('title' => 'Par compétence', 'url' => '/student/results/by_skill'),
		);
		$this->data['submenu'] = $submenu;

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
			$this->projects[$key]->self_assessments = $this->Submit_model->getSelfAssessmentByProjectId($project->project_id, TRUE);
			$this->projects[$key]->achievements = $this->Achievements_model->getAllAchievementsByProjectAndSchoolYear($project->project_id, TRUE, get_school_year());
			$this->projects[$key]->results = $this->Results_model->getResultsByProjectAndUser($project->project_id, FALSE);
			$this->projects[$key]->submitted = $this->Submit_model->getSubmittedFilesPathsByProjectAndUser($project->project_id, FALSE);
			$this->projects[$key]->graded = $this->Grade_model->isProjectGradedByProjectAndUser($project->project_id, FALSE);
			$this->projects[$key]->comments = @$this->data['comments'] = preg_replace("/\r\n|\r|\n/",'<br/>', $this->Comments_model->getCommentsByProjectIdAndUserId($project->project_id)->comment);
		}

		//dump(	$this->data['projects']);
	//	$this->data['projects'] = $this->projects;

		$this->data['page_title'] = _('Mes résultats');
		$this->load->template('student/results_overview', $this->data);
	}

	public function by_skill()
	{
		$skills = $this->Skills_model->getAllSkills();
		$results_by_skill = array();

		foreach ($skills as $skill_index => $skill)
		{
			$results_by_skill[$skill_index]['results'] = $this->Results_model->getUserResultsBySkill($skill->id);
			$results_by_skill[$skill_index]['result'] = $this->Results_model->getUserResultBySkillId($skill->id);

			$results_by_skill[$skill_index]['skill'] = $skill;
		}


		$this->data['results_by_skill'] = $results_by_skill;
		$this->data['page_title'] = _('Mes résultats par compétence');
		$this->load->template('results_by_skill', $this->data);
	}
}
