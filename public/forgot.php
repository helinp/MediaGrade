<?php

   
    // configuration
    require("../includes/config.php");
    
    /**
     *  Controls password reset process
     *
     */
    if (isset($_POST["password_new"]))
    {
        if (empty($_POST["password_new"]))
        {
            apologize("You must provide a password!");
        }
        else
        {    
            // password check
            if ($_POST["password_new"] === $_POST["password_new_confirmation"])
            {
                // password strenght check
                if (check_password($_POST["password_new"]))
                {
                    $check = query("SELECT id FROM lost_password WHERE hash = ?", $_POST["hash"]);
                    
                    if (!empty($check))
                    {
                        query("UPDATE users SET hash= ? WHERE id = ? ", crypt($_POST["password_new"]), $check[0]["id"]);
                        query("DELETE FROM lost_password WHERE id = ?", $check[0]["id"]);
                        // Confirms it's done
                         inform("Your password has been changed.");
                    }
                    else
                    {
                        apologize("Something gone wrong.");
                    }
                }
                else
                {
                    apologize("Password must be at least 8 characters long, including 1 uppercase letter and 1 number.");
                }
            }
            else
            {
                apologize("Passwords don't match");
            }
        }
    }
    
    /**
     *  Render change password form
     *
     */   
    else if (isset($_GET["lost"]))
    {
        // loads table
        $check = query("SELECT * from lost_password WHERE hash = ?", $_GET["lost"]);
        
        // check if hash exists in database
        if (!empty($check))
        {
            render("change_password.php", ["title" => "Change Password", "hash" => $_GET["lost"]], false);
        }
        
        // redirects if invalid hash
        else
        {
            redirect("/");
        }
    
    }
    
    /**
     *  Validate and process password change
     *
     */
     
    else if (isset($_POST["email"]))
    {
        if (empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
        {
            apologize("You must provide a valid mail adress.");
        }
        else
        {         
            $row = query("SELECT * FROM users WHERE email =  ?", htmlspecialchars($_POST["email"]));
            
            if (empty($row))
            {
                apologize("Unregistered email address.");
            }
            else
            {
                // creates a very simple hash
                $hash = bin2hex(openssl_random_pseudo_bytes(50));  

                // makes recovery url
                $reset_password_url = FULL_URL . "forgot.php?lost=" . sha1("$hash");
                
                // stores id and hash in database and updates is already exist
                query("INSERT INTO lost_password(id, hash) VALUES(?, ?) ON DUPLICATE KEY UPDATE hash = ?", $row[0]["id"], sha1("$hash"), sha1("$hash"));
                // dump($reset_password_url);
                
                // stores mail message in variable
                $subject = "Password reset";
                $message = 

"Dear " . $row[0]["username"] . ",\r\n

You have requested to have your password reset for your account.\r\n
Please visit this url to reset your password:\r\n". $reset_password_url . "\r\n
If you received this email in error, you can safely ignore this email.\r\n\r\n
MediaGrade Team.\r\n";
                
                 // Send mail
                 sendamail($_POST["email"], $subject, $message);
                 
                 // Confirms it's done
                 inform("An email has been sent. Use the link contained in the email to change you password.");
            }
        }
    }
    else
    {
        render("forgot_form.php", ["title" => LABEL_FORGOT_PASS], false);    
    }

?>
