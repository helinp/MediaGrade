<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance extends MY_AdminController {

	function __construct()
	{
		parent::__construct();

		$submenu[] = array('title' => 'Maintenance', 			'url' => '/admin/maintenance');
		$submenu[] = array('title' => 'Contrôle du système', 	'url' => '/admin/maintenance/system');
		$this->data['submenu'] = $submenu;
	}

	function index()
	{
		$this->data['page_title'] = 'Maintenance';
		$this->load->template('admin/maintenance', $this->data);
	}

	function check_update()
	{
		$this->output->enable_profiler(FALSE);
		$this->load->library('github_updater');

		$has_update = $this->github_updater->has_update();

		print(json_encode($has_update, JSON_PRETTY_PRINT));
	}

	function upgrade()
	{
		$this->load->library('github_updater');

		$success = $this->github_updater->update();

		if($success)
		{
			// @TODO update Database
			// get SQL update
			// query sql

			$this->session->sess_destroy();
			redirect('/', 'refresh');
		}
		else
		{
			show_error('Erreur dans la mise à jour.');
		}
	}

	// @TODO Removes orphan entries
	function term_to_id()
	{

		$this->db->where('term', 'P1')->update('projects', array('term' => 1));
		$this->db->where('term', 'P2')->update('projects', array('term' => 2));
		$this->db->where('term', 'P3')->update('projects', array('term' => 3));
		$this->db->where('term', 'XDEC')->update('projects', array('term' => 4));
		$this->db->where('term', 'XJUN')->update('projects', array('term' => 5));

	}

	function cleanDb()
	{

	}


	/************************************************/
	/***************** manual update ****************/
	/************************************************/
	/* Run once on update 01/10/2018 (career) */
	function build_career() {
		$this->Careers_model->orderBy('last_name');
		$this->Careers_model->orderBy('school_year');
		$this->Careers_model->orderBy('classes.name');
		$this->Careers_model->firstBuildt();
	}

	/* Run once on update 31/12/2017 (ion auth) */
	public function auto_assign_roles()
	{
		$this->load->model('Roles_model','',TRUE);
		$users = $this->Users_model->getAllUsers();
		foreach ($users as $user)
		{
			if($user->class)
			{
				$this->Roles_model->RemoveAllRolesFromUser($user->id);
				$this->Roles_model->addRoleToUser(2, $user->id);
			}
		}
		dump($this->Users_model->messages());
	}

	public function activate_all_users()
	{
		$this->load->model('UsersManager_model','',TRUE);
		$users = $this->Users_model->getAllStudents();
		foreach ($users as $user)
		{
			if($user->class <> 6 && $user->class <> 0)
			{
				$this->UsersManager_model->updateUser($user->id, array('active' => 1));
			}

		}
		dump($this->UsersManager_model->messages());
	}

	public function set_classes_id()
	{
		$this->load->model('ProjectsManager_model','',TRUE);
		$projects = $this->Projects_model->getAllActiveProjects();
		foreach ($projects as $project)
		{
			$class_id = $this->Classes_model->getClassIdByName($project->class);
			$this->ProjectsManager_model->updateProject(array('id' => $project->project_id, 'class' => $class_id));
		}
	}

	/************************************************/
	/************************************************/
	/************************************************/

	function backup_db($action = FALSE)
	{
		$this->load->dbutil();
		$this->load->helper('date');
		$datestring = '%Y-%m-%d';
		$time = time();
		$date = mdate($datestring, $time);
		$filename = $date . '-mediagrade.sql';
		$prefs = array(
			'tables'        => array(),							// Array of tables to backup.
			'ignore'        => array(),                     // List of tables to omit from the backup
			'format'        => 'sql',                       // gzip, zip, txt
			'filename'      => $filename,    					// File name - NEEDED ONLY WITH ZIP FILES
			'add_drop'      => TRUE,                        // Whether to add DROP TABLE statements to backup file
			'add_insert'    => TRUE,                        // Whether to add INSERT data to backup file
			'newline'       => "\n"                         // Newline character used in backup file
		);

		// Backup your entire database and assign it to a variable
		$backup = $this->dbutil->backup($prefs);

		switch ($action) {
			case 'download':
			// Load the download helper and send the file to your desktop
			$this->load->helper('download');
			force_download($filename, $backup);
			break;

			case 'email':
			// @TODO
			break;

			default:
			// Load the file helper and write the file to your server
			$this->load->helper('file');
			write_file("/backup/$filename", $backup);
			break;
		}
	}

	function update_db($confirm)
	{
		$this->load->model('Update_model','',TRUE);
		$this->Update_model->update_30_10();
	}

	function system($action = FALSE)
	{
		$this->load->model('System_model','',TRUE);
		$this->data['folder_perms'] = array('/assets/uploads' => $this->System_model->getFolderPerms('/assets/uploads/'));

		$this->load->model('Skills_model','',TRUE);
		$this->load->model('Welcome_model','',TRUE);

		// POST
		if ($action === 'mail_test')
		{
			$this->Email_model->sendObjectMessageToEmail(   $this->input->post('subject'),
			$this->input->post('body'),
			$this->session->email
		);
	}

	// GET
	$this->data['page_title'] = 'Contrôle du système';
	$this->data['welcome_message'] = $this->Welcome_model->getWelcomeMessage(FALSE);
	$this->data['disk_space'] = $this->System_model->getUsedDiskSpace();
	$this->load->template('admin/system', $this->data);
}
}

?>
