<?php
Class Users_model extends CI_Model
{

	/**
	 * Set session with user data
	 *
	 * @todo checkUserPassword and setSession should be in controller flow
	 * @return	boolean
	 */
    public function login($username, $password)
    {
        if($this->checkUserPassword($username, $password))
        {
            $this->db->select('id, name, last_name, username, email, class, role');
            $this->db->where('username', $username);
            $this->db->limit(1);

            $result = $this->db->get('users')->row();

			if(CONSULTATION_VERSION && $result->role === 'admin')
			{
				return FALSE;
			}

            $this->setSession($result);
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

	/**
	 * Update session data
	 *
	 * @return	void
	 */
    public function updateSession()
    {
        $this->db->select('name, last_name, username, email, class, role, picture, motto');
        $this->db->where('id', $this->session->id);
        $this->db->limit(1);

        $result = $this->db->get('users')->row();
        $this->setSession($result);
    }

	/**
	 * Compare a given password hash with user's hash in database
	 *
	 * @param	string	$username
	 * @param 	string	$password
	 * @param 	string	$col = 'username'
	 * @return	boolean
	 */
    protected function checkUserPassword($username, $password, $col = 'username')
    {
        $this->db->select('password, username');
        $this->db->from('users');
        $this->db->where($col, $username);
        $result = $this->db->get()->row();

        if( ! $result) return FALSE;
        return(password_verify($password, $result->password));
    }

	/**
	 * Returns user email from id
	 *
	 * @param	integer	$user_id
	 * @return	mixed
	 */
    public function getEmailFromUserId($user_id)
    {
        $this->db->select('email');
        $this->db->from('users');
        $this->db->where('id', $user_id);
        $result = $this->db->get()->row();

        if($result) return $result->email;
        return FALSE;
    }

	/**
	 * Set session from given data
	 *
	 * @param	array	$data
	 * @return	void
	 */
    private function setSession($data)
    {
        foreach($data as $key => $row)
        {
            $this->session->set_userdata($key, $row);
        }
        $this->session->set_userdata('logged_in', TRUE);
        $avatar = $this->getUserAvatar();
        $this->session->set_userdata('avatar', $avatar);

        $this->session->unset_userdata('password');
    }

	/**
	 * Returns user data from DB
	 *
	 * @param	integer 	$user_id = $this->session->id;
	 * @return	object
	 */
    public function getUserInformations($user_id = FALSE)
    {
        if ( ! $user_id) $user_id = $this->session->id;

		$query = $this->db->get_where('users', array('id' => $user_id), 1);

        return $query->row();;
    }

	/**
	 * Returns user avatar path from DB
	 *
	 * @param	integer 	$user_id = $this->session->id;
	 * @return	object
	 */
    public function getUserAvatar($user_id = FALSE)
    {
        if (!$user_id) $user_id = $this->session->id;

		$this->db->select('picture');
		$query = $this->db->get_where('users', array('id' => $user_id), 1);
        $result = $query->row('picture');

        return $result;
    }

	/**
	 * Returns user peferences from DB
	 *
	 * @param	string 		$type
	 * @param	integer 	$user_id = $this->session->id;
	 * @return	array
	 */
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

	/**
	 * Redirects user to login page if not logged in
	 *
	 * @return	void
	 */
    public function loginCheck()
    {
        if ( ! $this->session->logged_in) redirect('login');
    }

	/**
	 * Redirects user to main page if not admin
	 *
	 * @return	void
	 */
    public function adminCheck()
    {
        if ($this->session->role != 'admin') redirect('/');
    }

	/**
	 * Returns all user by class
	 *
	 * @param 	string	$role = 'student',
	 * @param	string	$class = FALSE
	 * @return	array[object]
	 * TODO ALL getAllUsers* method should return the same output, $unsorted is a workaround
	 * TODO $role is useless
	 */
    public function getAllUsersByClass($role = 'student', $class = FALSE, $unsorted = FALSE)
    {
        // Query
        $this->db->select('id, username, role, name, last_name, class, email, picture, motto');
        $this->db->where('role', $role);
		$this->db->order_by('class', 'ASC');

        if ($class) $this->db->where('class', $class);

        $this->db->order_by('class, last_name');
        $results = $this->db->get('users')->result();

		if($unsorted)
		{
			return $results;
		}

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

	/**
	 * Returns all users
	 *
	 * @param 	string	$role = 'student',
	 * @param	string	$class = FALSE
	 * @return	array[object]
	 */
	public function getAllUsers($role = 'student', $class = FALSE)
	{
		// Query
		$this->db->select('id, username, role, name, last_name, class, email, picture, motto');
		$this->db->where('role', $role);
		$this->db->order_by('class', 'ASC');
		$this->db->order_by('last_name', 'ASC');

		if ($class) $this->db->where('class', $class);

		$this->db->order_by('class, last_name');
		$results = $this->db->get('users')->result();

		return $results;
	}
}

?>
