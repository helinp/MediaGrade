<?php
Class UsersManager_model extends Users_model
{

	/**
	* Changes user password
	*
	* @param $user_id = $this->session->id
	* @param $current_password
	* @param $new_password
	* @param $new_password_confirmation
	*
	* @return boolean
	*/
	public function changePassword($user_id = FALSE, $current_password, $new_password, $new_password_confirmation)
	{
		if ( ! $user_id) $user_id = $this->session->id;

		// check current password
		$check = parent::checkUserPassword($user_id, $current_password, 'id');
		if( ! $check) show_error(_('Le mot de passe d\'origine n\'est pas valide'));

		// check new password
		if($new_password !== $new_password_confirmation)
		show_error(_('Les mots de passe ne correspondent pas'));

		if( ! empty($errors = $this->checkPasswordStrenght($new_password)))
		show_error($errors);

		$hash = password_hash($new_password, PASSWORD_DEFAULT);

		$data = array(
			'password' => $hash
		);
		$this->db->where('id', $user_id);
		$this->db->update('users', $data);

		return TRUE;
	}

	/**
	* Returns strenght of a given password
	*
	* @param 	string	$pwd
	* @return	string
	*/
	public function checkPasswordStrenght($pwd)
	{
		$errors = NULL;

		if (strlen($pwd) < 8)
		{
			$errors .= _('8 caractères minimum') . ', ';
		}

		if (!preg_match("#[0-9]+#", $pwd))
		{
			$errors .= _('au moins un chiffre.') . ', ';
		}

		if (!preg_match("#[a-z]+#", $pwd))
		{
			$errors .= _('au moins une minuscule') . ', ';
		}

		if (!preg_match("#[A-Z]+#", $pwd))
		{
			$errors .= _('au moins une majuscule') . ', ';
		}

		if( ! $errors) return NULL;
		return _('Le mot de passe doit comporter') . ' ' . $this->_strReplaceLast(rtrim($errors, ', '), ', ', ' ' . _('et') . ' ') . ' .';
	}


	/**
	* Replace last found needle by a given string
	*
	* @param 	string	$str
	* @param 	string	$search
	* @param 	string	$replace
	*
	* @return	string
	*/
	private function _strReplaceLast($str, $search, $replace)
	{
		if(($pos = strrpos($str, $search)) !== FALSE)
		{
			$search_length = strlen($search);
			$str = substr_replace($str, $replace, $pos, $search_length);
		}
		return $str;
	}


	/**
	* Add user in DB
	*
	* @param 	array	$data
	*
	* @return	boolean
	*/
	public function addUser($data = array())
	{
		return $this->ion_auth->register($data['username'], $data['password'], $data['email'], $data, $data['group_id']);
	}

	/**
	* Update user data in DB
	*
	* @param 	array	$data
	*
	* @return	boolean
	*/
	public function updateUser($user_id, $data = array())
	{
		 $this->ion_auth->update($user_id, $data);
	}

	public function messages()
	{
		 return $this->ion_auth->messages();
	}

	public function delUser($user_id)
	{
		return $this->ion_auth->delete_user($user_id);
	}

	/**
	* Update user email in DB
	*
	* @param 	integer		$user_id = $this->session->id
	* @param 	string		$email
	*
	* @return	void
	*/
	public function changeEmail($user_id = FALSE, $email)
	{
		if ( ! $user_id) $user_id = $this->session->id;

		$data = array(
			'id' => $user_id,
			'email' => $email
		);
		$this->db->where('id', $user_id);
		$this->db->update('users', $data);
	}

	/**
	* Update user motto in DB
	*
	* @param 	integer		$user_id = $this->session->id
	* @param 	string		$email
	*
	* @return	void
	*/
	public function changeMotto($user_id = FALSE, $motto)
	{
		if ( ! $user_id) $user_id = $this->session->id;

		$data = array(
			'id' => $user_id,
			'motto' => $motto
		);
		$this->db->where('id', $user_id);
		$this->db->update('users', $data);
	}

	/**
	* Update/add user email preferences in DB
	*
	* @param 	integer		$user_id = $this->session->id
	* @param 	array		$preferences
	*
	* @return	void
	*/
	public function changeEmailPreferences($user_id = FALSE, $preferences = array())
	{
		if ( ! $user_id) $user_id = $this->session->id;

		// process non checked inputs
		if(!isset($preferences['submit_confirmation'])) $preferences['submit_confirmation'] = FALSE;
		if(!isset($preferences['assessment_confirmation'])) $preferences['assessment_confirmation'] = FALSE;

		unset($preferences['change_mail_preferences']);

		$serialized = serialize($preferences);

		// prepare array
		$data = array(
			'user_id' => $user_id,
			'data' => $serialized,
			'type' => 'email'
		);
		$where = array(
			'user_id' => $user_id,
			'type' => 'email'
		);

		// checks if reocrd exists
		$q = $this->db->get_where('users_config', $where, 1);

		// if TRUE, update
		if ($q->num_rows() > 0)
		{
			$this->db->where($where);
			$this->db->update('users_config', $data);
		}
		// else insert
		else
		{
			$this->db->insert('users_config', $data);
		}

	}

	/**
	* Update/add user's avatar path in DB
	*
	* @param 	integer		$user_id = $this->session->id
	* @param 	string		$file_path
	*
	* @todo replace by generic function saveUserConfig($user_id, $data, $data_type) + change table name
	*
	* @return	void
	*/
	public function saveAvatar($user_id = FALSE, $file_path)
	{
		if ( ! $user_id) $user_id = $this->session->id;
		$this->db->where('id', $user_id);
		$this->db->update('users', array('picture' => $file_path));
	}
}
?>
