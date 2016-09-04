<?php
Class Welcome_model extends CI_Model
{
	public function getWelcomeMessage($interpret = TRUE)
	{
		$this->load->helper('message_var');

		$this->db->where('type', 'welcome_message');
		$result = $this->db->get('config', 1);

		if($result->row('content'))
		{
			if($interpret) return convert_user_var($result->row('content'));
			else return $result->row('content');
			
		}
		else
		{
			return false;
		}
	}

	public function saveWelcomeMessage($message)
	{
		$this->db->set('content', $message);
		$this->db->where('type', 'welcome_message');
		$this->db->update('config');
	}
}
?>
