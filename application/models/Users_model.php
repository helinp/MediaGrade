<?php
Class Users_model extends CI_Model
{
 public function login($username, $password)
 {
     $check = $this->checkUserPassword($username, $password);
     $query = $this->db->query("SELECT id, name, last_name, username, email, class, role FROM users WHERE username = ? LIMIT 1", $username);
     $query->row();


   if($check)
   {
      $this->session->set_userdata('logged_in', true);

      $this->setSession($query);
      $avatar = $this->getUserAvatar();
      $this->session->set_userdata('avatar', $avatar);
     return true;
   }
   else
   {
     return false;
   }
 }

 protected function checkUserPassword($username, $password)
 {
     $query = $this -> db -> query("SELECT password FROM users WHERE username = ? LIMIT 1", $username);

     return (crypt($password, $query->row()->password) === $query->row()->password);
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

 public function getUserInformations($user_id = false)
 {

    if (!$user_id) $user_id = $this->session->id;

  	$sql = "SELECT * FROM users WHERE id = ? LIMIT 1";

    $query = $this->db->query($sql, array($user_id));

    $result = $query->row();

    return $result;
 }

 public function getUserAvatar($user_id = false)
 {

    if (!$user_id) $user_id = $this->session->id;

    $sql = "SELECT data FROM users_config WHERE type = 'avatar' AND user_id = ? LIMIT 1";

    $query = $this->db->query($sql, array($user_id));

    $result = $query->row('data');

    return $result;
 }


 public function getUserPreferences($user_id = false, $type)
 {

    if (!$user_id) $user_id = $this->session->id;

    $sql = "SELECT * FROM users_config WHERE user_id = ? AND type = ?";

    // todo if no result
    $query = $this->db->query($sql, array($user_id, $type));

    if(!$query->row()) return array();
    $result = unserialize($query->row()->data);

    return $result;
 }

  public function loginCheck()
  {
      if ( ! $this->session->logged_in) redirect('login');
  }

  public function adminCheck()
  {
      if ( ! $this->session->role === 'admin') redirect('/');
  }

  public function getAllUsersByClass($role = 'student', $class = FALSE)
  {

     $sql = "SELECT id, username, role, name, last_name, class, email FROM users WHERE role = ?";

     if ($class) $sql.= "AND CLASS = ?";

     $sql .= " ORDER BY class, last_name";

     if ($class) $query = $this->db->query($sql, array($role, $class));
     else $query = $this->db->query($sql, array($role));

     $results = $query->result();

     $sorted_by_class = array();

     foreach($results as $user)
     {
         if( ! isset($sorted_by_class[$user->class]))
         {
            $sorted_by_class[$user->class] = array();
         }
         array_push($sorted_by_class[$user->class], $user);
     }

     return $sorted_by_class;
  }

}

?>
