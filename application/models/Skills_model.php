<?php
Class Skills_model extends CI_Model
{

	public $skill_id;
	public $skill_group;
	public $skill;


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


	public function getAllSkillsByProjects($project_id = FALSE, $only_id = FALSE)
	{
		if ( ! $project_id) return FALSE;

		$sql ="SELECT skill_ids FROM projects where id = ? LIMIT 1";
		$query = $this->db->query($sql, array($project_id));
		if(!$result = $query->row()) return new Skills_model;

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

	public function getAllSkills()
	{
		$sql ="SELECT skill, skill_group, skill_id FROM skills ORDER BY skill_id ASC";

		$query = $this->db->query($sql);

		if($query)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}

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

	public function deleteSkill($skill_id)
	{
		$this->db->where('skill_id', $skill_id);
		$this->db->limit(1);
		$this->db->delete('skills');
	}



	public function addToSkillsGroup()
	{
		// TODO
	}

	public function removeFromSkillsGroup()
	{
		// TODO
	}

	public function deleteSkillsGroup($group_name)
	{
		$this->db->where('name', $group_name);
		$this->db->limit(1);
		$this->db->delete('skills_groups');
	}

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

	public function getAllSkillsGroups()
	{
  		//$this->db->select('name');
		$this->db->order_by('name');
		$query = $this->db->get('skills_groups');

  		return $query->result();
	}
}
?>
