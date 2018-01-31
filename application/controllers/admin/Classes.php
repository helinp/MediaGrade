<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classes extends MY_AdminController {

	function __construct()
	{
		parent::__construct();

		$submenu[] = array('title' => 'Classes', 		'url' => '/admin/classes/classes');
		$submenu[] = array('title' => 'Cours', 		'url' => '/admin/classes/courses');
		$this->data['submenu'] = $submenu;

		$this->data['classes'] = $this->Classes_model->getAllClasses();

		$this->load->model('UsersManager_model','',TRUE);
		$this->load->model('Roles_model','',TRUE);
		$this->load->helper('form');
	}

	public function index()
	{

	}
	public function classes($action = FALSE, $id = FALSE)
	{
		if($action)
		{
			$param = array('name' 			=> $this->input->post('name'),
								'description' 	=> $this->input->post('description'),
								'id' 				=> $id
							);

			switch($action)
			{

				case 'add':
					unset($param['id']);
					$this->Classes_model->addClass($param);
					break;

				case 'update':
					$this->Classes_model->updateClass($param);
					break;

				case 'delete':
					$this->Classes_model->deleteClass($param['id']);
					break;
			}
			redirect('/admin/classes/classes');
		}

		// GET
		$this->data['page_title'] = _('Gestion des classes');
		$this->data['users'] = $this->Users_model->getAllStudentsSortedByClass();

		$this->load->template('admin/classes', $this->data);
	}

	/*
	 * Editing a class
	 */
	public function edit_class($id)
	{
		// check if the class exists before trying to edit it
		$this->data['class'] = $this->Classes_model->getClass($id);
		if(isset($this->data['class']->id))
		{
			if(isset($_POST) && count($_POST) > 0)
			{
				$param = array(	'name' 			=> $this->input->post('name'),
										'description' 	=> $this->input->post('description'),
										);

				$this->Classes_model->updateClass($id, $param);
				redirect('admin/classes/classes');
			}
			else
			{
				$this->load->template('admin/class_edit', $this->data, TRUE);
			}
		}
		else
		{
			show_error('La classe que vous voulez éditer n\'existe pas.');
		}
	}


	public function courses($action = FALSE, $id = FALSE)
	{
		if($action)
		{
			$param = array('name' 			=> $this->input->post('name'),
								'description' 	=> $this->input->post('description'),
								'teacher_id' 	=> $this->input->post('teacher_id'),
								'class_id' 		=> $this->input->post('class_id'),
								'id' 				=> $id
							);

			switch($action)
			{
				case 'add':
					unset($param['id']);
					$this->Courses_model->addCourse($param);
					break;

				case 'update':
					$this->Courses_model->updateCourse($param);
					break;

				case 'delete':
					$this->Courses_model->deleteCourse($param['id']);
					break;
			}
			redirect('/admin/classes/courses');
		}

		// GET
		$this->data['page_title'] = _('Gestion des cours');
		$this->data['courses'] = $this->Courses_model->getAllCourses();
		$this->data['teachers'] = $this->Users_model->getAllTeachers();
		$this->load->helper('form');
		$this->load->template('admin/courses', $this->data);
	}

}
