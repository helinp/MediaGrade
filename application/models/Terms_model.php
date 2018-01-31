<?php
Class Terms_model extends CI_Model
{

	/**
	* Returns all terms from table terms
	*
	* @return	array
	*/
	public function getAll()
	{
		$this->db->order_by('id');
		return $this->db->get('terms')->result();
	}

	public function getTerm($term_id)
	{
		$this->db->where('id', $term_id);
		$this->db->order_by('name');
		return $this->db->get('terms')->row();
	}

	/**
	* Add term, if not already exists, in database
	*
	* @param 	string		$term
	* @return	boolean
	*/
	public function add($term)
	{
		// checks if record exists
		$this->db->where('name', $term);
		$this->db->limit(1);
		$q = $this->db->get('terms');

		// if so do nothing
		if ($q->num_rows())
		{
			return FALSE;
		}
		// else insert data
		else
		{
			$this->db->insert('terms', array('name' => $term));
			return TRUE;
		}
	}

	/**
	* Removes term from database
	*
	* @param 	string		$term
	* @return	void
	*/
	public function delete($term)
	{
		$this->db->where('name', $term);
		$this->db->limit(1);
		$this->db->delete('terms');
	}

}
?>
