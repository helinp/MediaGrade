<?php

	class MY_Loader extends CI_Loader
	{
		public function template($template_name, $vars = array(), $menu = FALSE)
		{

				$this->view('templates/header', $vars);

				// TODO add catch error
				// project and welcome got the same nav
				$template_name_tmp = $template_name;
				if ($template_name == 'projects/welcome'
					|| $template_name == 'projects/instructions'
					|| $template_name == 'projects/results'
					|| $template_name == 'projects/submit')
					 $template_name_tmp = 'projects/projects';

				if($menu) $this->load->view($template_name_tmp . '_nav', $vars);

				$this->view($template_name, $vars);
				$this->view('templates/footer', $vars);

		}

	}

?>
