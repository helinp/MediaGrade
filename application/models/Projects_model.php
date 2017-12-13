<?php
Class Projects_model extends CI_Model
{

	/**
	* @var int
	*/
	var $id;

	/**
	* @var string
	*/
	var $term;

	/**
	* @var string
	*/
	var $instructions_pdf;

	/**
	* @var string
	*/
	var $instructions_txt;

	/**
	* @var date
	*/
	var $deadline;

	/**
	* @var string
	*/
	var $project_name;

	/**
	* @var string
	*/
	var $class;

	/**
	* @var string (concatened integers)
	*/
	var $self_assessment_ids;

	/**
	* @var string
	*/
	var $assessment_type;

	/**
	* @var string (concatened integers)
	*/
	var $skill_ids;

	/**
	* @var string
	*/
	var $extension;

	/**
	* @var integer
	*/
	var $number_of_files;

	/**
	* @var boolean
	*/
	var $is_activated;

	/**
	* @var string
	*/
	var $school_year;

	/**
	* @var int
	*/
	var $admin_id;

	/**
	* @var string
	*/
	var $material;

	/**
	*  Construct method
	*
	*/
	function __construct()
	{
		$this->load->helper('school');
		$this->school_year = get_school_year();

		if($this->input->get('school_year'))
		{
			$this->school_year = $this->input->get('school_year');
		}
	}

	/**
	*  Magic call function getAllActiveProjects..By..And()
	*
	* @param 	string	$method
	* @param 	mixed[]	$args
	* @return	object
	*/
	public function __call($method, $args)
	{
		if(strpos($method, 'getAllActiveProjects') === FALSE) exit;

		// if only one WHERE in function name
		if( ! strpos($method, 'And'))
		{
			$wheres = explode('By', $method);

			// removes first key (getAllActiveProjects)
			array_shift($wheres);
		}
		else
		{
			$wheres = explode('And', ltrim($method, 'getAllActiveProjectsBy'));
		}

		// Sanity check
		if(count($args) !== count($wheres))
		throw new Exception("Error: Missing argument in function $method", 1);

		// Format data language in french
		// TODO: set a config file
		$this->db->query("SET lc_time_names = 'fr_FR'");

		$this->db->distinct();
		$this->db->select("projects.id as project_id,
		project_name,
		school_year,
		class,
		term,
		assessment_type,
		start_date,
		instructions_txt,
		deadline as raw_deadline,
		DATE_FORMAT(deadline, '%W %d %M %Y') as deadline,
		material");

		$this->db->where('is_activated', TRUE);

		if($this->session->role === 'admin')
		{
			$this->db->where('admin_id', $this->session->id);
		}

		for($i = 0, $count = count($wheres) - 1 ; $i <= $count ; $i++)
		{
			// arguments translation
			if($wheres[$i] === 'User') $wheres[$i] = 'user_id';
			elseif($wheres[$i] === 'SchoolYear') $wheres[$i] = 'school_year';

			// Do not consider WHERE if no ARG
			if($args[$i]) $this->db->where(strtolower($wheres[$i]), $args[$i]);
		}

		$this->db->order_by('class', 'DESC');
		//$this->db->order_by('id', 'ASC');
		$this->db->order_by('raw_deadline', 'ASC');

		return $this->db->get('projects')->result();
	}


	/**
	*  Gets active projects from given class, term and school year
	*
	* @param 	string	$class = FALSE
	* @param 	string	$term = FALSE
	* @param 	string	$school_year
	* @return	object
	*/

	public function getAllActiveProjectsByClassAndTermAndSchoolYear($class = FALSE, $term = FALSE, $school_year)
	{
		if ( ! $class) $class = $this->session->class;

		$this->db->distinct();
		$this->db->select('projects.id as project_id, project_name, term, deadline, start_date, instructions_txt', FALSE);

		$this->db->where('is_activated', TRUE);
		$this->db->where('class', $class);
		$this->db->where('school_year', $school_year);
		if($term) $this->db->where('term', $term);

		$this->db->order_by('class', 'ASC');
		$this->db->order_by('deadline', 'ASC');
		$this->db->order_by('projects.term', 'ASC');


		return $this->db->get('projects')->result();
	}


	/**
	*  Gets instructions of a given projects
	*
	* @param 	integer	$project_id
	* @return	object
	*/
	public function getInstructionsFromProjectId($project_id)
	{
		if( ! $project_id) return FALSE;

		$this->db->where('id', $project_id);
		$this->db->select('instructions_pdf as pdf, instructions_txt as txt, project_name');
		$row = $this->db->get('projects', 1)->row();

		$row->txt = unserialize($row->txt);

		return $row;
	}

	/**
	*  Checks if project_id exists in DB
	*
	* @param 	boolean	$project_id
	* @return	boolean
	*/
	public function isProjectIdInDb($project_id)
	{
		$sql = "SELECT id FROM projects WHERE id = ? LIMIT 1";
		$query = $this->db->query($sql, array($project_id));
		$row = $query->row();

		return ($row) ? true : false;
	}


	/**
	*  Gets teacher's projects
	*
	* @param 	boolean	$activated = TRUE
	* @param 	boolean	$school_year = FALSE
	* @return	object
	*/
	public function getAllActiveProjectsByAdmin($activated = TRUE, $school_year = FALSE)
	{
		$this->db->select("projects.id as project_id,
		project_name,
		term,
		deadline,
		is_activated,
		assessment_type,
		start_date,
		number_of_files,
		class", TRUE);
		$this->db->distinct();
		$this->db->where('admin_id', $this->session->id);

		if( ! $school_year) $school_year = $this->school_year;
		$this->db->where("school_year", $school_year);

		if($activated) $this->db->where('is_activated', TRUE);

		$this->db->order_by('projects.term', 'DESC');
		$this->db->order_by('class', 'ASC');
		$this->db->order_by('deadline', 'DESC');

		return $this->db->get('projects')->result();
	}

	/**
	* Returns current and activated projects data
	*
	* @param 	string		$class = FALSE
	* @return	object
	*/
	public function getAllActiveAndCurrentProjects($class = FALSE)
	{

		$this->db->select('projects.id as project_id,
		project_name,
		term,
		deadline,
		assessment_type,
		is_activated,
		class');

		$this->db->distinct();
		$this->db->from('projects');
		$this->db->where('is_activated', TRUE);
		$this->db->where('deadline > CURDATE()');
		$this->db->where("school_year", $this->school_year);

		if($class) $this->db->where('projects.class', $class);
		if($this->session->role === 'admin') $this->db->where('admin_id', $this->session->id);

		$this->db->order_by('class', 'DESC');
		$this->db->order_by('projects.term', 'DESC');
		$this->db->order_by('deadline', 'ASC');

		return $this->db->get()->result();
	}

	/**
	* Returns projects data
	*
	* @param 	integer		$project_id
	* @return	object
	*/
	public function getProjectDataByProjectId($project_id)
	{
		if( ! $project_id)
		{
			return FALSE;
		}

		$this->db->from('projects');
		$this->db->limit(1);
		$this->db->where('id', $project_id);
		$results = $this->db->get()->row();

		if(isset($results->instructions_txt) && $results->instructions_txt)
		{
			$results->instructions_txt = unserialize($results->instructions_txt);
		}

		if(! $results)
		{
			return new Projects_model;
		}
		else
		{
			return $results;
		}
	}

	/**
	* Gets the url of a random submitted project
	*
	* @return	string
	*/
	public function random_media()
	{
		$sql = "SELECT CONCAT(file_path, file_name) as url,
		RIGHT(file_name, 3) as extension
		FROM submitted
		ORDER BY RAND()
		LIMIT 1";

		$query = $this->db->query($sql);

		$url = $query->row()->url;
		$url = '/assets/' . $url;
		return $url;
	}

	/**
	* Returns teacher ID from a project
	*
	* @param 	integer		$project_id
	* @return	integer|boolean
	*/
	public function getAdminIdFromProjectId($project_id)
	{
		$this->db->select('admin_id');
		$this->db->from('projects');
		$this->db->where('id', $project_id);
		$result = $this->db->get();

		if($result) return $result->row()->admin_id;
		return FALSE;
	}



	/**
	* Returns all school years in projects
	*
	* @return	object|boolean
	*/
	public function getSchoolYears()
	{
		$this->db->distinct();
		$this->db->select('school_year');
		$this->db->from('projects');
		$this->db->order_by('school_year', 'DESC');
		$result = $this->db->get();

		if($result) return $result->result();

		return FALSE;
	}

	/**
	* Returns if a project is in current school year
	*
	* @param 	integer		$project_id
	* @return	boolean
	*/
	public function isProjectIdFromThisSchoolYear($project_id)
	{
		$this->load->helper('school');
		$project_sy = $this->getProjectDataByProjectId($project_id)->school_year;

		return (get_school_year() === $project_sy);
	}

	public function getMaterialStatisticsByAdminAndClassAndShoolYear($admin_id = FALSE, $class = FALSE, $school_year = FALSE)
	{
		$this->db->select('material');
		$this->db->from('projects');
		$this->db->where('is_activated', TRUE);

		if($class) $this->db->where("class", $class);
		if($school_year) $this->db->where("school_year", $school_year);
		if($admin_id) $this->db->where("admin_id", $admin_id);

		$result = $this->db->get()->result();

		$material = array();

		foreach ($result as $row)
		{
			if($row->material !== '')
			{
				$temp = explode(',', $row->material);

				foreach ($temp as $value)
				{
					$material[] = $value;
				}
			}
		}
		$counts = array_count_values($material);
		return($counts);
	}
}
?>
