<?php
class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->Users_model->loginCheck();

		if(config_item('mode') === 'development')
		{
			$this->output->enable_profiler(TRUE);
		}
	}

}

class MY_AdminController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->Users_model->loginCheck();
		$this->Users_model->adminCheck();

		if($this->config->item('mode') === 'development')
		{
			$this->output->enable_profiler(TRUE);
		}
	}

}
?>
