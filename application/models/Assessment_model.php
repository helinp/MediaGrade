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
	 * Returns assessement grid for given project
	 *
	 * @param integer $project_id
	 * @return object
	 * @todo use querybuilder
	 */
	public function getAssessmentTable($project_id)
	{
		$sql ="	SELECT assessments.id, skills_group, criterion, `cursor`, max_vote
				FROM projects_assessments
				LEFT JOIN assessments
					ON projects_assessments.assessment_id = assessments.id
				WHERE project_id = ?
			    ORDER BY skills_group, assessments.id ";

		$query = $this->db->query($sql, array($project_id));
		$assessments = $query->result();

		if( ! $assessments) return array(new Assessment_model);

		return $assessments;
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
}
?>
