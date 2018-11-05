<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends MY_AdminController {

	private $data;

	function __construct()
	{
		parent::__construct();

		// Models
		$this->load->model('ProjectsManager_model','',TRUE);
		$this->load->model('Comments_model','',TRUE);
		$this->load->model('FilesFormat_model','',TRUE);

		// helpers
		$this->load->helper('school');
		$this->load->helper('text');
		$this->load->helper('round');

		$this->data['classes'] = $this->Classes_model->getAllClasses();
		$this->data['courses'] = $this->Courses_model->getAllCourses();
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
	}


	public function instructions($project_id)
	{
		$project = $this->Projects_model->getInstructionsFromProjectId($project_id);

		$this->data['instructions_pdf'] = $project->pdf;
		$this->data['instructions_txt'] = $project->txt;

		$this->load->helper('pdf');
		$this->load->helper('url');

		$this->data['title'] = _('Consignes');
		$this->load->template('admin/instructions', $this->data, $this->input->get('modal'));
	}


	/**
	*
	*		PROJECTS MANAGEMENT PAGE
	*
	*/
	public function management($project_id = FALSE)
	{
		// TODO : control for empty fields

		// GET data from argument (to get project info)
		{
			if(is_numeric($project_id))
			{
				$this->data['curr_project'] = $this->Projects_model->getProjectDataByProjectId($project_id);
				$this->data['active_skills'] = $this->Skills_model->getAllSkillsByProjects($project_id, TRUE);
				$this->data['assessment_table'] = $this->Assessment_model->getAssessmentsByProjectId($project_id, TRUE);
				$this->data['active_self_assessments'] = $this->Assessment_model->getSelfAssessmentIdsByProject($project_id);

				$this->data['page_title'] = _('Gestion');
			}
			else
			{
				// user wants to create a new empty project
				$this->data['assessment_table'] = array(new Assessment_model);
				$this->data['page_title'] = _('Nouveau projet');
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
		}
		$this->load->template('admin/project_management', $this->data, $this->input->get('modal'));

	}

	function record()
	{
		if($project_id = $this->input->post('project_id'))
		{
			if($this->input->post('disactivate_project'))
			{
				$this->ProjectsManager_model->switchProjectState($this->input->post('disactivate_project'));
				redirect('/admin/projects');
			}
			elseif($this->input->post('delete_project'))
			{
				$this->ProjectsManager_model->deleteProject($this->input->post('delete_project'));
				redirect('/admin/projects');
			}
			// ADD, UPDATE or DUPLICATE PROJECT
			elseif($this->input->post('save_project') || $this->input->post('update_project') || $this->input->post('duplicate_project'))
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
					'id' 									=> $this->input->post('project_id'),
					'project_name' 				=> $this->input->post('project_name'),
					'assessment_type' 		=> $this->input->post('assessment_type'),
					'term' 								=> $this->input->post('term'),
					'class' 							=> $this->Courses_model->getClassIdFromCourseId($this->input->post('course_id')),
					'deadline' 						=> $this->input->post('deadline'),
					'start_date'					=> $this->input->post('start_date'),
					'school_year'					=> get_school_year(),
					'skill_ids' 					=> implode(',', $this->input->post('seen_skill_ids')),
					'external' 						=> $this->input->post('external') == TRUE,
					'material'						=> $this->input->post('material'),
					'extension' 					=> $this->input->post('extension'),
					'course_id' 					=> $this->input->post('course_id'),
					'instructions_txt'		=> serialize(array(
						'instructions'  			=> $this->input->post('instructions_txt'),
						'context'							=>  $this->input->post('context_txt')
					)),
					'number_of_files'			=> $this->input->post('number_of_files'),
					'self_assessment_ids' => implode(',', $self_assessment_ids),
					'is_activated' 				=> '1',
					'admin_id'						=> $this->session->id
				);

				/**
				* SAVE PROJECT
				*/
				if($this->input->post('save_project') || $this->input->post('duplicate_project'))
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

				// TODO IF update, check removed assessments
				if($this->input->post('update_project'))
				{
					// find removed ASSESSMENTS
					$post_assessements = $this->input->post('assessment_id');
					$project_assessments = $this->Assessment_model->_getAssessmentsIdByProject($this->input->post('project_id'));
					$removed_assessments = array_diff($project_assessments, $post_assessements);

					// Remove ASSESSMENTS
					foreach ($removed_assessments as $key => $assessment_id)
					{
						$this->Assessment_model->deleteAssessment($assessment_id);
						$this->Assessment_model->deleteProjects_Assessments($this->input->post('project_id'), $assessment_id);
					}
				}

				foreach($skills as $key => $skill_id)
				{
					if($this->config->item('assessment_mode') !== 'skills_group')
					{
						$_POST['skills_groups'][$key] = $this->Skills_model->getSkillGroupsFromSkillId($skill_id);
					}
					$assessment = array(
						'id' 							=> $this->input->post('assessment_id')[$key],
						'skills_group' 		=> $this->input->post('skills_groups')[$key],
						'skill_id' 				=> $this->input->post('skill_ids')[$key],
						'criterion'				=> $this->input->post('criterion')[$key],
						'cursor' 					=> $this->input->post('cursor')[$key],
						'grading_type' 		=> $this->input->post('grading_type')[$key],
						'max_vote' 				=> $this->input->post('max_vote')[$key],
						'achievement_id' 	=> $this->input->post('achievement_id')[$key],
					);
					// Update existent assessment
					if($this->input->post('update_project') && $this->input->post('assessment_id')[$key])
					{
						$this->Assessment_model->updateAssessment($assessment);
					}
					else // assessment doesn't exist or needs to be duplicated
					{
						unset($assessment['id']);
						$assessment_id = $this->Assessment_model->addAssessment($assessment);

						$this->Assessment_model->addProjects_Assessments($project_id, $assessment_id);
					}
				}


				/**
				* SAVE PDF instructions
				*/
				if(@$_FILES['instructions_pdf']['size'] > 0)
				{
					$post['instructions_pdf'] = $this->uploadPDF($post);
				}
				else
				{
					$post['instructions_pdf'] = FALSE;
				}

				// Job done, redirect
				redirect('/admin/projects?school_year=' . get_school_year());
			}
		}
		else
		{
			show_error('Erreur');
		}
	}

	// TODO should be in models
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

	public function statistics($project_id = FALSE)
	{
		// cleaner url
		if(is_numeric($this->input->get('project')))
		{
			redirect('/admin/project/statistics/' . $this->input->get('project') . '?classe=' . $this->input->get('classe') . '&modal=' . $this->input->get('modal'));
		}

		// for the filter form in view
		if($classe = $this->input->get('classe'))
		{
			$projects = $this->Projects_model->getAllActiveProjectsByClassAndSchoolYear($classe, $this->school_year);
		}
		else
		{
			$projects = $this->Projects_model->getAllActiveProjectsBySchoolYear($this->school_year);
		}
		$this->data['projects'] = $projects;

		// process data
		if($project_id)
		{
			$project = $this->Projects_model->getProjectDataByProjectId($project_id);
			// Pourcentage de réussites
			$results = $this->Results_model->getStudentsAverageByProjectId($project->id);
			$n_students = $n_master = $n_success = $n_pass = $n_fail = 0;
			foreach ($results as $result)
			{
				$percentage = $result->user_vote / $result->max_vote * 100;

				if($percentage = 100) $n_master++;
				elseif($percentage > 69) $n_success++;
				elseif($percentage > 39) $n_pass++;
				elseif($percentage < 0) $n_fail++;
				$n_students++;
			}
			$this->data['success'] = array('master' => $n_master, 'success' => $n_success, 'pass' => $n_pass, 'fail' => $n_fail);

			// results by skills getAllSkillsGroups
			$skills_groups = $this->Skills_model->getAllSkillsGroups();
			$results = array();
			foreach ($skills_groups as $skills_group)
			{
				// get only array with resutls
				$check =  $this->Results_model->getSkillsGroupsAverageByProjectIdAndSkillGroup($project->id, $skills_group->name);
				if($check) $results[] = $check;
			}
			$this->data['results_skills'] = $results;

			// results by criterion getAllSkillsGroups
			$criteria = $this->Assessment_model->getCriteriaFromProjectId($project->id);
			$results = array();
			foreach ($criteria as $criterion)
			{
				// get only array with resutls
				$check =  $this->Results_model->getCriterionAverageByProjectId($project->id, $criterion->criterion);
				if($check) $results[] = $check;
			}
			$this->data['results_criteria'] = $results;

			// get detailled students results TODO Check for a mysql query
			$students = $this->Users_model->getAllStudentsSortedByClass($project->class)[$project->class];
			$students_results = array();
			$submitted = 0;
			$p_to_submit = array();
			$i = 0;
			foreach ($students as $student)
			{
				$students_results[$i]['results'] = $this->Results_model->getResultsTable($student->id, $project->id);
				$students_results[$i]['overall'] = $this->Results_model->getUserProjectOverallResult($student->id, $project->id);
				$students_results[$i]['submitted_time'] = @$this->Submit_model->getSubmittedInfosByUserIdAndProjectId($student->id, $project->id)[0];
				$students_results[$i]['first_name'] = $student->first_name;
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

		$this->data['page_title'] = _('Statistiques');

		$this->load->template('admin/project_stats', $this->data, $this->input->get('modal'));
	}

}
