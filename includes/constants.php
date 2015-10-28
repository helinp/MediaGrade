<?php

    /**
     * constants.php
     *
     * Computer Science 50
     * Final Work
     *
     * Global constants.
     */

    // your database's name
    define("DATABASE", "mediagrade");

    // your database's password
    define("PASSWORD", "crimson");

    // your database's server
    define("SERVER", "localhost");

    // your database's username
    define("USERNAME", "jharvard");
    
    // absolute address
    define("FULL_URL", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    
    // No reply mail 
    define("NO_REPLY_MAIL", "no-reply@yopmail.com");
    
    // admin mail
    define("ADMIN_MAIL", "pierre.helin@gmail.com");
    
    // SMTP server: Use your ISP's SMTP server (e.g., smtp.fas.harvard.edu if on campus or smtp.comcast.net if off campus and your ISP is Comcast)
    define("SMTP_SERVER", "smtp.scarlet.be");
    
    // user files directory TODO: fix manual URL
    define("UPLOAD_DIR", "/home/jharvard/vhosts/$_SERVER[HTTP_HOST]/public//uploads/");
    
    // pdf files directory TODO: fix manual URL
    define("ROOT_DIR", "/home/jharvard/vhosts/$_SERVER[HTTP_HOST]/public");
   
   // max size of uploaded files in octet TODO: see php.ini
   define("MAX_UPLOAD_FILE_SIZE", "209715200");

?>
