<?php
Class Achievements_model extends CI_Model
{

	/**
	 * Returns all achievements from table
	 *
	 * @return	array
	 */
	public function getAllAchievements()
	{
		$this->db->order_by('name', 'ASC');
		$this->db->order_by('star', 'ASC');
	//	$this->db->group_by('name');
		return $this->db->from('achievements')->get()->result();
	}

	/**
	 * Returns all achievements from table
	 *
	 * @return	array
	 */
	public function getAllFirstAchievements()
	{
		$this->db->order_by('name', 'ASC');
		$this->db->order_by('star', 'ASC');

		$this->db->group_by('name');
		return $this->db->from('achievements')->get()->result();
	}

	/**
	 *
	 *
	 * @param 	int		$student_id
	 * @return	boolean
	 */
	public function getAllAchievementsByStudent($student_id = FALSE)
	{
		if ( ! $student_id) $student_id = $this->session->id;

		$this->db->distinct();
		$this->db->from('achievements');
		$this->db->join('achievements_student', 'achievements_student.achievement_id = achievements.id');
		$this->db->where('student_id', $student_id);
		$this->db->order_by('date', 'DESC');
		return $this->db->get()->result();
	}

	public function getAllUnrewardedAchievementsByStudent($student_id = FALSE, $class = FALSE)
	{
		if ( ! $student_id) $student_id = $this->session->id;
		if ( ! $class) $class = $this->session->class;

		$class_achievements = $this->getAllAchievementsByClass($class, $group_achievements = FALSE);
		$student_achievements = $this->getAllAchievementsByStudent();

		// compare array_sort
		// from https://stackoverflow.com/questions/16135311/compare-two-multidimensional-arrays-then-create-array-of-only-unique
		$unrewarded = array();

		foreach($class_achievements as $class)
		{
			$got = FALSE;

		  	foreach($student_achievements as $student_ach)
			{
				//dump($student_ach);
			    if($student_ach->achievement_id === $class->achievement_id)
				{
					$got = TRUE;
				}
			}

			if($got === FALSE) $unrewarded[] = $class;
		}

		return $unrewarded;
	}

	/**
	 *
	 *
	 * @param 	int		$student_id
	 * @return	boolean
	 */
	public function getAllAchievementsByProject($project_id = FALSE, $group_achievements = FALSE)
	{
		$this->db->distinct();
		$this->db->select('achievement_id, achievements.name, achievements.star, achievements.icon, project_id, assessment_id, description');
		$this->db->from('projects_assessments');
		$this->db->join('assessments', 'assessments.id = projects_assessments.assessment_id');
		$this->db->join('achievements', 'achievements.id = assessments.achievement_id');

		if($project_id !== FALSE)
		{
			$this->db->where('project_id', $project_id);
		}

		if($group_achievements)
		{
			$this->db->group_by('project_id');
			$this->db->group_by('achievement_id');
		}

		$this->db->order_by('achievement_id');
		return $this->db->get()->result();
	}

	public function getAllAchievementsByClass($class, $group_achievements = FALSE)
	{
		$this->db->distinct();
		$this->db->select('achievement_id, achievements.name, achievements.star, achievements.icon, project_id, assessment_id, description');
		$this->db->from('projects_assessments');
		$this->db->join('assessments', 'assessments.id = projects_assessments.assessment_id');
		$this->db->join('achievements', 'achievements.id = assessments.achievement_id');
		$this->db->join('projects', 'projects_assessments.project_id = projects.id');

		$this->db->where('class', $class);

		if($group_achievements)
		{
			$this->db->group_by('project_id');
		}

		$this->db->group_by('achievement_id');
		$this->db->order_by('project_id', 'ASC');
		$this->db->order_by('achievements.id', 'DESC');
		return $this->db->get()->result();
	}

	public function add($data)
	{
		// insert in DB
		$this->db->insert('achievements', $data);
		return TRUE;
	}

	public function update($data)
	{
		// update DB
		$this->db->where('id', $data['id']);
		$this->db->update('achievements', $data);
		return TRUE;
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->limit(1);
		$this->db->delete('achievements');
		return TRUE;
	}


	public function award($student_id, $achievement_id)
	{
		$this->db->from('achievements_student');

		// check if entry already exists
		$this->db->where(array('student_id' => $student_id, 'achievement_id' => $achievement_id));
		$check = $this->db->get()->result();

		if(empty($check))
		{
			$this->db->insert('achievements_student', array('student_id' => $student_id, 'achievement_id' => $achievement_id));
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 *	Returns true if percentage > 79 AND $count >
	 *
	 * @param 	int		$type formative | certifivative
	 * @return	boolean
	 */
	public function isEligible($percentage, $count, $type)
	{
		if($percentage > 79)
		{
			if(strtolower($type) === strtolower(LABEL_ASSESSMENT_CERTIFIED) && $count > 0)
			{
				return TRUE;
			}
			elseif($count > 2)
			{
				return TRUE;
			}
		}
		else
		{
			return FALSE;
		}

	}

}
?>
