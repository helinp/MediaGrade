<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Config extends MY_AdminController {

	function __construct()
	{
		parent::__construct();

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
