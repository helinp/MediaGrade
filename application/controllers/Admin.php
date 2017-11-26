<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	private $data;
// test auto-updater
	function __construct()
	{
		parent::__construct();
		$this->load->model('Users_model','',TRUE);
		$this->Users_model->loginCheck();
		$this->Users_model->adminCheck();

		$this->load->model('Email_model','',TRUE);
		$this->load->model('Submit_model','',TRUE);
		$this->load->model('Assessment_model','',TRUE);
		$this->load->model('Projects_model','',TRUE);
		$this->load->model('Submit_model','',TRUE);
		$this->load->model('System_model','',TRUE);
		$this->load->model('Skills_model','',TRUE);
		$this->load->model('Terms_model','',TRUE);
		$this->load->model('Classes_model','',TRUE);
		$this->load->model('Results_model','',TRUE);
		$this->load->model('Grade_model','',TRUE);
		$this->load->helper('school');

		if($this->config->item('mode') === 'development')
		{
			$this->output->enable_profiler(TRUE);
		}

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
//		dump(

		redirect('admin/dashboard?school_year=' . get_school_year());
	}


	public function student_details()
	{
		if($this->input->get())
		{
			$student_id = $this->input->get('student');
			$class = $this->input->get('class');
			$students = $this->Users_model->getAllUsersByClass('student', $class);
		}
		else
		{
			$students = $this->Users_model->getAllUsersByClass('student');
			$student_id = current($students)[0]->id;
			$class = current($students)[0]->class;;
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

	// Dashboard
	public function dashboard()
	{
		$school_year = $class = FALSE;
		if($this->input->get('classe')) $class = $this->input->get('classe');
		if($this->input->get('school_year')) $school_year = $this->input->get('school_year');

		$this->load->model('Results_model','',TRUE);
		$this->load->model('Grade_model','',TRUE);
		$this->load->model('Skills_model','',TRUE);

		$skills_groups = $this->Skills_model->getAllSkillsGroups();
		$gauss  = array();

		foreach ($skills_groups as $skills_group)
		{
			$gauss[] = $this->Results_model->getGaussDataByClassAndSchoolYearAndAdmin($class, $school_year, $this->session->id, $skills_group->name);
		}

		$this->data['gauss'] =  $gauss;

		$this->data['ranking_top'] = $this->Results_model->getStudentsRankingByTermAndClassAndSchoolYear('DESC', 60, 5, FALSE, $class, $school_year);
		$this->data['ranking_bottom'] = $this->Results_model->getStudentsRankingByTermAndClassAndSchoolYear('ASC', 60, FALSE, FALSE, $class, $school_year);
		$this->data['materials_stats'] = $this->Projects_model->getMaterialStatisticsByAdminAndClassAndShoolYear($this->session->id, $class, $school_year);
		$this->data['gauss_overall'] =  $this->Results_model->getGaussDataByClassAndSchoolYearAndAdmin($class, $school_year, $this->session->id);
		$this->data['skills_usage'] = $this->Skills_model->getSkillsUsageByClass($class, FALSE, $school_year);
		$this->data['last_submitted'] = $this->Submit_model->getNLastSubmitted($class, 5, $school_year);
		$this->data['active_projects'] = $this->Projects_model->getAllActiveAndCurrentProjects($class);
		$this->data['not_graded_projects'] = $this->Grade_model->listUngradedProjects($class, $school_year);
		$this->data['disk_space'] = $this->System_model->getUsedDiskSpace();
		$this->data['assessed_skills'] = $this->_getCountAssessments($class);
		$this->data['skills_groups'] = $skills_groups;
		$this->load->helper('form');
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

	public function export($type)
	{
		if($type === "projects_assessments")
		{
			if( ! empty($this->input->get('term')))
			{
				$term = $this->input->get('term');
			}
			else
			{
				$term = FALSE;
			}
			if($this->input->get('school_year')) $school_year = $this->input->get('school_year');

			$this->data['projects'] = $this->Projects_model->getAllActiveProjectsByTermAndSchoolYear($term, $school_year);
			$this->load->template('admin/export_assessments', $this->data);
		}
		elseif($type === "students_report")
		{
			$this->data['students_list'] = $this->Users_model->getAllUsersByClass();
			$this->load->template('admin/export_student_report', $this->data);
		}
		elseif($type === "lessons")
		{
			if( ! empty($this->input->get('class')))
			{
				$class = $this->input->get('class');
			}
			else
			{
				$class = FALSE;
			}
			$this->data['projects'] = $this->Projects_model->getAllActiveProjectsByClassAndSchoolYear($class, $this->school_year);
			$this->load->template('admin/export_lessons', $this->data);
		}
	}


	public function result_details($project_id, $user_id = FALSE)
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


	public function results($class)
	{
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

	/* TODO Should be 2 distinct methods (grade & grade_list)*/
	public function grade($class = FALSE, $project_id = FALSE, $user_id = FALSE)
	{
		$this->load->model('Comments_model','',TRUE);

		// POST ACTION
		if($this->input->post() && $this->Projects_model->isProjectIdFromThisSchoolYear($this->input->post('project_id')))
		{
			$this->load->model('Grade_model','',TRUE);
			$user_id = $this->input->post('user_id');

			// save grades to DB
			$i = 0;
			foreach ($this->input->post('assessments_id') as $assessment_id)
			{
				$user_vote = $this->input->post("user_vote[$i]");

				if($user_vote == -1 && $this->Grade_model->isAssessmentGraded($assessment_id, $user_id))
				{
					$this->Grade_model->removeVote($assessment_id, $user_id);
				}
				elseif($user_vote <> -1)
				{
					$this->Grade_model->grade(	$this->input->post('project_id'),
												$user_id,
												$assessment_id,
												$user_vote
										 	 );
				}
				$i++;
			}

			// saves comment to DB
			$this->Comments_model->comment($this->input->post('project_id'),
				$this->input->post('user_id'),
				$this->input->post('comment')
				);

			if($this->input->post('origin'))
			{
				redirect($this->input->post('origin'));
			}
			else
			{
				redirect("/admin/grade/");
			}
		}


		// Load Models
		$this->load->model('Users_model','',TRUE);
		$this->load->model('Results_model','',TRUE);
		$this->load->model('Submit_model','',TRUE);

		// catch gets for grading_list table
		if( ! $class = $this->input->get('classe')) $class = NULL;
		if( ! $term = $this->input->get('term')) $term = NULL;

		$this->data['class_users'] = $this->Users_model->getAllUsersByClass('student', $class);

		// TODO mhash_keygen_s2k table CLASS -> USER_ID -> PROJECTS & USER
		$table = '';
		foreach($this->data['class_users'] as $class => $students_in_class)
		{
			foreach($students_in_class as $user)
			{
				$table[$class][$user->id]['user'] = $user;

				$projects = $this->Projects_model->getAllActiveProjectsByClassAndTermAndSchoolYear($class, $term, get_school_year());

				//workaround
				//$projects = array_reverse($projects);

				foreach($projects as $key => $project)
				{
					$projects[$key]->is_graded = $this->Results_model->IsProjectGraded($user->id, $project->project_id);
					$projects[$key]->is_submitted = $this->Submit_model->IsSubmittedByUserAndProjectId($user->id, $project->project_id);
				}
				$table[$class][$user->id]['projects'] = $projects;
			}
		}
		$this->data['grade_table'] = $table;


		/**
		 *  Routing
		 */
		// to grade page
		if($class && $project_id && $user_id)
		{
			// gather informations
			$this->data['user'] = $this->Users_model->getUserInformations($user_id);
			$this->data['submitted'] = $this->Submit_model->getSubmittedInfosByUserIdAndProjectId($user_id, $project_id);
			$this->data['self_assessments'] = $this->Submit_model->getSelfAssessmentByProjectId($project_id, TRUE, $user_id);
			$this->data['comment'] = preg_replace('<br/>', "/\n", $this->Comments_model->getCommentsByProjectIdAndUserId($project_id, $user_id)->comment);
			$this->data['assessment_table'] = $this->Results_model->getResultsTable($user_id, $project_id);
			$this->data['project'] = $this->Projects_model->getProjectDataByProjectId($project_id);

			// GET
			$this->load->template('admin/grade', $this->data, TRUE);
		}
		// To grade_list
		else
		{
			$this->load->template('admin/grade_list', $this->data);
		}
	}

	/**
	 *
	 *		PROJECTS LIST PAGE
	 *
	 */
	public function projects($project_id = FALSE)
	{
		// Models
		$this->load->model('Assessment_model','',TRUE);
		$this->load->model('ProjectsManager_model','',TRUE);
		$this->load->model('Terms_model','',TRUE);
		$this->load->model('Achievements_model','',TRUE);
		$this->load->model('Users_model','',TRUE);

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
		$this->load->template('admin/projects', $this->data);
	}


	public function instructions($project_id)
	{
		$project = $this->Projects_model->getInstructionsFromProjectId($project_id);

		$this->data['instructions_pdf'] = $project->pdf;
		$this->data['instructions_txt'] = $project->txt;

		$this->load->helper('pdf');
		$this->load->helper('url');

		$this->load->template('admin/instructions', $this->data, TRUE);
	}


	/**
	 *
	 *		PROJECTS MANAGEMENT PAGE
	 *
	 */
	public function project_management($project_id = FALSE)
	{
		// Models
		$this->load->model('Assessment_model','',TRUE);
		$this->load->model('ProjectsManager_model','',TRUE);
		$this->load->model('Terms_model','',TRUE);
		$this->load->model('Comments_model','',TRUE);
        $this->load->model('FilesFormat_model','',TRUE);
		$this->load->model('Achievements_model','',TRUE);
		$this->load->model('Skills_model','',TRUE);

		// TODO : control for empty fields

		// GET
		if( ! $this->input->post())
		{
			// GET data from argument (to get project info)
			if($project_id)
			{
				$this->data['curr_project'] = $this->Projects_model->getProjectDataByProjectId($project_id);
				$this->data['active_skills'] = $this->Skills_model->getAllSkillsByProjects($project_id, TRUE);
				$this->data['assessment_table'] = $this->Assessment_model->getAssessmentsByProjectId($project_id, TRUE);
				$this->data['active_self_assessments'] = $this->Assessment_model->getSelfAssessmentIdsByProject($project_id);
			}
			else
			{
				// user wants to create a new empty project
				$this->data['assessment_table'] = array(new Assessment_model);
			}

			// Get data
			$this->data['achievements'] = $this->Achievements_model->getAllAchievements();
			$this->data['file_formats'] = $this->FilesFormat_model->getAllDistinctFormats();
			$this->data['projects'] = $this->Projects_model->getAllActiveProjectsByAdmin(FALSE);
			$this->data['skills'] = $this->Skills_model->getAllSkills();
			$this->data['self_assessments'] = $this->Assessment_model->getAllSelfAssessments();
			$this->data['skills_groups'] = $this->Skills_model->getAllSkillsGroups();

			$this->load->helper('text');
			$this->load->helper('deadline');
			$this->load->template('admin/project_management', $this->data, TRUE);
		}
		else // POST ACTION
		{
			if($this->input->post('disactivate_project'))
			{
				$this->ProjectsManager_model->switchProjectState($this->input->post('disactivate_project'));
			}
			elseif($this->input->post('delete_project'))
			{
				$this->ProjectsManager_model->deleteProject($this->input->post('delete_project'));
				redirect('/admin/projects');
			}
			// ADD or UPDATE PROJECT
			elseif($this->input->post('save_project') || $this->input->post('update_project'))
			{

				/**
				 * HYDRATE SELF ASSESSEMENTS
				 */

				// saves NEWS self-assessments
				$self_assessment_ids = array();

				if ($this->input->post('new_self_assessment')[0])
				{
					foreach($this->input->post('new_self_assessment') as $row)
					{
						array_push($self_assessment_ids, $this->Assessment_model->addSelfAssessment($row));
					}
				}
				// adds SELECTED self-assessements
				if ($this->input->post('self_assessment_id'))
				{
					foreach($this->input->post('self_assessment_id') as $row)
					{
						array_push($self_assessment_ids, $row);
					}
				}

				/**
				 * HYDRATE PROJECT
				 */
				$project = array(
					'id' 					=> $this->input->post('project_id'),
					'project_name' 			=> $this->input->post('project_name'),
					'assessment_type' 		=> $this->input->post('assessment_type'),
					'term' 					=> $this->input->post('term'),
					'class' 				=> $this->input->post('class'),
					'deadline' 				=> $this->input->post('deadline'),
					'start_date'			=> $this->input->post('start_date'),
					'school_year'			=> get_school_year(),
					'skill_ids' 			=> implode(',', $this->input->post('seen_skill_ids')),
					'material'				=> $this->input->post('material'),
					'extension' 			=> $this->input->post('extension'),
					'instructions_txt' 		=> serialize(array(
												'instructions'  => $this->input->post('instructions_txt'),
												'context'		=>  $this->input->post('context_txt')
												)),
					'number_of_files'		=> $this->input->post('number_of_files'),
					'self_assessment_ids' 	=> implode(',', $self_assessment_ids),
					'is_activated' 			=> '1',
					'admin_id'				=> $this->session->id
					);

				/**
				 * SAVE PROJECT
				 */
				if($this->input->post('save_project'))
				{
					$project_id = $this->ProjectsManager_model->addProject($project);
				}
				elseif ($this->input->post('update_project'))
				{
					$project_id = $this->ProjectsManager_model->updateProject($project);
				}

				/**
				 * SAVE ASSESSMENTS
				 */
				if($this->config->item('assessment_mode') === 'skills_group')
				{
					$skills = $this->input->post('skills_groups');
				}
				else
				{
					$skills = $this->input->post('skill_ids');
				}

				foreach($skills as $key => $skill_id)
				{
					if( ! $this->config->item('assessment_mode') === 'skills_group')
					{
						$_POST['skills_groups'][$key] = $this->Skills_model->getSkillGroupsFromSkillId($skill_id);
					}

					$assessment = array(
						'id' => $this->input->post('assessment_id')[$key],
						'skills_group' => $this->input->post('skills_groups')[$key],
						'skill_id' => $this->input->post('skill_ids')[$key],
						'criterion' => $this->input->post('criterion')[$key],
						'cursor' => $this->input->post('cursor')[$key],
						'max_vote' => $this->input->post('max_vote')[$key],
						'achievement_id' => $this->input->post('achievement_id')[$key],
						);
					if($this->input->post('assessment_id')[$key])
					{
						$assessment_id = $this->Assessment_model->updateAssessment($assessment);
					}
					else
					{
						$assessment_id = $this->Assessment_model->addAssessment($assessment);
						$this->Assessment_model->addProjects_Assessments($project_id, $assessment_id);
					}
				}

	            // Save & upload (or not) PDF instructions
				if(@$_FILES['instructions_pdf']['size'] > 0)
					$post['instructions_pdf'] = $this->uploadPDF($post);
				else
					$post['instructions_pdf'] = FALSE;
				redirect('/admin/projects?school_year=' . get_school_year());
			}
		}
	}


	private function uploadPDF($data = array())
	{

		$class = $data['class'];
		$term = $data['term'];
		$project_name = $data['project_name'];

		$config = $this->ProjectsManager_model->getUploadPDFConfig($class, $term, $project_name);
		$error = $this->ProjectsManager_model->uploadPDF($config, 'instructions_pdf');

		if (isset($error['error']))
		{
			show_error($error['error']);
		}
		else
		{
			return $config['file_db_path'] . $config['file_name'] . '.pdf';
		}
	}


	public function skills($action = FALSE)
	{
		// POST
		if($action === 'add_skill')  $this->Skills_model->addSkill($this->input->post('skill_id'), $this->input->post('skill'), $this->input->post('skills_group'));
		elseif ($action === 'del_skill') $this->Skills_model->deleteSkill($this->input->post('skill_id'));

		if($action) redirect('/admin/skills');

		// GET
		$this->load->model('Assessment_model','',TRUE);
		$this->data['skills'] = $this->Skills_model->getAllSkills();
		$this->data['skills_groups'] = $this->Skills_model->getAllSkillsGroups();
		$this->data['skills_groups_array'] = $this->Skills_model->getAllSkillsGroupsArray();
		$this->load->template('admin/skills', $this->data);
	}

	public function skills_groups($action = FALSE)
	{
		// POST
		if($action === 'add_skills_group')  $this->Skills_model->addSkillsGroup($this->input->post('skills_group'));
		elseif ($action === 'del_skills_group') $this->Skills_model->deleteSkillsGroup($this->input->post('skills_group'));
		if($action) redirect('/admin/skills_groups');

		// GET
		$this->data['skills_groups'] = $this->Skills_model->getAllSkillsGroups();
		$this->load->template('admin/skills_groups', $this->data);
	}

	public function terms($action = FALSE)
	{
		$this->load->model('Terms_model','',TRUE);

		// POST
		if($action === 'add_term')  $this->Terms_model->add($this->input->post('term'));
		elseif ($action === 'del_term') $this->Terms_model->delete($this->input->post('term'));
		if($action) redirect('/admin/terms');

		// GET
		$this->load->template('admin/terms', $this->data);
	}

	public function users($action = FALSE)
	{
		$this->load->model('UsersManager_model','',TRUE);

		if($action)
		{
			$data = array(
				'id' 		=> $this->input->post('id'),
				'username' 	=> $this->input->post('username'),
				'name' 		=> $this->input->post('name'),
				'last_name' => $this->input->post('last_name'),
				'class' 	=> $this->input->post('class'),
				'email' 	=> $this->input->post('email'),
				'role' 		=> $this->input->post('role'),
				'password' 	=> $this->input->post('password')
				);

			switch($action)
			{
				case 'add_user':
					$this->UsersManager_model->addUser($data);
					break;

				case 'update_user':
					$this->UsersManager_model->updateUser($data);
					break;

				case 'delete_user':
					$this->UsersManager_model->delUser($data->id);
					break;
			}
		}

		// GET
		$this->data['admins'] = $this->Users_model->getAllUsersByClass('admin');
		$this->data['users'] = $this->Users_model->getAllUsersByClass();

		$this->load->template('admin/users', $this->data);
	}

	public function achievements($action = FALSE)
	{
		$this->load->model('Achievements_model','',TRUE);

		if($action)
		{
			$data = array(
				'id' 			=> $this->input->post('id'),
				'name' 			=> $this->input->post('name'),
				'description' 	=> $this->input->post('description'),
				'icon' 			=> $this->input->post('icon'),
				'star'			=> $this->input->post('star')
				);

			switch($action)
			{
				case 'add':
					$this->Achievements_model->add($data);
					break;

				case 'update':
					$this->Achievements_model->update($data);
					break;

				case 'delete':
					$this->Achievements_model->delete($data['id']);
					break;

				case 'reward':
					$this->_achievement_reward();
					break;
			}
		}

		// GET
		$this->data['achievements'] = $this->Achievements_model->getAllAchievements();
		$this->load->template('admin/achievements', $this->data);
	}

	/**
	*** TODO NEEDS SEVERE OPTIMIZATION
	**/
	private function _achievement_reward()
	{
		// get all projects with assessment
		$projects_achievements = $this->Achievements_model->getAllAchievementsByProject(FALSE, $group = FALSE);
		$eligible_users = array();
		$results = array();

		// $results[USER_ID][ACHIEVEMENT_ID]
		foreach ($projects_achievements as $achievement)
		{
			$tmp_results = $this->Results_model->getResultsByAssessmentId(FALSE, FALSE, $achievement->assessment_id);

			$i = 0;
			foreach ($tmp_results as $row)
			{
				$results[$row->user_id][$achievement->achievement_id][$row->assessment_type][] = array(	'user_id' => $row->user_id,
																				'achievement_id' => $achievement->achievement_id,
																				'assessment_id' => $achievement->assessment_id,
																				'project_id' => $row->project_id,
																				'max_vote' => $row->max_vote,
																				'user_vote' => $row->user_vote,
																				'assessment_type' => $row->assessment_type,
																				'submitted_time' => @$this->Submit_model->getSubmittedInfosByUserIdAndProjectId($row->user_id, $row->project_id)[0]->raw_time,
																			);
			}
		}

		foreach($results as $res_key => $achievements)
		{
			foreach ($achievements as $ach_key => $achievement)
			{
				foreach ($achievement as $typ_key => $type)
				{
					/**
					** Should be ordered by submission time TODO
					** 	this is a workaround, should be improved ASAP
					**/
					$this->load->helper('functions');
					$results[$res_key][$ach_key][$typ_key] = array_sort($type, 'submitted_time', 'SORT_DESC');
					/**
					** end of workaround
					**/

					$total_max = 0;
					$total_user = 0;
					$count = array();
					$c = 0;

					foreach ($results[$res_key][$ach_key][$typ_key] as $result)
					{
						//dump($result);
						$total_max += $result['max_vote'];
						$total_user += $result['user_vote'];

						$count[$result['project_id']] = TRUE;
					}
					$count = count($count);
					$percentage = @($total_user / $total_max * 100);
					$eligible = $this->Achievements_model->isEligible($percentage, $count, $typ_key);

					/* for dump debug only
					$results[$res_key][$ach_key][$typ_key] = array(
																	'number' 		=> $count,
																	'total_max'		=> $total_max,
																	'total_user'	=> $total_user,
																	'percentage'	=> $percentage,
																	'eligible'		=> $eligible
																	);
					*/

					if($eligible)
					{
						$eligible_users[] = array(
																	'user_id' 			=> $res_key,
																	'achievement_id'	=> $ach_key,
																	);
					}
				}
			}
		}

		foreach ($eligible_users as $u) {
			// finally, awards students
			$this->Achievements_model->award($u['user_id'], $u['achievement_id']);
		}
	}

	public function project_stats($project_id = FALSE)
	{
		if($classe = $this->input->get('classe'))
		{
			$projects = $this->Projects_model->getAllActiveProjectsByClassAndSchoolYear($classe, $this->school_year);
		}
		else
		{
			$projects = $this->Projects_model->getAllActiveProjectsBySchoolYear($this->school_year);
		}

		$this->data['projects'] = $projects;

		if($project_id = $this->input->get('project'))
		{
			$project = $this->Projects_model->getProjectDataByProjectId($project_id);
			// Pourcentage de réussites
			$results = $this->Results_model->getStudentsAverageByProjectId($project->id);
			$n_students = $n_success = $n_pass = $n_fail = 0;
			foreach ($results as $result)
			{
				$percentage = $result->user_vote / $result->max_vote * 100;

				if($percentage > 79) $n_success++;
				elseif($percentage > 49) $n_pass++;
				elseif($percentage < 50) $n_fail++;
				$n_students++;
			}
			$this->data['success'] = array('success' => $n_success, 'pass' => $n_pass, 'fail' => $n_fail);

			// results by skills getAllSkillsGroups
			$skills_groups = $this->Skills_model->getAllSkillsGroups();
			$results = NULL;
			foreach ($skills_groups as $skills_group)
			{
				// get only array with resutls
				$check =  $this->Results_model->getSkillsGroupsAverageByProjectIdAndSkillGroup($project->id, $skills_group->name);
				if($check) $results[] = $check;
			}
			$this->data['results_skills'] = $results;

			// results by criterion getAllSkillsGroups
			$criteria = $this->Assessment_model->getCriteriaFromProjectId($project->id);
			$results = NULL;
			foreach ($criteria as $criterion)
			{
				// get only array with resutls
				$check =  $this->Results_model->getCriterionAverageByProjectId($project->id, $criterion->criterion);
				if($check) $results[] = $check;
			}
			$this->data['results_criteria'] = $results;

			// get detailled students results TODO Check for a mysql query
			$students = $this->Users_model->getAllUsersByClass('student', $project->class)[$project->class];
			$students_results = array();
			$submitted = 0;
			$p_to_submit = array();
			$i = 0;
			foreach ($students as $student)
			{
				$students_results[$i]['results'] = $this->Results_model->getResultsTable($student->id, $project->id);
				$students_results[$i]['overall'] = $this->Results_model->getUserProjectOverallResult($student->id, $project->id);
				$students_results[$i]['submitted_time'] = @$this->Submit_model->getSubmittedInfosByUserIdAndProjectId($student->id, $project->id)[0];
				$students_results[$i]['name'] = $student->name;
				$students_results[$i]['last_name'] = $student->last_name;

				// count how many to correct yet
				if($this->Submit_model->IsSubmittedByUserAndProjectId($student->id, $project->id))
				{
					$submitted++;
				}
				else {
					$p_to_submit[] = $student;
				}

				$i++;
			}
			$this->data['students_results'] = $students_results;
			$this->data['n_submitted'] = $submitted;
			$this->data['n_to_submit'] = count($students) - $submitted;
			$this->data['p_to_assess'] = $this->Grade_model->listUngradedProjectsByProjectId($project->id);
			$this->data['p_to_submit'] = $p_to_submit;
			$this->data['n_to_assess'] = count($this->data['p_to_assess']);
		}
		else
		{
			$this->data['students_results'] = array();
			$this->data['n_submitted'] = 0;
			$this->data['n_to_submit'] = 0;
			$this->data['p_to_assess'] = array();
			$this->data['p_to_submit'] = 0;
			$this->data['n_to_assess'] = 0;
		}

		if($this->input->get('modal'))
		{
			$this->load->template('admin/project_stats', $this->data, TRUE);
		}
		else
		{
			$this->load->template('admin/project_stats', $this->data);
		}
	}

	public function settings($action = FALSE)
	{
        $this->load->model('System_model','',TRUE);
        $this->data['folder_perms'] = array('/assets/uploads' => $this->System_model->getFolderPerms('/assets/uploads/'));

		$this->load->model('Skills_model','',TRUE);
		$this->load->model('Welcome_model','',TRUE);

		// POST
		if ($action === 'mail_test')
		{
			$this->Email_model->sendObjectMessageToEmail(   $this->input->post('subject'),
															$this->input->post('body'),
															$this->session->email
														);

			redirect('/admin/settings');
		}

		// GET
		$this->data['welcome_message'] = $this->Welcome_model->getWelcomeMessage(FALSE);
        $this->data['disk_space'] = $this->System_model->getUsedDiskSpace();
		$this->load->template('admin/settings', $this->data);
	}

    public function welcome_message($action = FALSE)
	{
		$this->load->model('Welcome_model','',TRUE);

		if ($action === 'update')
		{
			$this->Welcome_model->saveWelcomeMessage($this->input->post('welcome_message'));
		}

		$this->data['welcome_message'] = $this->Welcome_model->getWelcomeMessage(FALSE);

		// GET
		$this->load->template('admin/welcome_message', $this->data);
	}

}
