<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends MY_AdminController {

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

		$submenu = array(
			array('title' => 'Nouveau', 'url' => '/admin/project/management/new'),
			array('title' => 'Vue d\'ensemble', 'url' => '/admin/projects'),
			array('title' => 'Statistiques', 'url' => '/admin/project/statistics')
		);
		$this->data['submenu'] = $submenu;
	}

	/**
	 *
	 *		PROJECTS LIST PAGE
	 *
	 */
	public function index($project_id = FALSE)
	{
		// Models
		$this->load->model('ProjectsManager_model','',TRUE);
		$this->load->model('Terms_model','',TRUE);
		$this->load->model('Achievements_model','',TRUE);

		// TODO : control for empty fields

		// Get data
		$admin_projects = $this->Projects_model->getAllActiveProjectsByAdmin(FALSE, $this->school_year);

		$current_classe = ''; // for perf
		$n_students = 0;
		foreach ($admin_projects as $row)
		{
			$this->data['achievements_by_project'][$row->project_id] = $this->Achievements_model->getAllAchievementsByProject($row->project_id);

			if($current_classe !== $row->class)
			{
				$n_students = count($this->Users_model->getAllUsersByClass('student', $row->class)[$row->class]);
				$current_classe = $row->class;
			}

			$n_files_to_submit = $row->number_of_files;
			$n_submitted =  $this->Submit_model->getNSubmittedByProjectId($row->project_id) / $n_files_to_submit;
			$n_graded = count($this->Grade_model->listUngradedProjectsByProjectId($row->project_id));

			$this->data['n_students'][$row->project_id] = $n_students;
			$this->data['n_submitted'][$row->project_id] = $n_submitted;
			$this->data['n_graded'][$row->project_id] = $n_submitted - $n_graded;

			$results = $this->Results_model->getStudentsAverageByProjectId($row->project_id);
			$n_success = $n_pass = $n_fail = 0;
			foreach ($results as $result)
			{
				if($result->max_vote)
				{
					$percentage = $result->user_vote / $result->max_vote * 100;

					if($percentage > 79) $n_success++;
					elseif($percentage > 49) $n_pass++;
					elseif($percentage < 50) $n_fail++;
				}
			}
			$this->data['success'][$row->project_id] = array('success' => $n_success, 'pass' => $n_pass, 'fail' => $n_fail);
		}

		$this->data['projects'] = $admin_projects;

		// helpers
		$this->load->helper('text');
		$this->load->helper('deadline');
		$this->load->helper('assessment');

		// template
		$this->data['page_title'] = _('Vue d\'ensemble');
		$this->load->template('admin/projects', $this->data);
	}
}
