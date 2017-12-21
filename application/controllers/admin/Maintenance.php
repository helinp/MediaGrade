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
	function cleanDb()
	{

	}

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
		$this->Update_model->update_db();
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
