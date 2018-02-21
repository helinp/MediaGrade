<?php
Class Users_model extends CI_Model
{

	public function logUser($username, $password, $remember = FALSE)
	{
		return $this->ion_auth->login($username, $password, $remember);
	}
	/**
	* Update session data
	*
	* @return	void
	*/
	public function updateSession()
	{
		$user = $this->ion_auth->user()->row();
		unset($user->password);
		unset($user->salt);
		$this->setSession($user);
	}

	/**
	* Returns user email from id
	*
	* @param	integer	$user_id
	* @return	mixed
	*/
	public function getEmailFromUserId($user_id)
	{
		$user = $this->ion_auth->user()->row();
		return $user->email;
	}

	/**
	* Set session from given data
	*
	* @param	array	$data
	* @return	void
	*/
	public function setSession()
	{
		$data = $this->ion_auth->user()->row();

		foreach($data as $key => $row)
		{
			$this->session->set_userdata($key, $row);
		}
		$this->session->set_userdata('logged_in', TRUE);

		$avatar = $this->getUserAvatar();
		$this->session->set_userdata('avatar', $avatar);

		$this->session->set_userdata('class_name', $class_description);

		$this->session->unset_userdata('password');
		$this->session->unset_userdata('salt');
		$this->session->unset_userdata('picture');
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
		$user = $this->ion_auth->user($user_id)->row();
		unset($user->password);
		unset($user->salt);
		return $user;
	}

	/**
	* Returns user avatar path from DB
	*
	* @param	integer 	$user_id = $this->session->id;
	* @return	object
	*/
	public function getUserAvatar($user_id = FALSE)
	{
		if ( ! $user_id) $user_id = $this->session->id;

		$user = $this->ion_auth->user($user_id)->row();
		return $user->picture;
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
		if ( ! $this->ion_auth->logged_in()) redirect('login');
	}

	public function isLoggedIn()
	{
		return $this->ion_auth->logged_in();
	}

	/**
	* Redirects user to main page if not admin
	*
	* @return	void
	*/
	public function adminCheck()
	{
		if ( ! $this->isAdmin()) redirect('/');
	}


	public function isAdmin()
	{
		return $this->ion_auth->in_group('admin');
	}

	/**
	* Returns all user by class
	*
	* @param 	string	$role = 'student',
	* @param	string	$class = FALSE
	* @return	array[object]
	* TODO ALL getAllUsers* methods should ALWAYS return the same output, $unsorted is a workaround
	*/
	public function getAllStudentsSortedByClass($class_id = FALSE)
	{
		$students = $this->getUsersByRole('student');

		// Array[class] format
		$sorted_by_class = array();

		foreach($students as $user)
		{
			if( ! isset($sorted_by_class[$user->class]))
			{
				$sorted_by_class[(int) $user->class] = array();
			}
			array_push($sorted_by_class[$user->class], $user);
		}

		($class_id ? : ksort($sorted_by_class));

		return ($class_id ? array($class_id => $sorted_by_class[$class_id]) : $sorted_by_class);
	}

	public function getAllStudentsByClass($class_id)
	{
		// get role id
		$this->db->join('users_roles', 'users_roles.user_id = users.id', 'left');
		$this->db->join('roles', 'roles.id = users_roles.role_id', 'left');
		$this->db->join('classes', 'classes.id = users.class', 'left');

		$this->db->select('last_name, first_name, users.id as id, email, class, motto, picture, picture AS avatar, classes.name AS class_name');
		$this->db->where('roles.name', 'student');
		$this->db->where('users.class', $class_id);
		$this->db->where('users.active', 1);
		return $this->db->get('users')->result();

	}


	public function CountStudentsByClass($class_id)
	{
		$this->db->where('class', $class_id);
		return $this->db->count_all_results('users');
	}

	/**
	* Returns all users
	*
	* @return	array[object]
	*/
	public function getAllUsers()
	{
		$this->ion_auth->order_by('last_name, first_name', 'ASC');
		return $this->ion_auth->users()->result();
	}

	public function messages()
	{
		 return $this->ion_auth->messages();
	}

	public function getAllStudents()
	{
		return $this->getUsersByRole('student');
	}

	public function getAllTeachers()
	{
		return $this->getUsersByRole('admin');
	}

	public function getAllAdmins()
	{
		return $this->getUsersByRole('admin');
	}

	public function getUsersByRole($group)
	{
		$groups = $this->ion_auth->groups()->result_array();
		$group_key = array_search($group, array_column($groups, 'name'));
		$this->ion_auth->order_by('class, last_name, first_name', 'ASC');
		return $this->ion_auth->users($groups[$group_key])->result();
	}


}

?>
