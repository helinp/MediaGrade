<?php

    /**
     * functions.php
     *
     * Computer Science 50
     * Final work
     * 
     * Helper functions from Problem Set 7.
     */

    require_once("constants.php");
    
    require_once("libphp-phpmailer/PHPMailerAutoload.php");
   
    /**
     * Checks password strenght.
     */     
    function check_password($password)
    {
        if (preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#", $password))
        {
            return true;
        } 
        else 
        {
            return false;
        }

    }
    
    /**
     *  Purifies html tags
     */
    function html_purify($input)
    {
        return(strip_tags($input, ALLOWED_HTML_TAGS));   
    }

    function sanitize_name($name)
    {
        setlocale(LC_ALL,'en_US.UTF-8');
        $sanitized = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
        $sanitized = preg_replace("/[^a-zA-Z0-9]/", "_", $sanitized);
        return(preg_replace("/_+/", "_", $sanitized)); 
         
    }

    /**
     * Returns scolar year from timestamp 
     * Es: 2015-2016
     */
	function get_school_year($time_stamp = false)
    {
      if ($time_stamp) 
      {
          $time = strtotime($time_stamp);
          $yy = date('Y', $time);
          $dm = date('dm', $time);
      }
      else 
      {
          $yy = date('Y');
          $dm = date('dm');
      }      
      
      if($dm < END_OF_YEAR) return ($yy - 1 . '-' . $yy);
      else return ($yy . '-' . $yy + 1);
    }

    /**
     * Sends a mail.
     */    
    function sendamail($send_to, $subject, $message)
    {
        // instantiate mailer
        $mail = new PHPMailer();
        
        $mail->IsSMTP();
        $mail->Host = SMTP_SERVER;

        // set From:
        $mail->SetFrom(NO_REPLY_MAIL);

        // set To:
        $mail->AddAddress($send_to);

        // set Subject:
        $mail->Subject = $subject;

        // set body
        $mail->Body = $message;

        // send mail
        if ($mail->Send() === false)
            die($mail->ErrorInfo . "\n");
    }
    
    /**
     * Inform user with message.
     */
    function inform($message)
    {
        render("inform.php", ["title" => "Information", "message" => $message], false);
        exit;
    }
    
    /**
     * Apologizes to user with message.
     */
    function apologize($message)
    {
        render("apology.php", ["title" => "Erreur", "message" => $message], false);
        exit;
    }

    /**
     * Facilitates debugging by dumping contents of variable
     * to browser.
     */
    function dump($variable)
    {
        require("../templates/dump.php");
        exit;
    }

    /**
     * Logs out current user, if any.  Based on Example #1 at
     * http://us.php.net/manual/en/function.session-destroy.php.
     */
    function logout()
    {
        // unset any session variables
        $_SESSION = [];

        // expire cookie
        if (!empty($_COOKIE[session_name()]))
        {
            setcookie(session_name(), "", time() - 42000);
        }

        // destroy session
        session_destroy();
    }

    /**
     * Executes SQL statement, possibly with parameters, returning
     * an array of all rows in result set or false on (non-fatal) error.
     */
    function query(/* $sql [, ... ] */)
    {
        // SQL statement
        $sql = func_get_arg(0);
                
        // parameters, if any
        $parameters = array_slice(func_get_args(), 1);

        // try to connect to database
        static $handle;
        if (!isset($handle))
        {
            try
            {
                // connect to database
                $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

                // ensure that PDO::prepare returns false when passed invalid SQL
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
            }
            catch (Exception $e)
            {
                // trigger (big, orange) error
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }

        // prepare SQL statement
        $statement = $handle->prepare($sql);
        if ($statement === false)
        {
            // trigger (big, orange) error
            trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            exit;
        }

        // execute SQL statement
        $results = $statement->execute($parameters);

        // return result set's rows, if any
        if ($results !== false)
        {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            return false;
        }
    }

    /**
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any HTML.
     */
    function redirect($destination)
    {
        // handle URL
        if (preg_match("/^https?:\/\//", $destination))
        {
            header("Location: " . $destination);
        }

        // handle absolute path
        else if (preg_match("/^\//", $destination))
        {
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            header("Location: $protocol://$host$destination");
        }

        // handle relative path
        else
        {
            // adapted from http://www.php.net/header
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: $protocol://$host$path/$destination");
        }

        // exit immediately since we're redirecting anyway
        exit;
    }


    /**
     * Renders template, passing in values.
     */
     function render($template, $values = [], $menu = true)
    {
        // if template exists, render it
        if (file_exists("../templates/$template"))
        {
            // extract variables into local scope
            extract($values);
            
            // render header
            require("../templates/header.php");
            
            // render user side menu
            if ($menu)
            {
                // render admin side menu
                if(@$_SESSION["admin"]) 
                {
                    // gets classes for <select> in templace adm_add_project  
                    $classes = query("SELECT DISTINCT class FROM users WHERE is_staff = 0 ORDER BY class");
                }
 
                // render template
                require("../templates/menu.php");
            }
            
            // render template
            require("../templates/$template");

            // render footer
            require("../templates/footer.php");
        }

        // else err
        else
        {
            trigger_error("Invalid template: $template", E_USER_ERROR);
        }
    }

    /**
     *  Converts and format bytes into readable value
     *  Credits: wiede at gmx dot net on php.net
     */
    function format_bytes($bytes)
    {
        $fr_prefix = array( 'o', 'Ko', 'Mo', 'Go', 'To', 'Eo', 'Zo', 'Yo' );
        $si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
        
        if ($_SESSION['lang'] == 'fr') $si_prefix =  $fr_prefix;
        
        $base = 1024;
        $class = min((int)log($bytes , $base) , count($si_prefix) - 1);
        return sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
    }
    
    /**
     *  Returns a jpg poster from a video
     *
     */
     
    function make_video_poster($movie_file, $dest)
    {
        
        $extension = "ffmpeg";
        $extension_soname = $extension . "." . PHP_SHLIB_SUFFIX;
        $extension_fullname = PHP_EXTENSION_DIR . "/" . $extension_soname;

        // load extension
        if(!extension_loaded($extension)) {
            dl($extension_soname) or die("Can't load extension $extension_fullname\n");
        }
        
        if (!$movie_file) return 0;
        
        extension_loaded('ffmpeg');
        
        // Instantiates the class ffmpeg_movie so we can get the information you want the video  
        $movie = new ffmpeg_movie($movie_file);  

        // Get The duration of the video in seconds  
        echo $Duration = round($movie->getDuration(), 0);  

        // Get the number of frames of the video  
        $TotalFrames = $movie->getFrameCount();  

        // Get the height in pixels Video  
        $height = $movie->getFrameHeight();  

        // Get the width of the video in pixels  
        $width = $movie->getFrameWidth();  

        //Receiving the frame from the video and saving 
        // Need to create a GD image ffmpeg-php to work on it  
        $image = imagecreatetruecolor($width, $height);  

        // Create an instance of the frame with the class ffmpeg_frame  
        $Frame = new ffmpeg_frame($image);  

        // Choose the frame you want to save as jpeg  
        $thumbnailOf = (int) round($movie->getFrameCount() / 2.5);  

        // Receives the frame  
        $frame = $movie->GetFrame($thumbnailOf);  

        // Convert to a GD image  
        $image = $frame->toGDImage();  

        // Save to disk.  
        //echo $movie_file.'.jpg';
        imagejpeg($image, $dest . '.jpg', 100);

        //return $movie_file.'.jpg';
    }
    
    
    
    /**
     *  From davidwalsh.name
     *
     */    

    function make_thumbnail($src, $dest, $desired_width = false, $desired_height = false)
    {
        /*If no dimenstion for thumbnail given, return false */
        if (!$desired_height&&!$desired_width) return false;
        $fparts = pathinfo($src);
        $ext = strtolower($fparts['extension']);
        /* if its not an image return false */
        if (!in_array($ext,array('gif','jpg','png','jpeg'))) return false;

        /* read the source image */
        if ($ext == 'gif')
            $resource = imagecreatefromgif($src);
        else if ($ext == 'png')
            $resource = imagecreatefrompng($src);
        else if ($ext == 'jpg' || $ext == 'jpeg')
            $resource = imagecreatefromjpeg($src);
        
        $width  = imagesx($resource);
        $height = imagesy($resource);
        /* find the "desired height" or "desired width" of this thumbnail, relative to each other, if one of them is not given  */
        if(!$desired_height) $desired_height = floor($height*($desired_width/$width));
        if(!$desired_width)  $desired_width  = floor($width*($desired_height/$height));
      
        /* create a new, "virtual" image */
        $virtual_image = imagecreatetruecolor($desired_width,$desired_height);
      
        /* copy source image at a resized size */
        imagecopyresized($virtual_image,$resource,0,0,0,0,$desired_width,$desired_height,$width,$height);
        
        /* create the physical thumbnail image to its destination */
        /* Use correct function based on the desired image type from $dest thumbnail source */
        $fparts = pathinfo($dest);
        $ext = strtolower($fparts['extension']);
        /* if dest is not an image type, default to jpg */
        if (!in_array($ext,array('gif','jpg','png','jpeg'))) $ext = 'jpg';
        $dest = $fparts['dirname'].'/'.$fparts['filename'].'.'.$ext;
        
        if ($ext == 'gif')
            imagegif($virtual_image,$dest);
        else if ($ext == 'png')
            imagepng($virtual_image,$dest,1);
        else if ($ext == 'jpg' || $ext == 'jpeg')
            imagejpeg($virtual_image,$dest,100);
        
        return array(
            'width'     => $width,
            'height'    => $height,
            'new_width' => $desired_width,
            'new_height'=> $desired_height,
            'dest'      => $dest
        );
    }

    function custom_round($val, $precision = 0.5) 
    {
       if(!is_numeric($val)) return $val;
       $output = round($val / $precision);
       return $output * $precision;
    }

?>
