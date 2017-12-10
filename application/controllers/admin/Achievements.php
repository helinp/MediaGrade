<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Achievements extends CI_Controller {

	private $data;

	function __construct()
	{
		parent::__construct();
		$this->load->model('Achievements_model','',TRUE);
	}


	public function index($action = FALSE)
	{
		if($action)
		{
			$data = array(
				'id' 			=> $this->input->post('id'),
				'name' 			=> $this->input->post('name'),
				'description' 	=> $this->input->post('description'),
				'icon' 			=> $this->input->post('icon'),
				'star'			=> $this->input->post('star')
				);

			switch($action)
			{
				case 'add':
					$this->Achievements_model->add($data);
					break;

				case 'update':
					$this->Achievements_model->update($data);
					break;

				case 'delete':
					$this->Achievements_model->delete($data['id']);
					break;

				case 'reward':
					$this->_achievement_reward();
					break;
			}
		}

		// GET
		$this->data['achievements'] = $this->Achievements_model->getAllAchievements();
		$this->load->template('admin/achievements', $this->data);
	}

	/**
	*** TODO NEEDS SEVERE OPTIMIZATION
	**/
	private function _achievement_reward()
	{
		// get all projects with assessment
		$projects_achievements = $this->Achievements_model->getAllAchievementsByProject(FALSE, $group = FALSE);
		$eligible_users = array();
		$results = array();

		// $results[USER_ID][ACHIEVEMENT_ID]
		foreach ($projects_achievements as $achievement)
		{
			$tmp_results = $this->Results_model->getResultsByAssessmentId(FALSE, FALSE, $achievement->assessment_id);

			$i = 0;
			foreach ($tmp_results as $row)
			{
				$results[$row->user_id][$achievement->achievement_id][$row->assessment_type][] = array(	'user_id' => $row->user_id,
																				'achievement_id' => $achievement->achievement_id,
																				'assessment_id' => $achievement->assessment_id,
																				'project_id' => $row->project_id,
																				'max_vote' => $row->max_vote,
																				'user_vote' => $row->user_vote,
																				'assessment_type' => $row->assessment_type,
																				'submitted_time' => @$this->Submit_model->getSubmittedInfosByUserIdAndProjectId($row->user_id, $row->project_id)[0]->raw_time,
																			);
			}
		}

		foreach($results as $res_key => $achievements)
		{
			foreach ($achievements as $ach_key => $achievement)
			{
				foreach ($achievement as $typ_key => $type)
				{
					/**
					** Should be ordered by submission time TODO
					** 	this is a workaround, should be improved ASAP
					**/
					$this->load->helper('functions');
					$results[$res_key][$ach_key][$typ_key] = array_sort($type, 'submitted_time', 'SORT_DESC');
					/**
					** end of workaround
					**/

					$total_max = 0;
					$total_user = 0;
					$count = array();
					$c = 0;

					foreach ($results[$res_key][$ach_key][$typ_key] as $result)
					{
						//dump($result);
						$total_max += $result['max_vote'];
						$total_user += $result['user_vote'];

						$count[$result['project_id']] = TRUE;
					}
					$count = count($count);
					$percentage = @($total_user / $total_max * 100);
					$eligible = $this->Achievements_model->isEligible($percentage, $count, $typ_key);

					/* for dump debug only
					$results[$res_key][$ach_key][$typ_key] = array(
																	'number' 		=> $count,
																	'total_max'		=> $total_max,
																	'total_user'	=> $total_user,
																	'percentage'	=> $percentage,
																	'eligible'		=> $eligible
																	);
					*/

					if($eligible)
					{
						$eligible_users[] = array(
																	'user_id' 			=> $res_key,
																	'achievement_id'	=> $ach_key,
																	);
					}
				}
			}
		}

		foreach ($eligible_users as $u) {
			// finally, awards students
			$this->Achievements_model->award($u['user_id'], $u['achievement_id']);
		}
	}



	public function settings($action = FALSE)
	{
        $this->load->model('System_model','',TRUE);
        $this->data['folder_perms'] = array('/assets/uploads' => $this->System_model->getFolderPerms('/assets/uploads/'));

		$this->load->model('Skills_model','',TRUE);
		$this->load->model('Welcome_model','',TRUE);

		// POST
		if ($action === 'mail_test')
		{
			$this->Email_model->sendObjectMessageToEmail(   $this->input->post('subject'),
															$this->input->post('body'),
															$this->session->email
														);

			redirect('/admin/settings');
		}

		// GET
		$this->data['welcome_message'] = $this->Welcome_model->getWelcomeMessage(FALSE);
        $this->data['disk_space'] = $this->System_model->getUsedDiskSpace();
		$this->load->template('admin/settings', $this->data);
	}

    public function welcome_message($action = FALSE)
	{
		$this->load->model('Welcome_model','',TRUE);

		if ($action === 'update')
		{
			$this->Welcome_model->saveWelcomeMessage($this->input->post('welcome_message'));
		}

		$this->data['welcome_message'] = $this->Welcome_model->getWelcomeMessage(FALSE);

		// GET
		$this->load->template('admin/welcome_message', $this->data);
	}

}
