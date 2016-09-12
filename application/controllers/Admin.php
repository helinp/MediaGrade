<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	private $data;

	function __construct()
	{
		parent::__construct();
		$this->load->model('Users_model','',TRUE);
		$this->Users_model->loginCheck();
		$this->Users_model->adminCheck();

		$this->load->model('Email_model','',TRUE);
		$this->load->model('Submit_model','',TRUE);
		$this->load->model('Projects_model','',TRUE);
		$this->load->model('System_model','',TRUE);
		$this->load->model('Skills_model','',TRUE);
		$this->load->model('Terms_model','',TRUE);
		$this->load->model('Classes_model','',TRUE);

		$this->load->helper('school');

		$this->data['classes'] = $this->Classes_model->getAllClasses();
		$this->data['terms'] = $this->Terms_model->getAllTerms();

		if($this->input->get('school_year'))
			$this->school_year = $this->input->get('school_year');
		else
			$this->school_year = get_school_year();

		$this->data['school_years'] = $this->Projects_model->getSchoolYears();
	}

	public function index()
	{
		redirect('admin/dashboard?school_year=' . get_school_year());
	}


	// Dashboard
	public function dashboard()
	{
		$school_year = $class = FALSE;
		if($this->input->get('classe')) $class = $this->input->get('classe');
		if($this->input->get('school_year')) $school_year = $this->input->get('school_year');

		$this->load->model('Results_model','',TRUE);
		$this->load->model('Grade_model','',TRUE);

		$this->data['gauss'] =  $this->Results_model->getGaussDataByClassAndSchoolYearAndAdmin($class, $school_year, $this->session->id);
		$this->data['skills_usage'] = $this->Skills_model->getSkillsUsageByClass($class, FALSE, $school_year);
		$this->data['last_submitted'] = $this->Submit_model->getNLastSubmitted($class, 5, $school_year);
		$this->data['active_projects'] = $this->Projects_model->getAllActiveAndCurrentProjects($class);
		$this->data['not_graded_projects'] = $this->Grade_model->listNotGradedProjects($class, $school_year);
		$this->data['disk_space'] = $this->System_model->getUsedDiskSpace();

		$this->load->helper('form');
		$this->load->template('admin/dashboard', $this->data);
	}


	public function export()
	{
		if(!empty($this->input->get('term')))
			$term = $this->input->get('term');
		else
			$term = FALSE;

		$this->data['projects'] = $this->Projects_model->getAllActiveProjectsByTerm($term);
		$this->load->template('admin/export', $this->data);
	}


	public function result_details($project_id, $user_id = FALSE)
	{
		$this->load->model('Results_model','',TRUE);
		$this->load->model('Assessment_model','',TRUE);

		// get project info
		$project = $this->Projects_model->getProjectDataByProjectId($project_id);
		$class = $project->class;
		$first_col = $this->Assessment_model->getAssessmentTable($project_id);

		// if one student only, create dummy array
		if ($user_id)
		{
			$users[$class][0] = $this->Users_model->getUserInformations($user_id);
			//$users[$class][0]->id = $user_id;
		}
		else
		{
			// get all students
			$users = $this->Users_model->getAllUsersByClass('student', $class);
		}

		foreach($first_col as $key => $row)
		{
			foreach($users[$class] as $user)
			{
				$first_col[$key]->results[$user->id] = @$this->Results_model->getResultsByProjectAndUser($project_id, $user->id)[$key]->user_vote;
			}
		}

		$this->data['project_name'] = $project->project_name;
		$this->data['results'] = $first_col;
		$this->data['students'] = $users;

		$this->load->helper('round');
		$this->load->template('admin/result_details', $this->data, TRUE);
	}


	public function results($class)
	{
		if($this->input->get('term'))
			$term = $this->input->get('term');
		else
			$term = FALSE;

		$users = $this->Users_model->getAllUsersByClass('student', $class);

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
		$this->data['table_body'] = $this->Results_model->tableBodyClassResultsBySkillsGroup($class, $term, $this->school_year);

		$this->load->template('admin/results', $this->data);
	}


	public function grade($class = FALSE, $project_id = FALSE, $user_id = FALSE)
	{
		$this->load->model('Comments_model','',TRUE);

		// POST ACTION
		if($this->input->post() && $this->Projects_model->boolMatchProjectSchoolYear($this->input->post('project_id')))
		{
			$this->load->model('Grade_model','',TRUE);

			// save grades to DB
			$i = 0;
			foreach ($this->input->post('assessments_id') as $assessment_id)
			{
				$this->Grade_model->grade(	$this->input->post('project_id'),
											$this->input->post('user_id'),
											$assessment_id,
											$this->input->post("user_vote[$i]")
										 );

				$i++;
			}

			// saves comment to DB
			$this->Comments_model->comment($this->input->post('project_id'),
				$this->input->post('user_id'),
				$this->input->post('comment')
				);

			redirect("/admin/grade/$class/$project_id/$user_id");
		}


		// Load Models
		$this->load->model('Users_model','',TRUE);
		$this->load->model('Results_model','',TRUE);
		$this->load->model('Submit_model','',TRUE);

		// catch gets for grading_list table
		if(!$class = $this->input->get('classe')) $class = NULL;
		if(!$term = $this->input->get('term')) $term = NULL;

		$this->data['class_users'] = $this->Users_model->getAllUsersByClass('student', $class);

		// TODO mhash_keygen_s2k table CLASS -> USER_ID -> PROJECTS & USER
		$table = '';
		foreach($this->data['class_users'] as $class => $users)
		{
			foreach($users as $user)
			{
				$table[$class][$user->id]['user'] = $user;

				$projects = $this->Projects_model->getAllActiveProjectsByTermAndClassAndSchoolYear($term, $class, get_school_year());

				foreach($projects as $key => $project)
				{
					$projects[$key]->is_graded = $this->Results_model->boolIfGradedProject($user->id, $project->project_id);
					$projects[$key]->is_submitted = $this->Submit_model->boolIfSubmittedByUserAndProjectId($user->id, $project->project_id);
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
			$this->data['submitted'] = $this->Submit_model->getSubmittedByUserIdAndProjectId($user_id, $project_id);
			$this->data['self_assessments'] = $this->Submit_model->getSelfAssessmentByProjectId($project_id, TRUE, $user_id);
			$this->data['comment'] = $this->Comments_model->getCommentsByProjectIdAndUserId($project_id, $user_id);
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

		// TODO : control for empty fields

		// Get data
		$this->data['projects'] = $this->Projects_model->getAllActiveProjectsByAdmin(FALSE, $this->school_year);
		$this->data['terms'] = $this->Terms_model->getAllTerms();

		// helpers
		$this->load->helper('text');
		$this->load->helper('deadline');

		// template
		$this->load->template('admin/projects', $this->data);
	}


	public function instructions($project_id)
	{
		$project = $this->Projects_model->getInstructionsByProjectId($project_id);

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
                
		// TODO : control for empty fields

		// POST
		if($this->input->post('disactivate_project'))
		{
			$this->ProjectsManager_model->disactivateProject($this->input->post('disactivate_project'));
		}
		elseif($this->input->post('delete_project'))
		{
			$this->ProjectsManager_model->deleteProject($this->input->post('delete_project'));
			redirect('/admin/projects');
		}

		// ADD or UPDATE PROJECT
		elseif($this->input->post())
		{
			$post = $this->input->post();

			// Create new project or Update project
			if( $post['project_id'] == '-1')
			{
				$this->ProjectsManager_model->addProject($post);
			}
			else
			{
				$this->ProjectsManager_model->updateProject($post, $project_id);
			}

                        // Save & upload (or not) PDF instructions
			if(@$_FILES['instructions_pdf']['size'] > 0)
				$post['instructions_pdf'] = $this->uploadPDF($post);
			else
				$post['instructions_pdf'] = FALSE;

                        
			redirect('/admin/projects?school_year=' . get_school_year());
		}

		// GET data from argument (to get project info)
		if($project_id)
		{
			$this->data['curr_project'] = $this->Projects_model->getProjectDataByProjectId($project_id);
			$this->data['active_skills'] = $this->Skills_model->getAllSkillsByProjects($project_id, TRUE);
			$this->data['assessment_table'] = $this->Assessment_model->getAssessmentTable($project_id, TRUE);
			$this->data['active_self_assessments'] = $this->Assessment_model->getSelfAssessmentIdsByProject($project_id);
		}
		else
		{
			// user wants to create a new empty project
			$this->data['assessment_table'] = array(new Assessment_model);
		}

		// Get data
                $this->data['file_formats'] = $this->FilesFormat_model->getAllDistinctFormats();
		$this->data['projects'] = $this->Projects_model->getAllActiveProjectsByAdmin(FALSE);
		$this->data['skills'] = $this->Skills_model->getAllSkills();
		$this->data['self_assessments'] = $this->Assessment_model->getAllSelfAssessments();
		$this->data['skills_groups'] = $this->Skills_model->getAllSkillsGroups();
		$this->data['terms'] = $this->Terms_model->getAllTerms();

		$this->load->helper('text');
		$this->load->helper('deadline');
		$this->load->template('admin/project_management', $this->data, TRUE);
	}


	private function uploadPDF($data = array())
	{

		$class = $data['class'];
		$term = $data['term'];
		$project_name = $data['project_name'];

		$config = $this->ProjectsManager_model->getUploadPDFConfig($class, $term, $project_name);
		$error = $this->ProjectsManager_model->uploadPDF($config, 'instructions_pdf');

		if (isset($error['error']))
			show_error($error['error']);
		else
			return $config['file_db_path'] . $config['file_name'] . '.pdf';
	}


	public function skills($action = FALSE)
	{
		// POST
		if($action === 'add_skill')  $this->Skills_model->addSkill($this->input->post('skill_id'), $this->input->post('skill'));
		elseif ($action === 'del_skill') $this->Skills_model->deleteSkill($this->input->post('skill_id'));

		if($action) redirect('/admin/skills');

		// GET
		$this->load->model('Assessment_model','',TRUE);
		$this->data['skills'] = $this->Skills_model->getAllSkills();
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
		if($action === 'add_term')  $this->Terms_model->addTerm($this->input->post('term'));
		elseif ($action === 'del_term') $this->Terms_model->deleteTerm($this->input->post('term'));
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
					$this->UsersManager_model->delUser($this->input->post('id'));
					break;
			}
		}

		// GET
		$this->data['admins'] = $this->Users_model->getAllUsersByClass('admin');
		$this->data['users'] = $this->Users_model->getAllUsersByClass();

		$this->load->template('admin/users', $this->data);
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
									$this->session->email);

			redirect('/admin/settings');
		}

		$this->data['welcome_message'] = $this->Welcome_model->getWelcomeMessage(FALSE);
                $this->data['disk_space'] = $this->System_model->getUsedDiskSpace();

		// GET
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
