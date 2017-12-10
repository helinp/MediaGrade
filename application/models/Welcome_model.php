<?php
Class Welcome_model extends CI_Model
{
	/**
	* Returns Welcome message
	*
	* @param boolean $interpret
	* @return string
	*/
	public function getWelcomeMessage($interpret = TRUE)
	{
		$this->db->where('key', 'welcome_message');
		$result = $this->db->get('config', 1);

		if($result->row('content'))
		{
			if($interpret)
			{
				$this->load->helper('message_var');
				return convert_user_var($result->row('content'));
			}
			else
			{
				return $result->row('content');
			}
		}
		else
		{
			return '';
		}
	}

	/**
	* Saves Welcome message
	*
	* @param string $message
	* @return void
	*/
	public function saveWelcomeMessage($message)
	{
		$this->db->set('data', $message);
		$this->db->where('key', 'welcome_message');
		$this->db->update('config');
	}
}
?>
