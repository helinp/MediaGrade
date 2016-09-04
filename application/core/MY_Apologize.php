<?php

	class MY_Messages extends CI_Loader
	{

        public function user_message($message)
        {
            require('application/views/templates/header.php');
            require('application/views/apologize.php');
            require('application/views/templates/footer.php');
            exit;
        }
    }
?>
