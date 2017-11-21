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
		$this->load->model('Submit_model','',TRUE);
		$this->load->model('Terms_model','',TRUE);

		$this->load->helper('text');
		$this->load->helper('school');
		$this->load->helper('graph');

		if($this->config->item('mode') === 'development')
		{
			$this->output->enable_profiler(TRUE);
		}

		if( ! empty($this->input->get('school_year')))
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


// // //


// // //

		$this->data['best_results'] = $this->Results_model->getBestCursorResults(5);
		$this->data['worst_results'] = $this->Results_model->getWorstCursorResults(5);
		$this->data['criterion_results'] = $this->Results_model->getDetailledResults('criterion');
		$this->data['title'] = ucfirst('projets'); // Capitalize the first letter
		$this->data['content'] = $this->Welcome_model->getWelcomeMessage();

		$this->load->template('student/dashboard', $this->data);
	}


	public function overview()
	{
		$this->load->model('Achievements_model','',TRUE);

		foreach($this->projects as $key => $project)
		{
			$this->projects[$key]->achievements = $this->Achievements_model->getAllAchievementsByProject($project->project_id);
			$this->projects[$key]->result = $this->Results_model->getUserProjectOverallResult(FALSE, $project->project_id);
			$this->projects[$key]->submitted = $this->Submit_model->IsSubmittedByUserAndProjectId(FALSE, $project->project_id);
			$this->projects[$key]->graded = $this->Grade_model->isProjectGradedByProjectAndUser($project->project_id, FALSE);
		}

		$this->load->helper('deadline');
		$this->load->helper('assessment');
		$this->load->template('student/overview', $this->data);
	}

	public function instructions($project_id)
	{
		if (!is_numeric($project_id) || empty($this->Projects_model->isProjectIdInDb($project_id)))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$this->load->helper('pdf');

		$this->load->model('Assessment_model','',TRUE);
		$this->data['assessment_table'] = $this->Assessment_model->getAssessmentsByProjectId($project_id);
		$this->data['instructions']  = $this->Projects_model->getInstructionsFromProjectId($project_id);

		$this->load->template('student/instructions', $this->data, TRUE);

	}

	public function submit($project_id)
	{
		if ($this->config->item('mode') === 'read_only')
		{
			show_error(_('Version de consultation. Remise impossible!'));
		}

		if (!is_numeric($project_id) || empty($this->Projects_model->isProjectIdInDb($project_id)))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$project_data = $this->Projects_model->getProjectDataByProjectId($project_id);
		$this->load->model('FilesFormat_model','',TRUE);
		$project_data->mime = $this->FilesFormat_model->returnMimeFromExtension($project_data->extension);

		$this->load->helper('format_bytes');
		$this->data['submit'] = $this->Submit_model->getSubmitInformations($project_id);
		$this->data['submitted'] = $this->Submit_model->getSubmittedFilesPathsByProjectAndUser($project_id);
		$this->data['self_assessment'] = $this->Submit_model->getSelfAssessmentByProjectId($project_id, true);
		$this->data['project'] = $project_data;
		$this->data['error'] = '';

		$this->load->template('student/submit', $this->data, TRUE);
	}

	/* TODO Check if DEPRECATED*/
	public function results($project_id)
	{
		if (!is_numeric($project_id) || empty($this->Projects_model->isProjectIdInDb($project_id)))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}
		$this->load->model('Comments_model','',TRUE);

		$this->data['project'] = $this->Projects_model->getProjectDataByProjectId($project_id);
		$this->data['submitted'] = $this->Submit_model->getSubmittedFilesPathsByProjectAndUser($project_id);
		$this->data['comments'] = preg_replace("/\r\n|\r|\n/",'<br/>', $this->Comments_model->getCommentsByProjectIdAndUserId($project_id)->comment);
		$this->data['results'] = $this->Results_model->getResultsByProjectAndUser($project_id);

		$this->load->template('student/results', $this->data, TRUE);
	}

	public function result_details($project_id)
	{
		$this->load->model('Results_model','',TRUE);
		$this->load->model('Assessment_model','',TRUE);

		// get project info
		$project = $this->Projects_model->getProjectDataByProjectId($project_id);

		$first_col = $this->Assessment_model->getAssessmentsByProjectId($project_id);

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
		if ($this->config->item('mode') === 'read_only')
		{
			$this->triggerError(_('Version de consultation. Remise impossible!'));

			//show_error(_('Version de consultation. Remise impossible!'));
		}
		elseif($this->Submit_model->isDeadlineReached($project_id))
		{
			// show_error(_('La Deadline est désormais dépassée!'));
			$this->triggerError(_('La Deadline est désormais dépassée!'));
		}

		$number_of_files = count($_FILES['file']['name']);
		$number_of_files_expected = $this->Submit_model->getSubmitInformations($project_id)->number_of_files;
		$extension_expected = $this->Submit_model->getAllowedSubmittedFileType($project_id);
		$answers = '';

		if ($number_of_files != $number_of_files_expected && ! empty($extension_expected))
		{
			// show_error(_("Vous avez remis $number_of_files sur $number_of_files_expected fichier(s)."));
			$this->triggerError(_("Vous avez remis $number_of_files sur $number_of_files_expected fichier(s)."));
		}
		else
		{
			/**********************************************/
			/******  Save Self assessment answers  ********/
			/**********************************************/
			if(isset($_POST['self_assessment']))
			{
				$i = 0;
				foreach ($_POST['self_assessment'] as $answer)
				{
					$answer = preg_replace("/\r\n|\r|\n/",'<br/>', $answer);
					$answers[$i] = array(
						'id' => $_POST['self_assessment_id'][$i],
						'answer' => htmlspecialchars($answer)
					);
					$i++;
				}
			}

			/**********************************************/
			/*************  Save Files  *******************/
			/**********************************************/
			$submitted_files = '';
			$i = $number_submitted_files = count($_FILES['file']['name']);

			while ($i--)
			{
			//	dump($this->upload->data());
				// upload file if requested
				if( ! empty($extension_expected) && ! empty($_FILES['file']['name'][$i]))
				{
					// load config
					$config = $this->Submit_model->getProjectSubmitConfig($project_id, $i + 1);
					$file_name = $config['file_name'];

					// check MIME format
					$this->load->helper('file');
					$finfo = finfo_open(FILEINFO_MIME_TYPE); // Retourne le type mime à l'extension mimetype
					if(finfo_file($finfo, $_FILES['file']['tmp_name'][$i]) !== get_mime_by_extension('dummy.' . $extension_expected))
					{
						$this->triggerError(_("Le format du fichier n'est pas $extension_expected !"));
					}

					// TODO Cannot figure how to use do_upload with dropzonejs -> this is a workaround
					move_uploaded_file($_FILES['file']['tmp_name'][$i], $config['upload_path'] .  $file_name );

					// $error = $this->Submit_model->do_upload($config, 'file[]');
					// $error = $this->Submit_model->do_upload($config, $key);
					//$file_name = $this->upload->data('filename');

					 if (isset($error['error']))
					{
						$this->triggerError($error['error']);
						//	show_error($error['error']);
					}

					/** TODO optimize paths and filenames (_thumbs) -> rotateThumbnail should only rotate, not change filename**/
					// make image thumbnail
					if($extension_expected === 'jpg' || $extension_expected === 'png' || $extension_expected === 'gif')
					{
						$file_path = $config['upload_path'] . $file_name;
						$real_path = $this->upload->data('file_path');

						// Make thumb
						$thumb = $this->Submit_model->makeThumbnail($file_path, $real_path);

						// Rotate thumb
						// get original orientation
						$imgdata = exif_read_data($file_path, 'IFD0');
						$rotation = FALSE;
						switch($imgdata['Orientation'])
						{
							case 3:
							$rotation = '180';
							break;
							case 6:
							$rotation = '270';
							break;
							case 8:
							$rotation = '90';
							break;
						}
						if($rotation)
						{
							$this->Submit_model->rotateThumbnail($file_path, $rotation);
						}
						$submitted_files[] = $thumb;
					}
					// update database
					$this->Submit_model->submitProject($project_id, $file_name, $answers);
				}

			}
		}

		/**********************************************/
		/***** Send email second preferences  *********/
		/**********************************************/
		$user_preferences = $this->Users_model->getUserPreferences('email');

		$admin_id = $this->Projects_model->getAdminIdFromProjectId($project_id);
		$admin_email = $this->Users_model->getEmailFromUserId($admin_id);

		$admin_preferences = $this->Users_model->getUserPreferences('email', $admin_id);
		$project_info = $this->Projects_model->getProjectDataByProjectId($project_id);

		$this->load->model('Email_model','',TRUE);

		if(isset($admin_preferences['submit_confirmation']))
		{
			$this->Email_model->sendSubmitConfirmationToAdmin($project_info->project_name, $admin_email, $submitted_files);
		}

		if(isset($user_preferences['submit_confirmation']))
		{
			$this->Email_model->sendSubmitConfirmationToUser($project_info->project_name, $this->session->email, $submitted_files);
		}

		// Show success page
		//$this->success(_('Ton projet a bien été téléversé!'));
		echo _('Ton projet a bien été téléversé!');
	}

	// error handler for dropzonejs
	private function triggerError($message)
	{
		header('HTTP/1.1 500 Internal Server Error');
		header('Content-type: text/plain');
		exit($message);
	}

	private function success($message)
	{
		$this->data['message'] = $message;
		$this->load->template('success', $this->data, TRUE);
	}
}
