<?php
Class Periods_model extends CI_Model
{
	public function listAllPeriods()
	{
		/*$this->db->where('type', 'periods');
		$row = $this->db->get('config', 1);

		return explode(',', $row->row('content'));*/
		$this->db->select('name');
		$this->db->order_by('id');
		$q = $this->db->get('periodes');

		$array = array();
		foreach($q->result() as $row)
		{
			$array[] = $row->name;
		}

		return $array;

	}

	public function addPeriode($periode)
	{
		// checks if record exists
		$this->db->where('name', $periode);
		$this->db->limit(1);

		$q = $this->db->get('periodes');

		if ($q->num_rows()) return FALSE;

		//insert data
		$this->db->insert('periodes', array('name' => $periode));

		return TRUE;
	}

	public function deletePeriode($periode)
	{
		$this->db->where('name', $periode);
		$this->db->limit(1);
		$this->db->delete('periodes');
	}

}
?>
