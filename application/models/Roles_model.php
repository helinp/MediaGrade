<?php
Class Roles_model extends CI_Model
{

	function getAllRoles()
	{
		return $this->ion_auth->groups()->result();
	}

	function addRole($name, $description = FALSE)
	{
		// pass the right arguments and it's done
		$new_group_id = $this->ion_auth->create_group($name, $description);

		if( ! $group)
		{
			show_error($this->ion_auth->messages());
		}
		else
		{
			return $new_group_id;
		}
	}

	function updateRole($id, $name, $description = FALSE)
	{
		// pass the right arguments and it's done
		$group_update = $this->ion_auth->update_group($id, $name, $description);

		if( ! $group_update)
		{
			show_error($this->ion_auth->messages());
		}
		else
		{
			return TRUE;
		}
	}

	function deleteRole($id)
	{
		$group_delete = $this->ion_auth->delete_group($id);

		if( ! $group_delete)
		{
			show_error($this->ion_auth->messages());
		}
		else
		{
			return TRUE;
		}
	}

	public function getRole($id)
	{
		return $this->ion_auth->group($id);
	}

	public function addRoleToUser($role_id, $user_id)
	{
		return $this->ion_auth->add_to_group($role_id, $user_id);
	}

	public function RemoveRoleFromUser($role_id, $user_id)
	{
		return $this->ion_auth->remove_from_group($role_id, $user_id);
	}

	public function RemoveAllRolesFromUser($user_id)
	{
		return $this->ion_auth->remove_from_group(FALSE, $user_id);
	}

	public function getRoleIdFromName($name)
	{
		$groups = $this->ion_auth->groups()->result();
		$array_key = array_search($name, array_column($groups, 'name'));

		return @$groups[$array_key]->id;
	}
}

?>
