<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_AdminController {

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

		$submenu[] = array('title' => 'Élèves', 		'url' => '/admin/users/students');
		$submenu[] = array('title' => 'Professeurs', 'url' => '/admin/users/teachers');
		$this->data['submenu'] = $submenu;
	}

	public function index()
	{

	}
	public function students($action = FALSE)
	{
		if($action)
		{
			$this->_do($action);
		}

		// GET
		$this->data['page_title'] = _('Gestion des élèves');
		$this->data['users'] = $this->Users_model->getAllUsersByClass();

		$this->load->template('admin/users', $this->data);
	}

	public function teachers($action = FALSE)
	{
		if($action)
		{
			$this->_do($action);
		}

		// GET
		$this->data['page_title'] = _('Gestion des professeurs');
		$this->data['users'] = $this->Users_model->getAllUsersByClass('admin');

		$this->load->template('admin/users', $this->data);
	}


	private function _action($action)
	{
		$data = array(
			'id' 			=> $this->input->post('id'),
			'username' 	=> $this->input->post('username'),
			'name' 		=> $this->input->post('name'),
			'last_name' => $this->input->post('last_name'),
			'class' 		=> $this->input->post('class'),
			'email' 		=> $this->input->post('email'),
			'role' 		=> $this->input->post('role'),
			'password' 	=> $this->input->post('password')
			);

		$this->load->model('UsersManager_model','',TRUE);

		switch($action)
		{

			case 'add_user':
				$this->UsersManager_model->addUser($data);
				break;

			case 'update_user':
				$this->UsersManager_model->updateUser($data);
				break;

			case 'delete_user':
				$this->UsersManager_model->delUser($data->id);
				break;
		}
	}

}
