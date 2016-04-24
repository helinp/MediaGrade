<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends CI_Controller {

	private $data;

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

		$this->load->helper('text');

		$this->data['projects'] = $this->Projects_model->listAllActiveProjectsByClassAndUser();
		$this->data['classes'] = $this->Classes_model->listAllClasses();

	}

	public function index()
	{

			$this->data['title'] = ucfirst('projets'); // Capitalize the first letter

			$this->data['content'] = $this->Welcome_model->getWelcomeMessage();

		 	$this->load->template('projects/welcome', $this->data, true);
	}

	public function instructions($project_id)
	{
		if (!is_numeric($project_id) || empty($this->Projects_model->checkProjectId($project_id)))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}
		$instructions = $this->Projects_model->getProjectInstructions($project_id);

		$content = (!empty($instructions->pdf) ? $instructions->pdf : $instructions->txt) ;

		$this->data['content'] = $content;
		$this->data['project_name'] = $this->Projects_model->getProjectName($project_id);

		$this->load->template('projects/instructions', $this->data, true);

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
		$this->data['project_name'] = $this->Projects_model->getProjectName($project_id);
		$this->data['project_id'] = $project_id;
		$this->data['self_assessment'] = $this->Projects_model->getSelfAssessment($project_id, true);

		$this->load->template('projects/submit', $this->data, true);
	}

	public function results($project_id)
	{
		if (!is_numeric($project_id) || empty($this->Projects_model->checkProjectId($project_id)))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$submitted = $this->Submit_model->getSubmittedInformations($project_id);
		$comments = $this->Projects_model->getResultsComments($project_id);
		$results = $this->Results_model->getResults($project_id);

		$this->data['project_name'] = $this->Projects_model->getProjectName($project_id);
		$this->data['submitted'] = $submitted;
		$this->data['comments'] = $comments;
		$this->data['results'] = $results;

		$this->load->template('projects/results', $this->data, true);

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

	   	if ($number_of_files !== $number_of_files_requested && !empty($ext))
	   	{
		   	show_error(_("Vous devez remettre $number_of_files_requested fichier(s)"));
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
				// upload file if requested
				if(!empty($ext))
				{
					// load config
					$config = $this->Submit_model->getSubmitConfig($project_id, $i + 1);
					$error = $this->Submit_model->do_upload($config, 'submitted_file_' . $i);

					if (isset($error['error']))
					{
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
				$file_name = $this->upload->data('file_name');
				$this->Submit_model->submitProject($project_id, $file_name, $answers);

		   	}
	   }
	   redirect('projects');
	}
}
