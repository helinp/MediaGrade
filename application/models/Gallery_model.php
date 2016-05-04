<?php
Class Gallery_model extends CI_Model
{

	public function getProjectsGallery($user_id = false, $offset = 0)
	{
		$sql = "SELECT  CONCAT('/assets/', file_path, file_name) as file,
					 	CONCAT('/assets/', file_path, 'thumb_', file_name) as thumbnail,
						RIGHT(file_name, 3) as extension,
						name,
						CONCAT(LEFT(last_name, 1), '.') as last_name,
						project_name
				FROM submitted
				LEFT JOIN users
					ON users.id = user_id
				LEFT JOIN projects
					ON projects.id = submitted.project_id
				WHERE file_name IS NOT NULL";

		// user injection protection
		if($offset !== 0) $offset = intval($offset);

		if ($user_id)
		{
				$sql .= " AND user_id = ? ORDER BY submitted.id DESC LIMIT 12 OFFSET $offset";
				$query = $this->db->query($sql, array($user_id));
		}
		else
		{
				$sql .= " ORDER BY submitted.id DESC LIMIT 12 OFFSET $offset";
				$query = $this->db->query($sql);
		}
		return $query->result();
	}

	public function makeOptionArray($table, $col, $user = false)
    {
	    $sql = "SELECT DISTINCT $col, id FROM $table";

			if ($table === 'users') $sql .= " WHERE role <> 'admin'";

	    $sql .= " ORDER BY class ASC, $col ASC";

	    $query = $this->db->query($sql);//, array($col, $table, $col));
	    $results = $query->result();

	    $options = array();

			$options['all'] = _('Tous');

	    $this->load->helper('format');

	    foreach($results as $row)
	    {
	      $option = $row->id;
	    //  $option = substr($option, -5);

	      $options[$option] = $row->$col;

	    }
	    return $options;
  }





/******* WORK IN PROGRESS **********/

	public function sqlFilter($conditions = array())
  {
		$sql = "SELECT CONCAT('/assets/', file_path, file_name) as file,
									CONCAT('/assets/', file_path, 'thumb_', file_name) as thumbnail,
									RIGHT(file_name, 3) as extension,
									name, CONCAT(LEFT(last_name, 1), '.') as last_name, project_name
									FROM submitted";

		$sql .= "	LEFT JOIN users
								ON users.id = user_id
							LEFT JOIN projects
								ON projects.id = submitted.project_id";

      if(sizeof($conditions) > 0) {
        $sql.=' WHERE '. implode(' AND ', $conditions);
      }



      $query = $query = $this->db->query($sql);
      $results = $query->result();

      return $results;
    }

  /**
   * Méthode magique __call() permettant d'appeller une méthode virtuelle
   * du type sortByName(), sortByAge() ou sortByNameAndAge()...
   *
   * @param string $method Nom de la méthode virtuelle appelée
   * @param array $args Tableau des critères de recherche
   * @return array|null $return Tableau des résultats ou NULL
   * @see sortEngine::sort()
   */
  public function __call($method, $args)
  {
    if(preg_match('#^filterBy#i', $method))
    {
      $sort_conditions = str_replace('filterBy', '', $method);
      $sort_criteria = explode('and', $sort_conditions);
      $conditions = array();
      $nb_criteria = sizeof($sort_criteria);

      for($i = 0; $i < $nb_criteria; $i++)
      {
        $conditions[] = strtolower($sort_criteria[$i]) . '="' . $args[$i] . '"';
      }
      return $this->sqlFilter($conditions);
    }
    return null;
  }

}
?>
