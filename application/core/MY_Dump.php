<?php

	class MY_Dump extends CI_Loader
	{
		/**
        * Facilitates debugging by dumping contents of variable
        * to browser.
        */
       public function dump($variable)
       {
           require('../views/dump.php');
           exit;
       }
	}

?>
