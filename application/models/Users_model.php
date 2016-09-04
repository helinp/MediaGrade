<?php
Class Users_model extends CI_Model
{
    public function login($username, $password)
    {

        if($this->checkUserPassword($username, $password))
        {
            $this->db->select('id, name, last_name, username, email, class, role');
            $this->db->where('username', $username);
            $this->db->limit(1);

            $result = $this->db->get('users')->row();
            $this->setSession($result);

            return true;
        }
        else
        {
            return false;
        }
    }

    public function updateSession()
    {
        $this->db->select('name, last_name, username, email, class, role');
        $this->db->where('id', $this->session->id);
        $this->db->limit(1);

        $result = $this->db->get('users')->row();
        $this->setSession($result);


    }

    protected function checkUserPassword($username, $password, $col = 'username')
    {
        $this->db->select('password, username');
        $this->db->from('users');
        $this->db->where($col, $username);
        $result = $this->db->get()->row();

        if( ! $result) return FALSE;
        return(password_verify($password, $result->password));
    }

    public function getEmailFromUserId($user_id)
    {
        $this->db->select('email');
        $this->db->from('users');
        $this->db->where('id', $user_id);
        $result = $this->db->get()->row();

        if($result) return $result->email;
        return FALSE;
    }

    private function setSession($data)
    {
        $user_data = array();

        foreach($data as $key => $row)
        {
            $this->session->set_userdata($key, $row);
        }
        $this->session->set_userdata('logged_in', true);
        $avatar = $this->getUserAvatar();
        $this->session->set_userdata('avatar', $avatar);

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

    // TODO; Move to own model
    public function getUserPreferences($type, $user_id = FALSE)
    {
        if ( ! $user_id) $user_id = $this->session->id;

        $this->db->select('data');
        $this->db->where('type', $type);
        $this->db->where('user_id', $user_id);

        $result = $this->db->get('users_config', 1)->row();

        if( ! $result) return array();
        return unserialize($result->data);
    }

    public function loginCheck()
    {
        if ( ! $this->session->logged_in) redirect('login');
    }

    public function adminCheck()
    {
        if ($this->session->role != 'admin') redirect('/');
    }

    public function getAllUsersByClass($role = 'student', $class = FALSE)
    {

        // Query
        $this->db->select('id, username, role, name, last_name, class, email');
        $this->db->where('role', $role);

        if ($class) $this->db->where('class', $class);

        $this->db->order_by('class, last_name');
        $results = $this->db->get('users')->result();

        // Array[class] formatting
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
