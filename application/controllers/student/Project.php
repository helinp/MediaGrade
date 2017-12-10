<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Welcome_model','',TRUE);

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

	public function index()
	{
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


	public function upload($project_id = FALSE)
	{
		if( ! $project_id)
		{
			show_error('Aucun projet sélectionné');
		}
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
