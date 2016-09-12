<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Users_model','',TRUE);
		$this->Users_model->loginCheck();

		$this->load->model('Projects_model','',TRUE);
		$this->load->model('Classes_model','',TRUE);
		$this->load->model('Welcome_model','',TRUE);
		$this->load->model('Results_model','',TRUE);
		$this->load->model('Submit_model','',TRUE);
		$this->load->model('Grade_model','',TRUE);
		$this->load->model('Skills_model','',TRUE);

		$this->load->helper('text');
		$this->load->helper('school');
		$this->load->helper('graph');

		if( ! empty($this->input->get('school_year')))
			$this->school_year = $this->input->get('school_year');
		else
			$this->school_year = get_school_year();

		$this->projects = $this->Projects_model->getAllActiveProjectsByClassAndSchoolYear($this->session->class, $this->school_year);
		$this->data['projects'] = $this->projects;

		$this->data['school_year'] = $this->school_year;
		$this->data['school_years'] = $this->Projects_model->getSchoolYears();
	}

	/** DASHBOARD **/
	public function index()
	{
		/** get not submitted projects **/
		$not_submitted_projects = array();

		foreach ($this->data['projects'] as $project)
		{
			if ( ! $this->Submit_model->boolIfSubmittedByUserAndProjectId(FALSE, $project->project_id))
			$not_submitted_projects[] = $project;
		}

		$this->data['not_submitted'] = $not_submitted_projects;

		/** get graded projects **/
		$graded_projects = array();

		foreach ($this->projects as $project)
		{
			if ($this->Grade_model->boolGradedProjectByProjectAndUser($project->project_id))
			{
				// get project media vote
				$project->average = $this->Results_model->getUserProjectOverallResult(FALSE, $project->project_id);

				$graded_projects[] = $project;
			}
		}

		$this->data['graded'] = $graded_projects;

		/** get graph skills progression **/
		$skills_groups = $this->Skills_model->getAllSkillsGroups();

		$this->data['graph_results'] = graph_results($this->Results_model->getUserOverallResults($skills_groups, array_reverse($this->projects)));
		$this->data['graph_projects_list'] = graph_projects(array_reverse($this->projects));
		$this->data['title'] = ucfirst('projets'); // Capitalize the first letter
		$this->data['content'] = $this->Welcome_model->getWelcomeMessage();


		$this->load->template('student/dashboard', $this->data);
	}


	public function overview()
	{

		foreach($this->projects as $key => $project)
		{
			$this->projects[$key]->result = $this->Results_model->getUserProjectOverallResult(FALSE, $project->project_id);
			$this->projects[$key]->submitted = $this->Submit_model->boolIfSubmittedByUserAndProjectId(FALSE, $project->project_id);
			$this->projects[$key]->graded = $this->Grade_model->boolGradedProjectByProjectAndUser($project->project_id, FALSE);
		}

		$this->load->helper('deadline');
		$this->load->template('student/overview', $this->data);

	}

	public function instructions($project_id)
	{
		if (!is_numeric($project_id) || empty($this->Projects_model->checkProjectId($project_id)))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$this->load->helper('pdf');

		$this->load->model('Assessment_model','',TRUE);
		$this->data['assessment_table'] = $this->Assessment_model->getAssessmentTable($project_id);
		$this->data['instructions']  = $this->Projects_model->getInstructionsByProjectId($project_id);

		$this->load->template('student/instructions', $this->data, TRUE);

	}

	public function submit($project_id)
	{
		if (!is_numeric($project_id) || empty($this->Projects_model->checkProjectId($project_id)))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}
		$this->load->helper('format_bytes');
		$this->data['submit'] = $this->Submit_model->getSubmitInformations($project_id);
		$this->data['submitted'] = $this->Submit_model->getSubmittedInformations($project_id);
		$this->data['self_assessment'] = $this->Submit_model->getSelfAssessmentByProjectId($project_id, true);
		$this->data['project'] = $this->Projects_model->getProjectDataByProjectId($project_id);

		$this->load->template('student/submit', $this->data, TRUE);
	}

	public function results($project_id)
	{
		if (!is_numeric($project_id) || empty($this->Projects_model->checkProjectId($project_id)))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}
		$this->load->model('Comments_model','',TRUE);

		$this->data['project'] = $this->Projects_model->getProjectDataByProjectId($project_id);
		$this->data['submitted'] = $this->Submit_model->getSubmittedInformations($project_id);
		$this->data['comments'] = $this->Comments_model->getCommentsByProjectIdAndUserId($project_id);
		$this->data['results'] = $this->Results_model->getResultsByProjectAndUser($project_id);

		$this->load->template('student/results', $this->data, TRUE);

	}

	/** TODO: REMOVE FUNCTION AND VIEW BECAUSE OBSOLETE**/

	public function result_details($project_id)
	{
		$this->load->model('Results_model','',TRUE);
		$this->load->model('Assessment_model','',TRUE);

		// get project info
		$project = $this->Projects_model->getProjectDataByProjectId($project_id);

		$first_col = $this->Assessment_model->getAssessmentTable($project_id);

		foreach($first_col as $key => $row)
		{
			$first_col[$key]->user_vote = @$this->Results_model->getResultsByProjectAndUser($project_id, $this->session->id)[$key]->user_vote;
		}

		$this->data['project_name'] = $project->project_name;
		$this->data['results'] = $first_col;

		$this->load->helper('round');

		$this->load->template('student/result_details', $this->data, TRUE);
	}


	public function upload($project_id)
	{
		$this->load->model('Submit_model','',TRUE);

		if($this->Submit_model->isDeadlineReached($project_id))
		{
			show_error(_('La Deadline est désormais dépassée!'));
		}

		$number_of_files = count($_FILES);
		$number_of_files_requested = $this->Submit_model->getSubmitInformations($project_id)->number_of_files;
		$ext = $this->Submit_model->generateAllowedFileType($project_id);
		$answers = '';

		if ($number_of_files != $number_of_files_requested && !empty($ext))
		{
			show_error(_("Vous avez remis $number_of_files sur $number_of_files_requested fichier(s)."));
		}
		else
		{
			// save answers
			if(isset($_POST['self_assessment']))
			{
				$i = 0;
				foreach ($_POST['self_assessment'] as $answer)
				{
					$answers[$i] = array(
						'id' => $_POST['self_assessment_id'][$i],
						'answer' => htmlspecialchars($answer)
					);
					$i++;
				}
			}

			// Save POST
			$i = $number_of_files_requested;
			while ($i--)
			{
				// $file_name = NULL; TODO

				// upload file if requested
				if( ! empty($ext))
				{
					// load config
					$config = $this->Submit_model->getSubmitConfig($project_id, $i + 1);
					$error = $this->Submit_model->do_upload($config, 'submitted_file_' . $i);
					$file_name = $this->upload->data('file_name');

					if (isset($error['error']))
					{
                                                dump($this->upload->data());
						show_error($error['error']);
					}

					// make image thumbnail
					if($ext === 'jpg' || $ext === 'png' || $ext === 'gif')
					{
						$file_path = $config['upload_path'] . $file_name;
						$real_path = $this->upload->data('file_path');
						$this->Submit_model->makeThumbnail($file_path, $real_path);
					}
				}

				// update database
				$this->Submit_model->submitProject($project_id, $file_name, $answers);

			}
		}
		// Send email second preferences
		$user_preferences = $this->Users_model->getUserPreferences('email');

		$admin_id = $this->Projects_model->getAdminIdFromProjectId($project_id);
		$admin_email = $this->Users_model->getEmailFromUserId($admin_id);

		$admin_preferences = $this->Users_model->getUserPreferences('email', $admin_id);
		$project_info = $this->Projects_model->getProjectDataByProjectId($project_id);

		$this->load->model('Email_model','',TRUE);

		if(@$admin_preferences['submit_confirmation'])
		$this->Email_model->sendSubmitConfirmationToAdmin($project_info->project_name, $admin_email);

		if(@$user_preferences['submit_confirmation'])
		$this->Email_model->sendSubmitConfirmationToUser($project_info->project_name, $this->session->email);

		// Show success page
		$this->success(_('Ton projet a bien été téléversé!'));
	}

	private function success($message)
	{
		$this->data['message'] = $message;
		$this->load->template('success', $this->data, TRUE);
	}
}
