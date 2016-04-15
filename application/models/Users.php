<?php
Class Users extends CI_Model
{
 public function login($username, $password)
 {
   $query = $this -> db -> query("SELECT id, name, last_name, username, password, class, role FROM users WHERE username = ? LIMIT 1", $username);

   $check = crypt($password, $query->row()->password);

   if($check === $query->row()->password)
   {
     $this->setSession($query);
     return true;
   }
   else
   {
     return false;
   }
 }

 private function setSession($data)
 {
	$user_data = array();

	foreach($data->result_object[0] as $key => $row)
	{
		$this->session->set_userdata($key, $row);
	}

	$this->session->unset_userdata('password');
 }

 private function getUserInformations($user_id = false)
 {

  if (!$user_id) $user_id = $this->session->id;

	$query = "SELECT * FROM users WHERE id = ? LIMIT 1";

  $query = $this->db->query($sql, array($user_id));

  $result = $query->result();

  return $result;
 }

}
?>
