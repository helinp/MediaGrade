<?php
Class UsersManager_model extends Users_model
{

    /**
     *
     * @param $user_id = $this->session->id
     * @param $current_password
     * @param $new_password
     * @param $new_password_confirmation
     *
     */
    public function changePassword($user_id = false, $current_password, $new_password, $new_password_confirmation)
    {
        if ( ! $user_id) $user_id = $this->session->id;

        // check new password
        if($new_password !== $new_password_confirmation)
            show_error(_('Les mots de passe ne correspondent pas'));
        if( ! empty($errors = $this->checkPasswordStrenght($new_password)))
            show_error($errors);

        // check current password
        $check = parent::checkUserPassword($user_id, $current_password, 'id');
        if( ! $check) show_error(_('Le mot de passe n\'est pas valide'));

        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        //crypt($new_password, $user_id);

        $data = array(
                'password' => $hash
        );
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);

        return true;
    }

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
        return _('Le mot de passe doit comporter') . ' ' . $this->str_replace_last(rtrim($errors, ', '), ', ', ' ' . _('et') . ' ') . ' .';
    }


    private function str_replace_last($str, $search, $replace)
    {
        if(($pos = strrpos($str, $search)) !== false)
        {
            $search_length = strlen($search);
            $str = substr_replace($str, $replace, $pos, $search_length);
        }
        return $str;
    }



    public function addUser($data = array())
    {
        // checks if record already exists in DB
        $where = array(
                'username' => $data['username']
        );

        $this->db->where($where);
        $this->db->limit(1);
        $q = $this->db->get('users');

        // if true, returns error
        if ($q->num_rows() > 0)
        {
            return FALSE;
        }
        // else insert
        else
        {
            // crypt password
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            // insert in DB
            $this->db->insert('users', $data);
        }

        return TRUE;
    }

    public function updateUser($data = array())
    {
        // checks if record already exists in DB
        $where = array(
                'id' => $data['id']
        );

        $this->db->where($where);
        $this->db->limit(1);
        $q = $this->db->get('users');

        // if true, returns error
        if ($q->num_rows() == 0)
        {
            return FALSE;
        }
        // else update
        else
        {

            // unset empty fields
            $data = array_filter($data);

            // crypt pasword
            if(isset($data['password'])) $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            // update DB
            $this->db->where($where);
            $this->db->update('users', $data);
        }

        return TRUE;
    }

    public function delUser($user_id)
    {
        $this->db->where('id', $user_id);
		$this->db->limit(1);
		$this->db->delete('users');

        return TRUE;
    }


    public function changeEmail($user_id = false, $email)
    {
        if (!$user_id) $user_id = $this->session->id;

        $data = array(
                'id' => $user_id,
                'email' => $email
        );
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
    }

    public function changeEmailPreferences($user_id = false, $preferences = array())
    {
        if (!$user_id) $user_id = $this->session->id;

        // process non checked inputs
        if(!isset($preferences['submit_confirmation'])) $preferences['submit_confirmation'] = false;
        if(!isset($preferences['assessment_confirmation'])) $preferences['assessment_confirmation'] = false;

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

        // if true, update
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

    // TODO replace by generic function saveUserConfig($user_id, $data, $data_type) + change table name
    public function saveAvatar($user_id, $file_path)
    {
        if (!$user_id) $user_id = $this->session->id;

        // prepare array
        $data = array(
                'user_id' => $user_id,
                'data' => $file_path,
                'type' => 'avatar'
        );
        $where = array(
                'user_id' => $user_id,
                'type' => 'avatar'
        );

        // checks if reocrd exists
        $this->db->where($where);
        $q = $this->db->get_where('users_config', array('user_id' => $user_id), 1);

        // if true, update
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
}
?>
