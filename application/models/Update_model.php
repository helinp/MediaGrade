<?php
/**
* Future model for getting user and system config
*/

Class Update_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	public function update_db()
	{
		$this->rebuild_config_table();
	}

	private function rebuild_config_table()
	{
		// drop existent table
		$this->load->dbforge();
		$this->dbforge->drop_table('config');

		// recreate it
		//$this->dbforge->add_key('key', TRUE);
		$fields = array(
			'key' => array('type' => 'VARCHAR', 'unique' => TRUE, 'constraint' => '40', 'index' => TRUE),
			'value' => array('type' => 'TEXT'),
			'default_value' => array('type' => 'TEXT'),
			'format' => array('type' => 'TEXT')
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('config');

		// insert new data
		$insert = array(
			array('key' => 'allowed_html_tags','value' => '','default_value' => '<table><p><a><h4><h5><h6><i><b><code><pre><video><audio>','format' => ''),
			array('key' => 'mail_protocol','value' => '','default_value' => 'smtp','format' => 'smtp,pop3'),
			array('key' => 'mode','value' => '','default_value' => 'production','format' => 'development,production,demonstration,read_only'),
			array('key' => 'assessment_mode','value' => '','default_value' => 'skills','format' => 'skills_group,skills'),
			array('key' => 'periods','value' => 'P1,P2,P3,DEC,JUN','default_value' => '','format' => ''),
			array('key' => 'smtp_host','value' => 'smtp.skynet.be','default_value' => '','format' => 'url'),
			array('key' => 'smtp_port','value' => '','default_value' => '25','format' => ''),
			array('key' => 'timezone','value' => 'Europe/Brussels','default_value' => 'Europe/Brussels','format' => 'See http://php.net/manual/en/timezones.php'),
			array('key' => 'welcome_message','value' => '<h2>Bienvenue, %user_name% !</h2>','default_value' => '','format' => '')
			);
			foreach ($insert as $row) {
				$this->db->insert('config', $row);
			}
		}
	}
	?>
