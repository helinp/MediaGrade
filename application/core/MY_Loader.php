<?php

class MY_Loader extends CI_Loader
{
	public function template($template, $vars = array(), $only_content = FALSE)
	{
		if($only_content)
		{
			$this->view($template, $vars);
		}
		else
		{
			$this->view('templates/header', $vars);
			if($template !== 'login_form') $this->view('templates/aside', $vars);
			$this->view($template, $vars);
			$this->view('templates/footer', $vars);
		}
	}

}

?>
