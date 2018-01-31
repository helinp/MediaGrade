<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Welcome_model','',TRUE);

		$this->load->helper('text');
		$this->load->helper('school');
		$this->load->helper('graph');

		if($this->input->get('school_year'))
		{
			$this->school_year = $this->input->get('school_year');
		}
		else
		{
			$this->school_year = get_school_year();
		}

		$this->projects = $this->Projects_model->getAllActiveProjectsByClassAndSchoolYear($this->session->class, $this->school_year);
		$this->data['projects'] = $this->projects;

		$this->data['school_year'] = $this->school_year;
		$this->data['school_years'] = $this->Projects_model->getSchoolYears();
	}

	/** DASHBOARD **/
	public function index()
	{
		$class = $this->session->class;
		$student_id = $this->session->id;
		$skills_groups = $this->Skills_model->getAllSkillsGroups();
		$graded_projects = array();
		/**
		 *  Get overall results for each projects
		 **/
		$not_submitted_projects = array();
		$projects_overall_results = array();
		$projects = $this->Projects_model->getAllActiveProjectsByClassAndSchoolYearAndOrder($class, $this->school_year, 'DESC');

		foreach ($projects as $project)
		{
			if ($this->Submit_model->getNFilesToSubmitFromProjectId($project->project_id) > 0
			&& ! $this->Submit_model->IsSubmittedByUserAndProjectId($student_id, $project->project_id)
			&& ! $this->Results_model->IsProjectGraded($student_id, $project->project_id) )
			{
				// Project has not been submitted, add to array
				$not_submitted_projects[] = $project;

				// Project has not been graded, put 'null' in array (required for highcharts)
				$projects_overall_results[$project->project_id] = 'null';
			}

			// Note: a project can be graded even if not submitted
			// Get overall user result
			$project_overall_result = $this->Results_model->getUserProjectOverallResult($student_id, $project->project_id);

			if($this->Grade_model->isProjectGradedByProjectAndUser($project->project_id, $student_id) && $project_overall_result->total_max > 0)
			{
				$projects_overall_results[$project->project_id] = round($project_overall_result->total_user / $project_overall_result->total_max * 100);

				// get project results (for 10 last results panel)
				$project->average = $project_overall_result;
				$graded_projects[] = $project;
			}
			else
			{
				// Project has not been graded, put 'null' in array (required for highcharts)
				$projects_overall_results[$project->project_id] = 'null';
			}
		}
		// Send to view
		$this->data['projects_overall_results'] = $projects_overall_results;
		$this->data['not_submitted'] = $not_submitted_projects;
		$skills_result_by_project = array();
		/**
		*  get highcharts skills progression
		**/
		foreach ($skills_groups as $skills_group)
		{
			foreach ($projects as $project)
			{
				$skills_group_results = $this->Results_model->getAverageByProjectIdAndStudentIdAndSkillsGroup($project->project_id, $student_id, $skills_group->name);

				if($skills_group_results->max_vote)
				{
					$skills_result_by_project[$skills_group->name][$project->project_id] = $skills_group_results->user_vote / $skills_group_results->max_vote * 100;
				}
				else
				{
					$skills_result_by_project[$skills_group->name][$project->project_id] = 'null';
				}
			}
		}

		$this->load->helper('graph');
		$this->data['graph_results'] = graph_skills_groups_results($skills_result_by_project);
		//dump($skills_result_by_project);
		/*$this->data['graph_results'] = graph_skills_groups_results($this->Results_model->getUserOverallResults($skills_groups, $projects, $student_id));*/
		$this->data['graph_projects_list'] = graph_projects($projects);

		/**
		 *  Get 10 last projects results
		 **/
		$limited_projects = array_slice(array_reverse($graded_projects), 0, 10);
		$this->data['graded'] = $limited_projects;

		/**
		*  get periods overall results
		**/
		$terms = $this->Terms_model->getAll();
		$terms_results = array();
		foreach($terms as $term)
		{
			$terms_results[$term->id]['results'] = $this->Results_model->getUserVoteAverageByTermAndSchoolYear($student_id, $term->id, $this->school_year);
			$terms_results[$term->id]['term_name'] = $term->name;
		}
		$this->data['terms_results'] = $terms_results;

		/**
		 *  Get total year result
		 **/
		$this->data['total_year_result'] = $this->Results_model->getUserVoteAverageBySchoolYear($student_id, $this->school_year);


		/**
		*  get average results for each skills groups
		**/
		$skills_results = array();
		foreach ($skills_groups as $skill)
		{
			$tmp = $this->Results_model->getUserOverallResultsBySkillGroup($skill->name, $student_id, $this->school_year);
			if(is_null($tmp) || ! is_numeric($tmp))
			{
				$tmp = 'null';
			}
			$skills_results[$skill->name] = $tmp;
		}
		$this->data['skills_results'] = $skills_results;

		/**
		*  get average results for each criteria
		**/
		// count graded occurences for each cursors
		$graded_cursors = $this->Results_model->getDetailledResults('cursor', $student_id, FALSE, $this->school_year);
		$tmp_cnt = '';
		$cnt_graded_criteria = array();

		foreach ($graded_cursors as $graded_cursor)
		{
			if($tmp_cnt !== $graded_cursor['criterion'])
			{
				$tmp_cnt = $graded_cursor['criterion'];
				$cnt_graded_criteria[$tmp_cnt] = 1;
			}
			$cnt_graded_criteria[$tmp_cnt]++;
		}
		$this->data['cnt_graded_criteria'] = $cnt_graded_criteria;
		$this->data['cursor_results'] = $graded_cursors;

		/**
		*  get results for each criteria
		**/
		$this->data['criterion_results'] = $this->Results_model->getDetailledResults('criterion', $student_id, FALSE, $this->school_year);

		/**
		*  get general infos
		**/
		$this->data['user_data'] = $this->Users_model->getUserInformations($student_id);
		$this->data['title'] = ucfirst('projets'); // Capitalize the first letter
		$this->data['best_results'] = $this->Results_model->getBestCursorResults(5);
		$this->data['worst_results'] = $this->Results_model->getWorstCursorResults(5);
		$this->data['criterion_results'] = $this->Results_model->getDetailledResults('criterion');
		$this->data['page_title'] =  _('Tableau de bord');
		$this->data['content'] = $this->Welcome_model->getWelcomeMessage();

		$this->load->template('student/dashboard', $this->data);
	}



}
