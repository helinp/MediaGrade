<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends MY_AdminController {

	private $data;

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
		$this->data['school_years'] = $this->Projects_model->getSchoolYears();
	}

	public function index()
	{

	}

	public function details($student_id = FALSE)
	{
		// cleaner url
		if(is_numeric($this->input->get('student')))
		{
			redirect('/admin/student/details/' . $this->input->get('student') . '?class=' . $this->input->get('class'));
		}

		if($this->input->get('class'))
		{
			$class = $this->input->get('class');
			$students = $this->Users_model->getAllUsersByClass('student', $class);
		}
		else
		{
			$students = $this->Users_model->getAllUsersByClass('student');
			$class = current($students)[0]->class;
		}

		if($student_id === FALSE)
		{
			$student_id = current($students)[0]->id;
		}

		$skills_groups = $this->Skills_model->getAllSkillsGroups();

		/**
		 *  Get overall results for each projects
		 **/
		$not_submitted_projects = array();
		$graded_projects = array();
		$projects = $this->Projects_model->getAllActiveProjectsByClassAndSchoolYear($class, $this->school_year);

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
			$terms_results[$term] = $this->Results_model->getUserVoteAverageByTermAndSchoolYear($student_id, $term, $this->school_year);
		}
		$this->data['terms_results'] = $terms_results;

		/**
		 *  Get total year result
		 **/
		$this->data['total_year_result'] = $this->Results_model->getUserVoteAverageBySchoolYear($student_id);


		/**
		*  get average results for each skills groups
		**/
		$skills_results = array();
		foreach ($skills_groups as $skill)
		{
			$tmp = $this->Results_model->getUserOverallResultsBySkillGroup($skill->name, $student_id);
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
		$this->data['students'] = $students;
		$this->data['user_data'] = $this->Users_model->getUserInformations($student_id);
		$this->data['title'] = ucfirst('projets'); // Capitalize the first letter

		/**
		*  Call view
		**/
		if($this->input->get('modal') === 'true')
		{
			$this->load->template('admin/student_details', $this->data, TRUE);
		}
		else
		{
			$this->load->template('admin/student_details', $this->data);
		}
	}


}
