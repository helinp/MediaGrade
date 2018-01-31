<?php
/**
* Future model for getting user and system config
*/

Class Config_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();

	}

	public function getValue($key)
	{
		$this->db->select('value, default_value')
					->from('config')
					->limit(1);

		$result = $this->db->where('key', $key)->get()->row();

		if($result->value)
		{
			return $result->value;
		}
		else
		{
			return $result->default_value;
		}
	}

	public function getdefaultValue($key)
	{
		$this->db->select('default_value')
					->from('config')
					->limit(1);
		return $this->db->where('key', $key)->get()->row('default_value');
	}

	public function setValue($key, $value)
	{
		$this->db->from('config')
					->where('key', $key)
					->limit(1);
		$this->db->update('key', $key);
	}

}
?>
