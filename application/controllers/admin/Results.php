<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Results extends MY_AdminController {

	private $data;

	function __construct()
	{
		parent::__construct();

		$this->load->helper('school');
		$this->load->helper('text');
		$this->load->helper('assessment');
		$this->load->helper('round');
		$this->load->model('TableResults_model','',TRUE);


		// Get data for select in view

		$this->Courses_model->orderBy('name', 'ASC');
		$this->data['courses'] = $this->Courses_model->getAllCoursesByTeacherId($this->session->user_id);
		$this->data['terms'] = $this->Terms_model->getAll();

		// Get School Year if not set
		if($this->input->get('school_year'))
		{
			$this->school_year = $this->input->get('school_year');
		}
		else
		{
			$this->school_year = get_school_year();
		}
		$this->data['school_years'] = $this->Projects_model->getSchoolYears();

		// Submenu
		$submenu = array();
		$submenu[] = array('title' => 'Cahier de cotes', 'url' => '/admin/results');
		$submenu[] = array('title' => 'Trombinoscope', 'url' => '/admin/results/detail_by_class');
		$submenu[] = array('title' => 'Fiche élève', 'url' => '/admin/results/detail_by_student');
		$submenu[] = array('title' => 'Résultats simplifiés élève', 'url' => '/admin/results/overview');
		$this->data['submenu'] = $submenu;
	}

	public function index()
	{
		if($this->input->get('course'))
		{
			$course = $this->Courses_model->getCourse($this->input->get('course'));
		}
		else
		{
			// get first class as default value
			$course = $this->data['courses'][0];
		}

		if($this->input->get('term'))
		{
			$term = $this->input->get('term');
		}
		else
		{
			$term = FALSE;
		}

		$students_in_class = $this->Users_model->getAllStudentsSortedByClass($course->class_id);

		/*
		 *	TABLE
		 *
		 */
		// make table header
		$header = array();
		$projects = $this->Projects_model->getAllActiveProjectsByCourseAndTermAndSchoolYearAndOrder($course->id, $term, $this->school_year, 'ASC');

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

		$this->data['table_header'] = $header;
		$this->data['table_body'] = array();

		$index = 0;
		foreach ($students_in_class[$course->class_id] as $student)
		{
			$user_info = $this->Users_model->getUserInformations($student->id);

			$this->data['table_body'][$index]['user_id'] = $student->id;
			$this->data['table_body'][$index]['first_name'] = $user_info->first_name;
			$this->data['table_body'][$index]['last_name'] = $user_info->last_name;
			$this->data['table_body'][$index]['term'] = $term;
			$this->data['table_body'][$index]['average'] = $this->Results_model->getUserVoteAverageByTermAndSchoolYear($student->id, $term);
			$this->data['table_body'][$index]['deviation'] = $this->Results_model->getUserDeviationByTermAndSchoolYear($student->id, $term);

			if ( ! $projects) $this->data['table_body'][$index]['results'][0][0] = $this->Results_model;

			$index_project = 0;
			foreach($projects as $project)
			{

				// foreach skill group in project
				$skills_groups = $this->Assessment_model->getSkillsGroupByProject($project->project_id);

				foreach ($skills_groups as $i => $skills_group)
				{
					$results = $this->Results_model->getResultsBySkillsGroupAndUserIdAndProjectId($skills_group->skills_group, $student->id, $project->project_id);
					$this->data['table_body'][$index]['results'][$index_project][$i] = $results;
				}
				$index_project++;
			}
			$index++;
		}

		$this->load->helper('assessment');
		$this->data['page_title'] = _('Cahier de cotes');
		$this->data['course'] = $course;
		$this->load->template('admin/results', $this->data);
	}


	public function details($project_id, $user_id = FALSE)
	{
		// get project info
		$project = $this->Projects_model->getProjectDataByProjectId($project_id);
		$course = $project->class;

		// if one student only, create dummy array
		if ($user_id)
		{
			$user_info = $this->Users_model->getUserInformations($user_id);
			$user_info->class_name = $this->Classes_model->getClass($user_info->class)->name;
			$students_in_class[0] = $user_info;
		}
		else
		{
			// get all students
			$students_in_class = $this->Users_model->getAllStudentsSortedByClass($course)[$course];
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
		$this->data['page_title'] = _('Détail par élève');
		$this->load->template('admin/result_details', $this->data, TRUE);
	}

	public function detail_by_student($student_id = FALSE)
	{
		/**
		*  Filters management
		**/
		if(is_numeric($this->input->get('student')))
		{
			// get a cleaner URL
			redirect('/admin/results/detail_by_student/' . $this->input->get('student') . '?classe=' . $this->input->get('classe'));
		}

		$course_id = $this->input->get('classe');
		if(is_numeric($course_id))
		{
			$students = $this->Users_model->getAllStudentsSortedByClass($course_id);
		}
		else
		{
			$students[0] = $this->Users_model->getAllStudents();
		}

		if($student_id === FALSE)
		{
			$student_id = current($students)[0]->id;
		}

		$skills_groups = $this->Skills_model->getAllSkillsGroups();

		/**
		*  Get overall results for each projects
		**/
		$projects_overall_results = array();
		$skills_result_by_project = array();
		$not_submitted_projects = array();
		$graded_projects = array();
		$course_id = $this->Users_model->getUserInformations($student_id)->class;
		$projects = $this->Projects_model->getAllActiveProjectsByClassAndSchoolYear($course_id, $this->school_year);

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
			$project_overall_result->average = @round($project_overall_result->total_user / $project_overall_result->total_max * 100, 1, PHP_ROUND_HALF_DOWN);
			$project_overall_result->total_user = round($project_overall_result->total_user, 1, PHP_ROUND_HALF_DOWN);

			if($this->Grade_model->isProjectGradedByProjectAndUser($project->project_id, $student_id) && $project_overall_result->total_max > 0)
			{
				$projects_overall_results[$project->project_id] = round($project_overall_result->total_user / $project_overall_result->total_max * 100, 1, PHP_ROUND_HALF_DOWN);

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
					$skills_result_by_project[$skills_group->name][$project->project_id] = round($skills_group_results->user_vote / $skills_group_results->max_vote * 100, 1, PHP_ROUND_HALF_DOWN);
				}
				else
				{
					$skills_result_by_project[$skills_group->name][$project->project_id] = 'null';
				}
			}
		}

		$this->load->helper('graph');
		$this->data['graph_results'] = graph_skills_groups_results($skills_result_by_project);
		//dump($skills_groups);
		//dump($skills_result_by_project);
		/*$this->data['graph_results'] = graph_skills_groups_results($this->Results_model->getUserOverallResults($skills_groups, $projects, $student_id));*/
		$this->data['graph_projects_list'] = graph_projects($projects);

		/**
		*  Get 10 last projects results
		**/
		$max_results_number = 10;
		$limited_projects = array_slice(array_reverse($graded_projects), 0, $max_results_number);
		$this->data['graded'] = $limited_projects;

		/**
		*  get periods overall results
		**/
		$terms = $this->Terms_model->getAll();
		$terms_results = array();
		foreach($terms as $term)
		{
			$terms_results[$term->name] = $this->Results_model->getUserVoteAverageByTermAndSchoolYear($student_id, $term->id, $this->school_year);
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
		$this->data['page_title'] = _('Détail par élève');
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

	function detail_by_class()
	{
		$this->load->model('skills_model','',TRUE);
		$this->skills_groups = $this->skills_model->getAllSkillsGroups();
		$this->data['skills_groups'] = $this->skills_groups;

		// FILTERS
		$course_id = $this->input->get('classe');
		if($course_id && is_numeric($course_id))
		{
			$this->students_list = $this->Users_model->getAllStudentsSortedByClass($course_id)[$course_id];
		}
		else
		{
			$this->students_list = $this->Users_model->getAllStudents();
		}

		// Gets averages and achievements
		foreach ($this->students_list as $key => $student)
		{
			// GETS RESULTS FOR ADMIN
			if($this->Users_model->isAdmin())
			{
				$this->students_list[$key]->results = $this->Results_model->getUserVoteAverageByTermAndSchoolYear($student->id, FALSE, $this->school_year);
				$this->students_list[$key]->class_name = $this->Classes_model->getClass($student->class)->name;

				$this->projects_list = $this->Projects_model->getAllActiveProjectsByClassAndSchoolYear($student->class, $this->school_year);
				$this->students_list[$key]->all_results = $this->Results_model->getUserOverallResults(FALSE, $this->projects_list, $student->id, $this->school_year);

				foreach ($this->skills_groups as $skills_group)
				{
					$this->students_list[$key]->skills_groups_results[$skills_group->id] = $this->Results_model->getUserOverallResultsBySkillGroup($skills_group->name, $student->id, $this->school_year);
				}

				$this->students_list[$key]->trend = $this->_trendLine($this->students_list[$key]->all_results);
				$this->students_list[$key]->progression = $this->_progression($this->_linearProgression($this->students_list[$key]->all_results), 1);
			}
			$this->students_list[$key]->achievements = $this->Achievements_model->getAllAchievementsByStudent($student->id);
		}

		$this->data['students'] = $this->students_list;
		$this->data['page_title'] = _('Détail par classe');

		// VIEW
		$this->load->template('students_overview', $this->data);
	}


	function overview($student_id = FALSE)
	{
		/**
		*  Filters management
		**/
		if(is_numeric($this->input->get('student')))
		{
			// get a cleaner URL
			redirect('/admin/results/overview/' . $this->input->get('student') . '?classe=' . $this->input->get('classe'));
		}

		$course_id = $this->input->get('classe');
		if(is_numeric($course_id))
		{
			$students = $this->Users_model->getAllStudentsSortedByClass($course_id);
		}
		else
		{
			$students[0] = $this->Users_model->getAllStudents();
		}

		if($student_id === FALSE)
		{
			$student_id = current($students)[0]->id;
		}

		$course = $this->Users_model->getUserInformations($student_id)->class;

		$projects = $this->Projects_model->getAllActiveProjectsByClassAndSchoolYearAndOrder($course, $this->school_year, 'DESC');

		foreach($projects as $key => $project)
		{
			$projects[$key]->self_assessments = $this->Submit_model->getSelfAssessmentByProjectId($project->project_id, TRUE);
			$projects[$key]->achievements = $this->Achievements_model->getAllAchievementsByProject($project->project_id, TRUE);
			$projects[$key]->results = $this->Results_model->getResultsByProjectAndUser($project->project_id, $student_id);
			$projects[$key]->submitted = $this->Submit_model->getSubmittedFilesPathsByProjectAndUser($project->project_id, $student_id);
			$projects[$key]->graded = $this->Grade_model->isProjectGradedByProjectAndUser($project->project_id, $student_id);
			$projects[$key]->comments = @$this->data['comments'] = preg_replace("/\r\n|\r|\n/",'<br/>', $this->Comments_model->getCommentsByProjectIdAndUserId($project->project_id)->comment, $student_id);
		}

		//dump(	$this->data['projects']);
		//	$this->data['projects'] = $this->projects;
		$this->load->helper('deadline');
		$this->load->helper('assessment');
		$this->data['projects'] = $projects;
		$this->data['students'] = $students;
		$this->data['student_id'] = $student_id;
		$this->data['page_title'] = _('Remises et résultats');
		$this->load->template('admin/results_overview', $this->data);

	}

	/* ****************************
	*
	*			PRIVATE METHODS
	*
	*****************************/

	/* TODO Sould be helpers functions*/
	private function _progression($results = array(), $round = FALSE)
	{
		if(is_array($results))
		{
			$n = count($results);
		}
		else {
			$n = 0;
		}
		//	dump($n);
		if( ! $n | $n < 2 ) return 0;

		if($round)
		{
			$results[0] = round($results[0] 			* 2 / 100, 2);
			$results[$n - 1] = round($results[$n - 2] 	* 2 / 100, 2);
		}

		if($results[$n - 1] > $results[0]) return 1;
		if($results[$n - 1] < $results[0]) return -1;
		else return 0;
	}

	private function _trendLine($array_y = array())
	{
		$i = 0; $y = $x = false;
		foreach ($array_y as $array)
		{
			if ($array <> 'null')
			{
				$y[] = $array;
				$x[] = $i;
				$i++;
			}
			# code...
		}
		if( ! $x) return false;

		/**/
		$data = $y;

		$range;
		if($i > 10) $range = $i / 2;
		elseif($i >= 5) $range = 3;
		elseif($i > 1) $range = 2;
		else return 0;

		$range = (int)($range) ;
		$sum = array_sum(array_slice($data, 0, $range));

		$result = array($range - 1 => $sum / $range);

		for ($i = $range, $n = count($data); $i != $n; ++$i) {
			$result[$i] = (int) ( $result[$i - 1] + ($data[$i] - $data[$i - $range]) / $range);
		}

		/**/


		return ($result);
	}

	private function _linearProgression($array_y = array())
	{
		$i = 0; $y = $x = false;
		foreach ($array_y as $array)
		{
			if ($array <> 'null')
			{
				$y[] = $array;
				$x[] = $i;
				$i++;
			}
			# code...
		}
		if( ! $x) return false;

		$n     = count($x);     // number of items in the array
		$x_sum = array_sum($x); // sum of all X values
		$y_sum = array_sum($y); // sum of all Y values

		$xx_sum = 0;
		$xy_sum = 0;

		for($i = 0; $i < $n; $i++) {
			$xy_sum += ( $x[$i]*$y[$i] );
			$xx_sum += ( $x[$i]*$x[$i] );
		}
		// Slope
		$divider = ( ( $n * $xx_sum ) - ( $x_sum * $x_sum ) );
		$slope = ( ( $n * $xy_sum ) - ( $x_sum * $y_sum ) ) / ($divider === 0 ? 1 : $divider);

		// calculate intercept
		$intercept = ( $y_sum - ( $slope * $x_sum ) ) / $n;

		$trend = array(
			'slope'     => $slope,
			'intercept' => $intercept,
		);

		for($x = 0 ; $x <= $n ; $x++)
		{
			$graph[] = (int)($trend['intercept'] + ($x * $trend['slope']));
		}

		return ($graph);

	}
}
