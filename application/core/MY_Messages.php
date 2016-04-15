<?php

	class MY_Messages extends CI_Loader
	{

        public function apologize($message)
        {
        	//$this->view('templates/header');
            $this->view('apologize', $message);
        	$this->view('templates/footer');
            die;
        }
    }
?>
