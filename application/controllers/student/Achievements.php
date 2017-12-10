<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Achievements extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model','',TRUE);
		$this->Users_model->loginCheck();

        $this->load->model('Achievements_model','',TRUE);
    }


    function index()
	{
		$this->data['achievements'] = $this->Achievements_model->getAllAchievementsByStudent();
		$this->data['all_unrewarded_achievements'] = $this->Achievements_model->getAllUnrewardedAchievementsByStudent();

		$this->load->template('student/achievements', $this->data, FALSE);

	}


}

?>
