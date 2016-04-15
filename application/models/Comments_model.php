<?php
Class Comments_model extends CI_Model
{
	public function getAssessmentComment($user_id, $project_id)
    {
        $sql = "SELECT comment FROM comments WHERE user_id = ? AND project_id = ? LIMIT 1";

        $query = $this->db->query($sql, array($user_id, $project_id));

		$results = $query->row();

        if($results) return $results->comment;

		return FALSE;
    }
}
?>
