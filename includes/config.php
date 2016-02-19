<?php

    /**
     * config.php
     *
     * Computer Science 50
     * Final work
     *
     * Configures pages.
     */

    // display errors, warnings, and notices
    ini_set("display_errors", true);
    error_reporting(E_ALL);

    // enable sessions
    session_start();
    
    // requirements
    require("constants.php");
    require("functions.php");

    /**
     *  Languages
     *
     */
    if (!defined("LANG"))
    {
        $lang["browser"] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        switch ($lang["browser"])
        {
            case "fr":
            $lang["file"] = "langs/be_FR.php";
            break;
            
            default:
            $lang["file"] = "langs/us_EN.php";
            break;
        }
        
        require_once($lang["file"]);
    }
    
    // avoids question mark char coding error
    query("SET NAMES utf8");   
    
    // require authentication for all pages except /login.php, /logout.php, and /register.php
    if (!in_array($_SERVER["PHP_SELF"], ["/login.php", "/register.php", "/logout.php", "/forgot.php"]))
    {
        if (empty($_SESSION["id"]))
        {
            redirect("login.php");
        }
    }

?>
