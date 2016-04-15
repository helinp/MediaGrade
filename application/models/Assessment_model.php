<?php
Class Assessment_model extends CI_Model
{
	public function listAllSkills()
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



	public function listAllSkillsByProjects($project_id = FALSE, $only_id = FALSE)
	{
		if( ! $project_id) return array();

		$sql ="SELECT skill_ids FROM projects where id = ? LIMIT 1";
		$query = $this->db->query($sql, array($project_id));
		$result = $query->row()->skill_ids;

		// return array and only ID
		$skills = explode(',', $result);
		if ($only_id) return $skills;

		// return array WITH names
		$project_skills = array();

		if( ! $skills) return FALSE;

		foreach ($skills as $skill)
		{
			$sql ="SELECT skill, skill_group, skill_id FROM skills WHERE skill_id = ?";
			$query = $this->db->query($sql, array($skill));
			$result = $query->row();
			if ( ! empty($result)) array_push($project_skills, $result);
		}

		return $project_skills;
	}

	public function getAssessmentTable($project_id)
	{
		if( ! $project_id) return array();

		$sql ="	SELECT assessments.id, skills_group, criterion, `cursor`, max_vote
				FROM projects_assessments
					LEFT JOIN assessments
					ON projects_assessments.assessment_id = assessments.id
				WHERE project_id = ?";

		$query = $this->db->query($sql, array($project_id));
		$assessments = $query->result();

		return $assessments;
	}

	public function getSkillsGroupByProject($project_id)
	{
		if( ! $project_id) return array();

		$assessment_ids = $this->getAssessmentsIdByProject($project_id);

		$sql_where = ' WHERE';

		foreach ($assessment_ids as $assessment_id)
		{
			$sql_where .= " id = $assessment_id OR";
		}
		$sql_where = substr($sql_where, 0, -3);

		$sql = 'SELECT skills_group, SUM(max_vote) as max_vote FROM assessments' . $sql_where . ' GROUP BY skills_group';

		$query = $this->db->query($sql);

		return $query->result_array();
	}

	private function getAssessmentsIdByProject($project_id)
	{

		/*$sql ="SELECT assessment_ids FROM projects WHERE id = ? LIMIT 1";

		$query = $this->db->query($sql, array($project_id));

		$assessment_ids = explode(',', $query->row()->assessment_ids);*/

		$sql ="SELECT assessment_id FROM projects_assessments WHERE project_id = ?";

		$query = $this->db->query($sql, array($project_id));
		$results = $query->result();

		// transforms result into unidimensionnal array
		$assessment_ids = array();
		foreach ($results as $row)
		{
			$assessment_ids[] = $row->assessment_id;
		}
		return $assessment_ids;

	}

	public function getSelfAssessmentByProject($project_id, $only_id = FALSE)
	{
		if( ! $project_id) return array();

		$sql ="SELECT self_assessment_ids FROM projects WHERE id = ? LIMIT 1";

		$query = $this->db->query($sql, array($project_id));

		$self_assessment_ids = explode(',', $query->row()->self_assessment_ids);

		if($only_id) return $self_assessment_ids;

		$self_assessment_ids = array();

		foreach ($self_assessment_ids as $self_assessment_id)
		{
			$sql ="SELECT id, question FROM self_assessments WHERE id = ? LIMIT 1";

			$query = $this->db->query($sql, array($self_assessment_id));

			array_push($self_assessment_ids, $query->row());
		}

		return $self_assessment_ids;
	}

	public function listAllSelfAssessments()
	{
		$sql ="SELECT id, question FROM self_assessments";

		$query = $this->db->query($sql);

		return $query->result();;
	}
}
?>
