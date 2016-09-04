<?php
Class Gallery_model extends CI_Model
{

	public function getProjectsGalleryBy($wheres = array(), $offset = 0, $limit = 0)
	{

		$this->db->select("CONCAT('/assets/', file_path, file_name) as file,
									CONCAT('/assets/', file_path, 'thumb_', file_name) as thumbnail,
									RIGHT(file_name, 3) as extension,
									school_year,
									name, CONCAT(LEFT(last_name, 1), '.') as last_name, project_name", FALSE);

		$this->db->from('submitted');
		$this->db->join('users', 'users.id = user_id', 'left');
		$this->db->join('projects', 'projects.id = submitted.project_id', 'left');

		$this->db->where("file_name <> ''", NULL, FALSE);
		$this->db->limit($limit);
		$this->db->offset($offset);

		foreach ($wheres as $where => $criteria)
		{
			if($criteria) $this->db->where($where, $criteria);
		}

		$this->db->order_by('project_id', 'DESC');
		$this->db->order_by('name', 'ASC');
		$this->db->order_by('school_year', 'DESC');

		return $this->db->get()->result();
	}


}
?>
