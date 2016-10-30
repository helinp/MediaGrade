<?php
Class Gallery_model extends CI_Model
{

	/**
	 * Returns submitted projects
	 *
	 * @param 	string[]	$wheres		where conditions
	 * @param 	integer		$offset
	 * @param 	integer		$limit
	 * @return	object
	 * @todo uses data from FilesFormat model in calling arg $wheres
	 */
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

        foreach ($wheres as $where => $criteria)
		{
			if($criteria) $this->db->where($where, $criteria);
		}

		$this->db->where("file_name <> ''", NULL, FALSE);

        $this->db->where("extension", "jpg");
        $this->db->or_where("extension", "gif");
        $this->db->or_where("extension", "mov");
        $this->db->or_where("extension", "mp3");
        $this->db->or_where("extension", "wav");

		$this->db->limit($limit);
		$this->db->offset($offset);

		$this->db->order_by('project_id', 'DESC');
		$this->db->order_by('name', 'ASC');
		$this->db->order_by('school_year', 'DESC');

		return $this->db->get()->result();
	}

}
?>
