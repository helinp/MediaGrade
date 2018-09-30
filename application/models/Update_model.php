<?php
/**
* Future model for getting user and system config
*/

Class Update_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->dbforge();
	}

	public function update_db()
	{
		$this->rebuild_config_table();
	}


	public function update_30_10()
	{

		// ***************
		// EXTERNAL UPDATE
		// ***************
		$fields = array(
			'external' => array(
				'type' => 'BOOLEAN',
				'default' => '0'
			),
		);
		//$this->dbforge->add_column('projects', $fields);

		// ***************
		// COURSE ID
		// ***************
		$projects = $this->db->get('projects')->result();
		foreach($projects as $project)
		{
			$this->db->like('name', 'LABO');
			$this->db->where('class_id', $project->class);
			$query = $this->db->get('courses');
			$course_id = $query->row('id');

			$this->db->reset_query();
			$this->db->where('class', $project->class);
			$this->db->where('id', $project->id);
			//dump($this->db->get('projects')->result());
			$this->db->update('projects', array('course_id' => $course_id));

		}


		// ***************
		// submietted ext
		// ***************
		// delete any table
		$this->dbforge->drop_table('submitted_ext', TRUE);

		// create database
		$fields = array(
			'project_id' => array(
				'type' => 'INT'
			),
			'user_id' => array(
				'type' => 'INT'
			),
			'file_path' => array(
				'type' => 'VARCHAR',
				'constraint' => '200'
			),
			'file_name' => array(
				'type' => 'VARCHAR',
				'constraint' => '200'
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			),

		);
		$this->dbforge->add_field('id');
		$this->dbforge->add_field($fields);

		$this->dbforge->create_table('submitted_ext', TRUE);
		$this->dbforge->add_key('id');


		// ***************
		// career table
		// ***************
		// delete any table
		$this->dbforge->drop_table('careers',TRUE);

		// create database
		$fields = array(
			'user_id' => array(
				'type' => 'INT'
			),
			'first_name' => array(
				'type' => 'VARCHAR',
				'constraint' => '100'
			),
			'last_name' => array(
				'type' => 'VARCHAR',
				'constraint' => '100'
			),
			'class_name' => array(
				'type' => 'VARCHAR',
				'constraint' => '100'
			),
			'class_id' => array(
				'type' => 'INT'
			),
			'school_year' => array(
				'type' =>'VARCHAR',
				'constraint' => '10'
			)
		);
		$this->dbforge->add_field('id');
		$this->dbforge->add_field($fields);

		$this->dbforge->create_table('careers', TRUE);



		// get lessons
		$this->db->select('submitted.user_id AS user_id, classes.id AS class_id, classes.name AS class_name, projects.school_year AS school_year, first_name, last_name');
		$this->db->distinct();
		$this->db->join('submitted', 'projects.id = submitted.project_id');
		$this->db->join('classes', 'projects.class = classes.id');
		$this->db->join('users', 'users.id = submitted.user_id');

		$query = $this->db->get('projects');
		$result = $query->result();
		// save in db
		foreach ($result as $entry)
		{
			$this->db->insert('careers', $entry);
		}

		$this->db->reset_query();
		// get current users
		$this->db->select('users.id AS user_id, class AS class_id, classes.name AS class_name, "2018-2019" AS school_year, first_name, last_name');
		$this->db->join('classes', 'classes.id = users.class');
		$query = $this->db->get('users');
		$result = $query->result_array();
		// save in dbdump(t)
		foreach ($result as $entry)
		{
			$check = $this->db->where($entry)->get('careers')->row('class_id');
			if( ! $check && $entry['class_id'] != 6) $this->db->insert('careers', $entry);
		}
	}



	public function update_18_08()
	{
		$fields = array(
			'grading_type' => array(
				'type' => 'VARCHAR',
				'constraint' => '50',
				'default' => 'default'
			),
		);
		$this->dbforge->add_column('assessments', $fields);

	}

	private function rebuild_config_table()
	{
		// drop existent table
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
