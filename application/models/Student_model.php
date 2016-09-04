<?php
Class Student_model extends CI_Model
{

		public function getNotSubmittedProjects($user_id = $this->session->id)
		{
				$this->get('projects')
				$this->join('submitted')
				$this->db->where('')
		}

		public function isProjectSubmittedByUser($project_id, $user_id)


}
?>
