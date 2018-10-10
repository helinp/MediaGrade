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
	public function getProjectsGalleryBy($wheres = array(), $offset = FALSE, $limit = FALSE)
	{

		if($this->config->item('mode') === 'demo')
		{
			$this->db->select("'https://loremflickr.com/g/640/480/photography?random=1' as file,
			'https://loremflickr.com/g/320/240/photography?random=1' as thumbnail,
			RIGHT(file_name, 3) as extension,
			school_year,
			name, CONCAT(LEFT(last_name, 1), '.') as last_name, project_name", FALSE);

		}
		else
		{
			$this->db->select("CONCAT('/assets/', file_path, file_name) as file,
			CONCAT('/assets/', file_path, 'thumb_', file_name) as thumbnail,
			RIGHT(file_name, 3) as extension,
			school_year,
			first_name, CONCAT(LEFT(last_name, 1), '.') as last_name, project_name", FALSE);
		}

		$this->db->from('submitted');
		$this->db->join('users', 'users.id = user_id', 'left');
		$this->db->join('projects', 'projects.id = submitted.project_id', 'left');

		foreach ($wheres as $where => $criteria)
		{
			if($criteria) $this->db->where($where, $criteria);
		}

		$this->db->where("file_name <> ''", NULL, FALSE);
		$this->db->not_like('file_name', '.pdf');

		if($limit) $this->db->limit($limit);
		if($limit) $this->db->offset($offset);

		$this->db->order_by('project_id', 'DESC');
		$this->db->order_by('first_name', 'ASC');
		$this->db->order_by('school_year', 'DESC');

		return $this->db->get()->result();
	}

}
?>
