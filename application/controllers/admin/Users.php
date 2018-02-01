<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_AdminController {

	function __construct()
	{
		parent::__construct();

		$submenu[] = array('title' => 'Élèves', 		'url' => '/admin/users/students');
		$submenu[] = array('title' => 'Professeurs', 'url' => '/admin/users/teachers');
		$this->data['submenu'] = $submenu;

		$this->load->model('UsersManager_model','',TRUE);
		$this->load->model('Roles_model','',TRUE);
	}

	public function index()
	{

	}

	public function students($action = FALSE)
	{
		if($action)
		{
			$this->_userDo($action, 'student');
			redirect('admin/users/students');
		}

		// GET
		$this->data['page_title'] = _('Gestion des élèves');
		$this->data['users'] = $this->Users_model->getAllStudentsSortedByClass();
		$this->data['classes'] = $this->Classes_model->getAllClasses();
		$this->load->template('admin/users', $this->data);
	}

	public function teachers($action = FALSE)
	{
		if($action)
		{
			$this->_userDo($action, 'admin');
			redirect('admin/users/teachers');
		}

		// GET
		$this->data['page_title'] = _('Gestion des professeurs');
		$this->data['users'] = $this->Users_model->getAllAdmins();

		$this->load->template('admin/admins', $this->data);
	}

	private function _userDo($action, $role)
	{
		$user_id = $this->input->post('id');
		$role_id = $this->Roles_model->getRoleIdFromName($role);

		$data = array(
			'username' 		=> $this->input->post('username'),
			'first_name' 	=> $this->input->post('name'),
			'last_name' 	=> $this->input->post('last_name'),
			'class' 			=> $this->input->post('class'),
			'email' 			=> $this->input->post('email'),
			'active' 		=> $this->input->post('active'),
			'password' 		=> $this->input->post('password')
			);


		switch($action)
		{
			case 'add_user':
				$user_id = $this->UsersManager_model->addUser($data);
				if ($this->UsersManager_model->username_check($data['username']))
				{
					show_error('Nom d\'utilisation déjà utilisé');
				}
				$this->Roles_model->addRoleToUser($role_id, $user_id);
				break;

			case 'update_user':
				$this->UsersManager_model->updateUser($user_id, $data);
				$this->Roles_model->RemoveAllRolesFromUser($user_id);
				$this->Roles_model->addRoleToUser($role_id, $user_id);
				break;

			case 'delete_user':
				$this->UsersManager_model->delUser($user_id);
				break;
		}
	}

}
