<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tools extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Users_model','',TRUE);
		//$this->Users_model->loginCheck();
		//$this->Users_model->adminCheck();
	}

	function index()
	{

	}


	function update()
	{

		$this->load->library('github_updater');

		$success = $this->github_updater->update();

		dump($success);
		// update Database
		// $this->session->sess_destroy();
		// redirect('/', 'refresh');
	}

	function backup_db($action)
	{
		// Load the DB utility class
		$this->load->dbutil();

		$prefs = array(
		        'tables'        => array(),  						 // Array of tables to backup.
		        'ignore'        => array(),                     // List of tables to omit from the backup
		        'format'        => 'txt',                       // gzip, zip, txt
		        'filename'      => 'mybackup.sql',              // File name - NEEDED ONLY WITH ZIP FILES
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
				force_download('mybackup.gz', $backup);
				break;

			default:
			// Load the file helper and write the file to your server
			$this->load->helper('file');
			write_file('/path/to/mybackup.gz', $backup);
				break;
		}
	}

}

?>
