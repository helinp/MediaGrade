<?php
Class Skills_model extends CI_Model
{

	public $skill_id;
	public $skill_group;
	public $skill;

	/**
	 * Returns how many times each skill is used by a project
	 *
	 * @param 	string				$class = FALSE
	 * @param 	integer				$percentage = FALSE
	 * @return	integer OR float	$school_year = FALSE
	 */
	public function getSkillsUsageByClass($class = FALSE, $percentage = FALSE, $school_year = FALSE)
	{
		$skills = $this->getAllSkills();
		$count = array();
		$n = 0;

		foreach($skills as $skill)
		{
			$this->db->from('projects');

			if($class) $this->db->where('class', $class);
			if($school_year) $this->db->where('school_year', $school_year);

			$this->db->like('skill_ids', $skill->skill_id, 'both');
			$this->db->order_by('id');

			$count[$skill->skill_id] = $this->db->count_all_results();

			$n++;
		}

		if ($percentage)
		{
				foreach ($count as $key => $value)
				{
					$count[$key] = $count[$key] / $n * 100;
				}
		}

		return $count ;
	}

	/**
	 * Returns all related skills for a given project
	 *
	 * @param 	integer				$project_id
	 * @param 	boolean				$only_id = FALSE
	 * @return	array
	 */
	public function getAllSkillsByProjects($project_id, $only_id = FALSE)
	{
		$sql = "SELECT skill_ids FROM projects where id = ? LIMIT 1";
		$query = $this->db->query($sql, array($project_id));
		if( ! $result = $query->row()) return new Skills_model;

		// return array and only ID
		$skills = explode(',', $result->skill_ids);
		if ($only_id) return $skills;

		// return array WITH names
		$project_skills = array();

		if( ! $skills) return new Skills_model;

		foreach ($skills as $skill)
		{
			$sql ="SELECT skill, skill_group, skill_id FROM skills WHERE skill_id = ?";
			$query = $this->db->query($sql, array($skill));
			$result = $query->row();
			if ( ! empty($result)) array_push($project_skills, $result);
		}
		return $project_skills;
	}

	/**
	 * Returns all related skills for a given project
	 *
	 * @param 	integer				$project_id
	 * @param 	boolean				$only_id = FALSE
	 * @return	array
	 */
	public function getAllSkills()
	{
		$sql = "SELECT skill, skill_group, skill_id FROM skills ORDER BY skill_id ASC";

		$query = $this->db->query($sql);

		if($query)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Add a skill in skills table
	 *
	 * @param 	string				$skill_id (human readable id)
	 * @param 	string				$skill
	 * @return	boolean
	 */
	public function addSkill($skill_id, $skill)
	{
		// checks if record exists
		$this->db->where('skill_id', $skill_id);
		$this->db->or_where('skill_id', $skill_id);
		$this->db->limit(1);

		$q = $this->db->get('skills');

		if ($q->num_rows()) return FALSE;

		//insert data
		$this->db->insert('skills', array('skill_id' => $skill_id, 'skill' => $skill));

		return TRUE;
	}

	/**
	 * Deletes a skill in skills table
	 *
	 * @param 	string				$skill_id (human readable id)
	 * @return	void
	 */
	public function deleteSkill($skill_id)
	{
		$this->db->where('skill_id', $skill_id);
		$this->db->limit(1);
		$this->db->delete('skills');
	}


	/**
	 * Deletes a skill group in skills_groups table
	 *
	 * @param 	string	$group_name
	 * @return	void
	 */
	public function deleteSkillsGroup($group_name)
	{
		$this->db->where('name', $group_name);
		$this->db->limit(1);
		$this->db->delete('skills_groups');
	}

	/**
	 * Adds a skill group in skills_groups table
	 *
	 * @param 	string	$group_name
	 * @return	void
	 */
	public function addSkillsGroup($group_name)
	{
		// checks if record exists
		$this->db->where('name', $group_name);
		$this->db->limit(1);
		$q = $this->db->get('skills_groups');

		if ($q->num_rows()) return FALSE;

		//insert data
		$this->db->insert('skills_groups', array('name' => $group_name));

		return TRUE;
	}

	/**
	 * Returns all Skills groups
	 *
	 * @return	object
	 */
	public function getAllSkillsGroups()
	{
  		//$this->db->select('name');
		$this->db->order_by('name');
		$query = $this->db->get('skills_groups');

  		return $query->result();
	}
}
?>
