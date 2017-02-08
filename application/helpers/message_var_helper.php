<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('convert_user_var'))
{
    function convert_user_var($message)
    {
        // replace user's variables
        $message = str_replace("%user_name%", $_SESSION['name'], $message);
        $message = str_replace("%user_lastname%", $_SESSION['last_name'], $message);
        $message = str_replace("%class%", $_SESSION['class'], $message);
        
        return $message;
    }   
}

            
