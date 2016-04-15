<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	private $data;

	function __construct()
	{
		parent::__construct();
		$this->load->model('Users_model','',TRUE);
		$this->Users_model->loginCheck();
		$this->Users_model->adminCheck();

		$this->load->model('Submit_model','',TRUE);
		$this->load->model('Movies_model','',TRUE);
		$this->load->model('Projects_model','',TRUE);
		$this->load->model('System_model','',TRUE);

		$this->load->model('Classes_model','',TRUE);
		$this->data['classes'] = $this->Classes_model->listAllClasses();
	}

	// Dashboard
	public function index()
	{

			$this->data['last_submitted'] = $this->Submit_model->getNLastSubmitted();
			$this->data['last_movies'] = $this->Movies_model->getNLastSubmitted();
			$this->data['active_projects'] = $this->Projects_model->listAllActiveProjects();
			$this->data['not_graded_projects'] = $this->Submit_model->listNotGradedProjects();
			$this->data['disk_space'] = $this->System_model->getUsedDiskSpace();

		 	$this->load->template('admin/dashboard', $this->data, true);
	}

	public function export($periode = FALSE)
	{
			$this->load->model('Periods_model','',TRUE);
			$this->data['periodes'] = $this->Periods_model->listAllPeriods();
			$this->data['projects'] = $this->Projects_model->listAllActiveProjectsByPeriod($periode);

			$this->load->template('admin/export', $this->data, true);
	}

	public function results($class, $period = FALSE)
	{
			$users = $this->Users_model->getAllUsersByClass('student', $class);

			$this->load->helper('text');
			$this->load->helper('round');

			$this->load->model('Results_model','',TRUE);
			$this->load->model('Assessment_model','',TRUE);

			// make table header
			$header = array();
			$projects = $this->Projects_model->listAllActiveProjectsByClass($class, $period);

			// prepare data for body table
			foreach ($projects as $project)
			{
				$skills_group = $this->Assessment_model->getSkillsGroupByProject($project->project_id);

				$header[$project->project_id] = array(
						'project_name' => $project->project_name,
						 'skills_groups' => $skills_group,
					);
			}
//dump($header);
			$this->data['class'] = $class;
			$this->data['table_header'] = $header;
			$this->data['table_body'] = $this->Results_model->tableBodyClassResultsBySkillsGroup($class, $period);

			$this->load->model('Periods_model','',TRUE);
			$this->data['periods'] = $this->Periods_model->listAllPeriods();

			$this->load->template('admin/results', $this->data, true);
	}

	public function grade($class = FALSE, $project_id = FALSE, $user_id = FALSE)
	{

		// POST
		if($this->input->post())
		{
			$this->load->model('Grade_model','',TRUE);

			// save grades
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

			// saves comment
			$this->Grade_model->comment($this->input->post('project_id'),
										$this->input->post('user_id'),
										$this->input->post('comment')
									);

			redirect("/admin/grade/$class/$project_id/$user_id");
		}

		// Load Models
		$this->load->model('Users_model','',TRUE);
		$this->load->model('Comments_model','',TRUE);
		$this->load->model('Results_model','',TRUE);

		$this->data['class_users'] = $this->Users_model->getAllUsersByClass('student', $class);

		// mhash_keygen_s2k table CLASS -> USER_ID -> PROJECTS & USER
		$table = '';
		foreach($this->data['class_users'] as $class => $users)
		{
			foreach($users as $user)
			{
				$table[$class][$user->id]['user'] = $user;

				$projects = $this->Projects_model->listAllActiveProjectsByClassAndUser($class, $user->id);


				foreach($projects as $key => $project)
				{
					$projects[$key]->is_graded = $this->Results_model->isProjectGraded($user->id, $project->project_id);
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
			$this->data['submitted'] = $this->Submit_model->getSubmittedProject($user_id, $project_id);
			$this->data['self_assessments'] = $this->Projects_model->getSelfAssessment($project_id, TRUE, $user_id);
			$this->data['comment'] = $this->Comments_model->getAssessmentComment($user_id, $project_id);
			$this->data['assessment_table'] = $this->Results_model->getResultsTable($user_id, $project_id);
			$this->data['project'] = $this->Projects_model->getProjectData($project_id);

			// GET
			$this->load->template('admin/grade', $this->data, true);
		}
		// To grade_list
		else
		{
			$this->load->template('admin/grade_list', $this->data, true);
		}
	}



	/**
	 *
	 *		PROJECTS MANAGEMENT PAGE
	 *
	 */
	public function projects($project_id = FALSE)
	{
			// Models
			$this->load->model('Assessment_model','',TRUE);
			$this->load->model('Skills_model','',TRUE);
			$this->load->model('ProjectsManager_model','',TRUE);
			$this->load->model('Periods_model','',TRUE);

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

				// Create new project
				if( $post['project_id'] == '-1')
				{
					if(@$_FILES['instructions_pdf']['size'] > 0)
						$post['instructions_pdf'] = $this->uploadPDF($post);
					else
						$post['instructions_pdf'] = FALSE;

					$this->ProjectsManager_model->addProject($post);

				}
				// update project
				else
				{
					// if no new instructions
					if(@$_FILES['instructions_pdf']['size'] > 0)
						$post['instructions_pdf'] = $this->uploadPDF($post);
					else
						$post['instructions_pdf'] = FALSE;

					$this->ProjectsManager_model->updateProject($post, $project_id);

				}

			}

			// GET data from argument (to get project info)
			if($project_id)
			{
				$this->data['curr_project'] = $this->Projects_model->getProjectData($project_id);

				$this->data['active_skills'] = $this->Assessment_model->listAllSkillsByProjects($project_id, TRUE);
				$this->data['assessment_table'] = $this->Assessment_model->getAssessmentTable($project_id, TRUE);
				$this->data['active_self_assessments'] = $this->Assessment_model->getSelfAssessmentByProject($project_id, TRUE);
			}
			else
			{
				// user wants to create a new empty project
				include_once('./application/classes/AssessmentTable.class.php');
				$this->data['assessment_table'] = array(new AssessmentTable);
			}

			// Get data
			$this->data['projects'] = $this->Projects_model->listAllActiveProjects(FALSE);
			$this->data['skills'] = $this->Assessment_model->listAllSkills();
			$this->data['self_assessments'] = $this->Assessment_model->listAllSelfAssessments();
			$this->data['skills_groups'] = $this->Skills_model->listAllSkillsGroups();
			$this->data['periodes'] = $this->Periods_model->listAllPeriods();

			// helpers
			$this->load->helper('text');

			// template
		 	$this->load->template('admin/projects', $this->data, true);
	}


	private function uploadPDF($data = array())
	{

		$class = $data['class'];
		$periode = $data['periode'];
		$project_name = $data['project_name'];

		$config = $this->ProjectsManager_model->getUploadPDFConfig($class, $periode, $project_name);

		//var_dump($_POST);die;

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
		$this->load->model('Skills_model','',TRUE);

		// POST
		if($action === 'add_skill')  $this->Skills_model->addSkill($this->input->post('skill_id'), $this->input->post('skill'));
		elseif ($action === 'del_skill') $this->Skills_model->deleteSkill($this->input->post('skill_id'));

		if($action) redirect('/admin/skills');

		// GET
		$this->load->model('Assessment_model','',TRUE);
		$this->data['skills'] = $this->Assessment_model->listAllSkills();
		$this->load->template('admin/skills', $this->data, true);
	}

	public function skills_groups($action = FALSE)
	{
		$this->load->model('Skills_model','',TRUE);

		// POST
		if($action === 'add_skills_group')  $this->Skills_model->addSkillsGroup($this->input->post('skills_group'));
		elseif ($action === 'del_skills_group') $this->Skills_model->deleteSkillsGroup($this->input->post('skills_group'));
		if($action) redirect('/admin/skills_groups');

		// GET
		$this->data['skills_groups'] = $this->Skills_model->listAllSkillsGroups();
		$this->load->template('admin/skills_groups', $this->data, true);
	}

	public function periodes($action = FALSE)
	{
		$this->load->model('Periods_model','',TRUE);

		// POST
		if($action === 'add_periode')  $this->Periods_model->addPeriode($this->input->post('periode'));
		elseif ($action === 'del_periode') $this->Periods_model->deletePeriode($this->input->post('periode'));
		if($action) redirect('/admin/periodes');

		// GET
		$this->data['periodes'] = $this->Periods_model->listAllPeriods();
		$this->load->template('admin/periodes', $this->data, true);
	}

	public function users($action = FALSE)
	{

		$this->load->model('UsersManager_model','',TRUE);

		// POST
		if ($action === 'add_user')
		{
			$data = array(
					'username' 	=> $this->input->post('username'),
					'name' 		=> $this->input->post('name'),
					'last_name' => $this->input->post('last_name'),
					'class' 	=> $this->input->post('class'),
					'email' 	=> $this->input->post('email'),
					'role' 	=> $this->input->post('role'),
					'password' 	=> $this->input->post('password')
			);
			$this->UsersManager_model->addUser($data);
		}

		elseif ($action === 'delete_user')
		{
			$this->UsersManager_model->delUser($this->input->post('id'));
		}

		elseif ($action === 'update_user')
		{
			$data = array(
					'id' 	=> $this->input->post('id'),
					'username' 	=> $this->input->post('username'),
					'name' 		=> $this->input->post('name'),
					'last_name' => $this->input->post('last_name'),
					'class' 	=> $this->input->post('class'),
					'email' 	=> $this->input->post('email'),
					'role' 	=> $this->input->post('role'),
					'password' 	=> $this->input->post('password')
			);
			$this->UsersManager_model->updateUser($data);
		}

		if($action) redirect('/admin/users');


		// GET
		$this->data['admins'] = $this->Users_model->getAllUsersByClass('admin');
		$this->data['users'] = $this->Users_model->getAllUsersByClass();

		$this->load->template('admin/users', $this->data, true);
	}

	public function settings($action = FALSE)
	{
		$this->load->model('Skills_model','',TRUE);
		$this->load->model('Welcome_model','',TRUE);

		// POST
		if ($action === 'mail_test')
		{
			$this->load->library('email');

			$this->email->from($this->session->email, 'MediaGrade');
			$this->email->to($this->session->email);

			$this->email->subject($this->input->post('subject'));
			$this->email->message($this->input->post('body'));

			$this->email->send(FALSE);
			$this->email->print_debugger(array('headers'));
			redirect('/admin/settings');
		}
		elseif ($action === 'welcome_message')
		{
			$this->Welcome_model->saveWelcomeMessage($this->input->post('welcome_message'));
		}


		$this->data['welcome_message'] = $this->Welcome_model->getWelcomeMessage();

		// GET
		$this->load->template('admin/settings', $this->data, true);
	}


}
