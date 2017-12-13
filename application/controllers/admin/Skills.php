<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Skills extends MY_AdminController {

	function __construct()
	{
		parent::__construct();


	}

	public function index($action = FALSE)
	{
		// POST
		if($action)
		{
			if($action === 'add_skill')
			{
				$this->Skills_model->addSkill($this->input->post('skill_id'), $this->input->post('skill'), $this->input->post('skills_group'));
			}
			elseif ($action === 'del_skill')
			{
				$this->Skills_model->deleteSkill($this->input->post('skill_id'));
			}
			redirect('/admin/skills');
		}


		// GET
		$this->data['skills'] = $this->Skills_model->getAllSkills();
		$this->data['skills_groups'] = $this->Skills_model->getAllSkillsGroups();
		$this->data['skills_groups_array'] = $this->Skills_model->getAllSkillsGroupsArray();
		$this->load->template('admin/skills', $this->data);
	}

	public function groups($action = FALSE)
	{
		// POST
		if($action)
		{
			if($action === 'add_skills_group')
			{
				$this->Skills_model->addSkillsGroup($this->input->post('skills_group'));
			}
			elseif ($action === 'del_skills_group')
			{
				$this->Skills_model->deleteSkillsGroup($this->input->post('skills_group'));
			}
			redirect('/admin/skills/groups');
		}

		// GET
		$this->data['skills_groups'] = $this->Skills_model->getAllSkillsGroups();
		$this->load->template('admin/skills_groups', $this->data);
	}
}
