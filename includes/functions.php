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
    
    require_once("libphp-phpmailer/class.phpmailer.php");
    
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
        render("inform.php", ["message" => $message]);
        exit;
    }
    
    /**
     * Apologizes to user with message.
     */
    function apologize($message)
    {
        render("apology.php", ["message" => $message]);
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
    function render_admin($template, $values = [], $menu = true)
    {
        // if template exists, render it
        if (file_exists("../templates/$template"))
        {
            // extract variables into local scope
            extract($values);
            
            // langs
            require("../includes/langs/be_FR.php");
            
            // render header
            require("../templates/header.php");
            
            // extract username
            $username = query("SELECT name, last_name FROM users WHERE id = ?", $_SESSION["id"]);
                
            // render menu
            require("../templates/admin_menu.php");

             // render side menu
            if($menu === true) require("../templates/admin_projects.php");
                   
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
     * Renders template, passing in values.
     */
    function render($template, $values = [], $menu = false)
    {
        // if template exists, render it
        if (file_exists("../templates/$template"))
        {
            // extract variables into local scope
            extract($values);
            
            // langs
            require("../includes/langs/be_FR.php");
            
            // render header
            require("../templates/header.php");
            
            if ($menu === true)
            {
                // extract username
                $username = query("SELECT name, last_name FROM users WHERE id = ?", $_SESSION["id"]);
                
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
     * Renders template project, passing in values.
     */
    function render_projects($template, $values = [])
    {
        // if template exists, render it
        if (file_exists("../templates/$template"))
        {
            // extract variables into local scope
            extract($values);
            
            // extract username
            $username = query("SELECT name, last_name FROM users WHERE id = ?", $_SESSION["id"]); 
                
            // langs
            require("../includes/langs/be_FR.php");
            
            // render header
            require("../templates/header.php");
          
            // render template
            require("../templates/menu.php");

            // render template
            require("../templates/projects.php");
                        
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

?>