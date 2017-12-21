<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Skills extends MY_AdminController {

	function __construct()
	{
		parent::__construct();

		$submenu[] = array('title' => 'Compétences', 'url' => '/admin/skills');
		$submenu[] = array('title' => 'Pôles de compétences', 'url' => '/admin/skills/groups');
		$this->data['submenu'] = $submenu;
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
		}

		// GET
		$this->data['page_title'] = 'Gestion des compétences';
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
		$this->data['page_title'] = 'Gestion des pôles de compétences';
		$this->data['skills_groups'] = $this->Skills_model->getAllSkillsGroups();
		$this->load->template('admin/skills_groups', $this->data);
	}
}
