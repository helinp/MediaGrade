<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Achievements extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Achievements_model','',TRUE);
	}

	function index()
	{
		$this->data['achievements'] = $this->Achievements_model->getAllAchievementsByStudent();
		$this->data['all_unrewarded_achievements'] = $this->Achievements_model->getAllUnrewardedAchievementsByStudent();
		$this->data['page_title'] = _('Mes badges');
		$this->load->template('student/achievements', $this->data, FALSE);

	}
}

?>
