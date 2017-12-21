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

		$submenu = array(
			array('title' => 'Périodes', 'url' => '/admin/config/terms'),
			//array('title' => 'Cours', 'url' => '/admin/config/courses'),
			array('title' => 'Message d\'accueil', 'url' => '/admin/config/welcome_message'),
		);
		$this->data['submenu'] = $submenu;
	}

	public function welcome_message($action = FALSE)
	{
		$this->load->model('Welcome_model','',TRUE);

		if ($action === 'update')
		{
			$this->Welcome_model->saveWelcomeMessage($this->input->post('welcome_message'));
		}

		$this->data['welcome_message'] = $this->Welcome_model->getWelcomeMessage(FALSE);
		$this->data['page_title'] = _('Message d\'accueil');

		// GET
		$this->load->template('admin/welcome_message', $this->data);
	}

	public function terms($action = FALSE)
	{
		$this->load->model('Terms_model','',TRUE);

		// POST
		if($action === 'add_term')  $this->Terms_model->add($this->input->post('term'));
		elseif ($action === 'del_term') $this->Terms_model->delete($this->input->post('term'));
		if($action) redirect('/admin/config/terms');

		// GET
		$this->data['page_title'] = _('Périodes de cours');
		$this->load->template('admin/terms', $this->data);
	}

	public function courses()
	{
		// @TODO
	}


}
