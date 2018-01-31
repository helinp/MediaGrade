<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_AdminController {

	function __construct()
	{
		parent::__construct();

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
	}

	// Dashboard
	public function index()
	{
		$class = $this->input->get('classe');

		$skills_groups = $this->Skills_model->getAllSkillsGroups();
		$gauss  = array();

		foreach ($skills_groups as $skills_group)
		{
			$gauss[] = $this->Results_model->getGaussDataByClassAndSchoolYearAndAdmin($class, $this->school_year, $this->session->id, $skills_group->name);
		}

		$this->data['gauss'] =  $gauss;

		// get skills results and stats
		$skills_results_query = $this->Results_model->getSkillsResultsByClassAndSchoolYear($class, $this->school_year);
		$skills_results = array();
		$skills_stats = $this->Results_model->getSkillsStatsByClassAndSchoolYear($class, $this->school_year);

		foreach ($skills_results_query as $result)
		{
			$skill_id = $this->Skills_model->getSkillById($result->skill_id)->skill_id;
			$skills_results[$skill_id] = $result->percentage;
		}
		ksort($skills_results);

		$this->data['active_projects'] = $this->Projects_model->getAllActiveAndCurrentProjects($class);
		$this->data['assessed_skills'] = $this->_getCountAssessments($class);
		$this->data['current_school_year'] = $this->school_year;
		$this->data['disk_space'] = $this->System_model->getUsedDiskSpace();
		$this->data['last_submitted'] = $this->Submit_model->getNLastSubmitted($class, 5, $this->school_year);
		$this->data['gauss_overall'] =  $this->Results_model->getGaussDataByClassAndSchoolYearAndAdmin($class, $this->school_year, $this->session->id);
		$this->data['materials_stats'] = $this->Projects_model->getMaterialStatisticsByAdminAndClassAndShoolYear($this->session->id, $class, $this->school_year);
		$this->data['not_graded_projects'] = $this->Grade_model->listUngradedProjects($class, $this->school_year);
		$this->data['ranking_top'] = $this->Results_model->getStudentsRankingByTermAndClassAndSchoolYear('DESC', 60, 5, FALSE, $class, $this->school_year);
		$this->data['ranking_bottom'] = $this->Results_model->getStudentsRankingByTermAndClassAndSchoolYear('ASC', 60, FALSE, FALSE, $class, $this->school_year);
		$this->data['skills_groups'] = $skills_groups;
		$this->data['skills_results'] = $skills_results;
		$this->data['skills_stats'] = $skills_stats;
		$this->data['skills_usage'] = $this->Skills_model->getSkillsUsageByClass($class, FALSE, $this->school_year);
		$this->data['school_years'] = $this->Projects_model->getSchoolYears();

		$this->load->helper('form');
		$this->data['page_title'] = _('Dashboard');
		$this->load->template('admin/dashboard', $this->data);
	}

	private function _getCountAssessments($class = FALSE)
	{
		$result = array();

		if($class)
		{
			$projects = $this->Projects_model->getAllActiveProjectsByClass($class);
		}
		else
		{
			$projects = $this->Projects_model->getAllActiveProjectsByAdmin($this->session->id);
		}

		foreach($projects as $project)
		{
			$assessments = $this->Assessment_model->getAssessmentsByProjectId($project->project_id);
			foreach ($assessments as $assessment)
			{
				$skill = $this->Skills_model->getSkillById(@$assessment->skill_id);
				if( ! $skill)
				{
					break;
				}

				$result[$skill->id]['id'] = $skill->skill_id;
				$result[$skill->id]['name'] = $skill->skill;
				$result[$skill->id]['skills_group'] = $skill->skills_group;

				if( ! isset($result[$skill->id]['count']))
				{
					$result[$skill->id]['count'] = 1;
				}
				else
				{
					$result[$skill->id]['count']++;
				}
			}
		}
		ksort($result);
		return($result);
	}
}
