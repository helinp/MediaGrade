<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MoviesAdvisor extends CI_Controller {


	function __construct()
	{
		parent::__construct();
		$this->load->model('Users_model','',TRUE);
        $this->load->model('Movies_model','',TRUE);
		$this->Users_model->loginCheck();

	}

	public function index()
	{
			if ( ! file_exists(APPPATH.'views/projects/'.$page.'.php'))
			{
					// Whoops, we don't have a page for that!
					show_404();
			}

			$this->data['content'] = $this->Movies_model->getWelcomeMessage();

		 	$this->load->template('movies_advisor', $this->data, true);
	}


}
