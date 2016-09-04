<?php
Class Projects_model extends CI_Model
{

	var $id;
	var $term;
	var $instructions_pdf;
	var $instructions_txt;
	var $deadline;
	var $project_name;
	var $class;
	var $self_assessment_ids;
	var $assessment_type;
	var $skill_ids;
	var $extension;
	var $number_of_files;
	var $is_activated;
	var $school_year;

	function __construct()
	{
		$this->load->helper('school');
		$this->school_year = get_school_year();

		if($this->input->get('school_year'))
			$this->school_year = $this->input->get('school_year');
	}


	/* WORK IN PROGRESS  */
	public function __call($method, $args)
	{

		if(strpos($method, 'getAllActiveProjects') === FALSE) exit;

		// if only one WHERE in function name
		if( ! strpos($method, 'And'))
		{
			$wheres = explode('By', $method);

			// removes first key
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
			term,
			instructions_txt,
			deadline as raw_deadline,
			DATE_FORMAT(deadline, '%W %d %M %Y') as deadline");

		$this->db->where('is_activated', TRUE);

		if($this->session->role === 'admin')
			$this->db->where('admin_id', $this->session->id);

		for($i = 0, $count = count($wheres) - 1 ; $i <= $count ; $i++)
		{
			// arguments translation
			if($wheres[$i] === 'User') $wheres[$i] = 'user_id';
			elseif($wheres[$i] === 'SchoolYear') $wheres[$i] = 'school_year';

			// Do not consider WHERE if no ARG
			if($args[$i]) $this->db->where(strtolower($wheres[$i]), $args[$i]);
		}

		$this->db->order_by('class', 'DESC');
		$this->db->order_by('deadline', 'ASC');
		$this->db->order_by('term', 'DESC');

		return $this->db->get('projects')->result();
	}




	public function getAllActiveProjectsByClassAndTermAndSchoolYear($class = FALSE, $term = FALSE, $school_year)
	{
		if (!$class) $class = $this->session->class;

		$this->db->distinct();
		$this->db->select('projects.id as project_id, project_name, term, deadline, instructions_txt', FALSE);

		$this->db->where('is_activated', TRUE);
		$this->db->where('class', $class);
		$this->db->where('school_year', $school_year);

		$this->db->order_by('class', 'DESC');
		$this->db->order_by('deadline', 'ASC');
		$this->db->order_by('term', 'DESC');

		if($term) $this->db->where('term', $term);

		return $this->db->get('projects')->result();
	}


	/**
	*
	*	return array
	*
	*/
	public function getInstructionsByProjectId($project_id)
	{
		if( ! $project_id) return FALSE;

		$this->db->where('id', $project_id);
		$this->db->select('instructions_pdf as pdf, instructions_txt as txt, project_name');
		$row = $this->db->get('projects', 1)->row();

		$row->txt = unserialize($row->txt);

		return $row;
	}

	/**
	*
	*	return bool
	*
	*/
	public function checkProjectId($project_id, $class = false)
	{
		if( ! $project_id) return FALSE;
		if(!$class) $class = $this->session->class;

		$sql = "SELECT id FROM projects WHERE id = ? AND class = ? LIMIT 1";
		$query = $this->db->query($sql, array($project_id, $class));
		$row = $query->row();

		return ($row) ? true : false;
	}


	/**
	 *  Gets ADMIN's projects
	 *
	 */
	public function getAllActiveProjectsByAdmin($activated = TRUE, $school_year = FALSE)
	{

		$this->db->select("projects.id as project_id,
			project_name,
			term,
			deadline,
			is_activated,
			class", TRUE);
		$this->db->distinct();
		$this->db->where('admin_id', $this->session->id);

		if( ! $school_year) $school_year = $this->school_year;
		$this->db->where("school_year", $school_year);

		if($activated) $this->db->where('is_activated', TRUE);

		$this->db->order_by('projects.term', 'DESC');
		$this->db->order_by('class', 'DESC');
		$this->db->order_by('deadline', 'ASC');

		return $this->db->get('projects')->result();
	}

	public function getAllActiveProjectsByTerm($term = FALSE)
	{

		if(!$term) return $this->getAllActiveProjectsByAdmin();

		$this->db->select("projects.id as project_id,
			project_name,
			term,
			deadline,
			is_activated,
			class", TRUE);

		$this->db->distinct();

		$this->db->where('admin_id', $this->session->id);
		$this->db->where('term', $term);
		$this->db->where("school_year", $school_year);

		if($activated) $this->db->where('is_activated', TRUE);

		$this->db->order_by('projects.term', 'DESC');
		$this->db->order_by('class', 'DESC');
		$this->db->order_by('deadline', 'ASC');

		return $this->db->get('projects')->result();
	}

	public function getAllActiveAndCurrentProjects($class = FALSE)
	{

		$this->db->select('projects.id as project_id,
			project_name,
			term,
			deadline,
			is_activated,
			class');

		$this->db->distinct();
		$this->db->from('projects');
		$this->db->where('is_activated', TRUE);
		$this->db->where('deadline > CURDATE()');
		$this->db->where("school_year", $this->school_year);

		if($class) $this->db->where('projects.class', $class);
		if($this->session->role === 'admin') $this->db->where('admin_id', $this->session->id);

		$this->db->order_by('projects.term', 'DESC');
		$this->db->order_by('class', 'ASC');
		$this->db->order_by('deadline', 'DESC');

		return $this->db->get()->result();
	}

	public function getProjectDataByProjectId($project_id)
	{
		if( ! $project_id) return FALSE;

		$sql = "SELECT * FROM projects WHERE id = ? LIMIT 1";

		$query = $this->db->query($sql, array($project_id));
		$results = $query->row(0, 'Projects_model');

		if(@$results->instructions_txt) $results->instructions_txt = unserialize($results->instructions_txt);

		if(! $results) return new Projects_model;
		return $results;
	}

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

	public function getAdminIdFromProjectId($project_id)
	{
		$this->db->select('admin_id');
		$this->db->from('projects');
		$this->db->where('id', $project_id);
		$result = $this->db->get();

		if($result)
			return $result->row()->admin_id;
		else
			return FALSE;
	}

	public function getSchoolYears()
	{

		$this->db->distinct();
		$this->db->select('school_year');
		$this->db->from('projects');
		$this->db->order_by('school_year', 'DESC');
		$result = $this->db->get();

		if($result)
			return $result->result();
		else
			return FALSE;
	}

	public function boolMatchProjectSchoolYear($project_id)
	{
		$this->load->helper('school');
		$project_sy = $this->getProjectDataByProjectId($project_id)->school_year;

		return (get_school_year() === $project_sy);
	}
}
?>
