<?php
Class Skills_model extends CI_Model
{
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

	public function listAllSkillsGroups()
	{
  		//$this->db->select('name');
		$this->db->order_by('name');
		$query = $this->db->get('skills_groups');

  		return $query->result();
	}
}
?>
