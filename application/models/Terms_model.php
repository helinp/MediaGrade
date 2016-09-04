<?php
Class Terms_model extends CI_Model
{
	public function getAllTerms()
	{
		/*$this->db->where('type', 'terms');
		$row = $this->db->get('config', 1);

		return explode(',', $row->row('content'));*/
		$this->db->select('name');
		$this->db->order_by('id');
		$q = $this->db->get('terms');

		$array = array();
		foreach($q->result() as $row)
		{
			$array[] = $row->name;
		}

		return $array;

	}

	public function addTerm($term)
	{
		// checks if record exists
		$this->db->where('name', $term);
		$this->db->limit(1);

		$q = $this->db->get('terms');

		if ($q->num_rows()) return FALSE;

		//insert data
		$this->db->insert('terms', array('name' => $term));

		return TRUE;
	}

	public function deleteTerm($term)
	{
		$this->db->where('name', $term);
		$this->db->limit(1);
		$this->db->delete('terms');
	}

}
?>
