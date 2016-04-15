<?php
Class Welcome_model extends CI_Model
{
	public function getWelcomeMessage($class = false)
	{
		$this->load->helper('message_var');

		$sql = "SELECT content FROM config WHERE type = 'welcome_message' LIMIT 1";

		$query = $this->db->query($sql);

		if($query)
		{
			return convert_user_var($query->row('content'));
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
