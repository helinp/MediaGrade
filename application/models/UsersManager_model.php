<?php
Class UsersManager_model extends Users_model
{

    public function changePassword($user_id = false, $password, $new_password)
    {
        if (!$user_id) $user_id = $this->session->id;

        $check = $this->checkUserPassword($user_id, $password);

        if(!$check) return false;

        $hash = crypt($new_password, $user_id);

        $data = array(
                'id' => $user_id,
                'password' => $hash
        );
        $this->db->where('id', $user_id);
        $this->db->replace('users', $data);

        return true;
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
            $data['password'] = crypt($data['password']);

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
            if(isset($data['password'])) $data['password'] = crypt($data['password']);

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
