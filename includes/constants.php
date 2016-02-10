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
    define("FULL_URL", "http://final_project/");
    
    // No reply mail 
    define("NO_REPLY_MAIL", "mg@yopmail.com");
    
    // admin mail
    define("ADMIN_MAIL", "mg@yopmail.com");
    
    // SMTP server: Use your ISP's SMTP server (e.g., smtp.fas.harvard.edu if on campus or smtp.comcast.net if off campus and your ISP is Comcast)
    define("SMTP_SERVER", "smtp.scarlet.be");
    
    // user files directory TODO: fix manual URL
    define("UPLOAD_DIR", "/home/jharvard/vhosts/$_SERVER[HTTP_HOST]/public//uploads/");
    
    // pdf files directory TODO: fix manual URL
    define("ROOT_DIR", "/home/jharvard/vhosts/$_SERVER[HTTP_HOST]/public");
   
    // is demo version?
    define("DEMO_VERSION", false);

   // max size of uploaded files in octet TODO: see php.ini
   if (DEMO_VERSION)
   {
       define("MAX_UPLOAD_FILE_SIZE", "715200");
   }
   else
   {
       define("MAX_UPLOAD_FILE_SIZE", "209715200");
   }
   // allowed html tags
   define('ALLOWED_HTML_TAGS', '<table><p><a><h4><h5><h6><i><b><code><pre><video><audio>');
   
?>
