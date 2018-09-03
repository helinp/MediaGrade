<?php
/**
* Short description for class
*
* Long description for class (if any)...
*
*/

Class Assessment_model extends CI_Model
{
	/**
	* Assessment id
	*
	* @var integer
	*/
	public $id;

	/**
	* @var string
	*/
	public $skills_group;

	/**
	* @var string
	*/
	public $criterion;

	/**
	* @var string
	*/
	public $cursor;

	/**
	* @var int
	*/
	public $max_vote;

	/**
	* @var string
	*/
	public $grading_type;


	/**
	* Returns assessement grid for given project
	*
	* @param integer $project_id
	* @return object
	* @todo use querybuilder
	*/
	public function getAssessmentsByProjectId($project_id)
	{
		$sql ="	SELECT assessments.id, assessments.skills_group, criterion, `cursor`, max_vote, achievement_id, grading_type, skills.skill_id, skill AS skill_description
		FROM projects_assessments
		LEFT JOIN assessments
		ON projects_assessments.assessment_id = assessments.id
		LEFT JOIN skills
		ON skills.id = assessments.skill_id
		WHERE project_id = ?
		ORDER BY skills_group, assessments.id ";

		$query = $this->db->query($sql, array($project_id));
		$assessments = $query->result();

		if( ! $assessments) return array(new Assessment_model);

		return $assessments;
	}

	public function getCriteriaFromProjectId($project_id)
	{
		$this->db->distinct();
		$this->db->from('projects_assessments');
		$this->db->join('assessments', 'projects_assessments.assessment_id = assessments.id');
		$this->db->select('criterion');
		$this->db->where('project_id', $project_id);
		$this->db->order_by('criterion');

		return $this->db->get()->result();
	}

	/**
	* Returns group skills used in a given project
	*
	* @param integer $project_id
	* @return object
	*/
	public function getSkillsGroupByProject($project_id)
	{
		$this->db->select("skills_group, SUM(max_vote) as max_vote", FALSE);
		$this->db->from('assessments');

		$assessment_ids = $this->_getAssessmentsIdByProject($project_id);

		foreach ($assessment_ids as $assessment_id)
		{
			$this->db->or_where('id', $assessment_id);
		}

		$this->db->group_by('skills_group');
		$this->db->order_by('skills_group');

		return $this->db->get()->result();
	}


	/**
	* Returns all assessment id from given project
	*
	* @param integer $project_id
	* @return array
	*/
	private function _getAssessmentsIdByProject($project_id)
	{
		$sql ="SELECT assessment_id
		FROM projects_assessments
		WHERE project_id = ?";

		$query = $this->db->query($sql, array($project_id));
		$results = $query->result();

		// get unidimensional array from result
		$assessment_ids = array();
		foreach ($results as $row)
		{
			$assessment_ids[] = $row->assessment_id;
		}

		return $assessment_ids;
	}

	/**
	* Returns self assessments questions used in a given project
	*
	* @param integer $project_id
	* @return object
	*/
	public function getSelfAssessmentIdsByProject($project_id)
	{
		$this->db->select('self_assessment_ids');
		$result = $this->db->get_where('projects', array('id' => $project_id), 1)->row();

		if( ! $result) return new Assessment_model;

		return explode(',', $result->self_assessment_ids);
	}

	/**
	* Returns all self-assessment questions in DB
	*
	* @return object
	*/
	public function getAllSelfAssessments()
	{
		$this->db->select('id, question');
		return $this->db->get('self_assessments')->result();
	}

	/**
	* Returns self assessments questions from his ID
	*
	* @param integer $self_assessments_id
	* @return object
	*/
	public function getSelfAssessmentFromId($self_assessments_id)
	{
		$this->db->select('question');
		$result = $this->db->get_where('self_assessments', array('id' => $self_assessments_id), 1)->row();
		if($result)
		{
			return $result->question;
		}
		else
		{
			return FALSE;
		}
	}

	// saves assessments
	public function updateAssessment($assessment)
	{
		$this->db->where('id', $assessment['id']);
		return $this->db->update('assessments', $assessment);
	}

	public function addAssessment($assessment)
	{
		$this->db->insert('assessments', $assessment);
		$assessment_id = $this->db->insert_id();

		return $assessment_id;
	}


	/**
	* Add a row into projects_assessments table
	*
	* @param 	integer		$project_id
	* @param 	integer		$assessment_id
	* @return	integer
	*/
	public function addProjects_Assessments($project_id, $assessment_id)
	{
		$data = array('project_id' => $project_id, 'assessment_id' => $assessment_id);

		// checks if record exists
		$q = $this->db->get_where('projects_assessments', $data, 1);

		if( ! $q->row())
		{
			$this->db->insert('projects_assessments', $data);
			return $this->db->insert_id();
		}
		else
		{
			return $q->row('id');
		}
	}

}
?>
