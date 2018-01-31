<?php
Class TableResults_model extends CI_Model
{

	/**
	* Returns all terms from table terms
	*
	* @return	array
	*/
	/**
	*  Magic call function getAllActiveProjects..By..And()
	*
	* @param 	string	$method
	* @param 	mixed[]	$args
	* @return	object
	*/

	// where class, 
	public function __call($method, $args)
	{
		if(strpos($method, 'getTableResults') === FALSE) exit;

		// if only one WHERE in function name
		if( ! strpos($method, 'And'))
		{
			$wheres = explode('By', $method);

			// removes first key (getAllActiveProjects)
			array_shift($wheres);
		}
		else
		{
			$wheres = explode('And', ltrim($method, 'getTableResults'));
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

}
?>
