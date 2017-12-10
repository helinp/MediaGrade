<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Users_model','',TRUE);
		$this->Users_model->loginCheck();
		$this->Users_model->adminCheck();

		if($this->config->item('mode') === 'development')
		{
			error_reporting(-1);
			ini_set('display_errors', 'On');
			$this->output->enable_profiler(TRUE);
		}
	}

	function index()
	{
		$this->load->template('admin/maintenance');
	}

	function check_update()
	{
		$this->output->enable_profiler(FALSE);
		$this->load->library('github_updater');

		$has_update = $this->github_updater->has_update();

		print(json_encode($has_update, JSON_PRETTY_PRINT));

		// update Database
		// $this->session->sess_destroy();
		// redirect('/', 'refresh');
	}
	function upgrade()
	{

		$this->load->library('github_updater');

		$success = $this->github_updater->update();

		dump('$success');
		// update Database
		// $this->session->sess_destroy();
		// redirect('/', 'refresh');
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

			default:
			// Load the file helper and write the file to your server
			$this->load->helper('file');
			write_file("/backup/$filename", $backup);
			break;
		}
	}

}

?>
