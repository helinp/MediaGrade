<?php

   
    // configuration
    require("../includes/config.php");
    if ($_POST)
    {
        if(isset($_POST["change_email"]))
        {
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) 
            {
              apologize("Invalid email format");; 
            }
            else
            {
                query(" UPDATE users
                        SET email = ?
                        WHERE id = ?",
                        $_POST["email"],
                        $_SESSION["id"]);
                 // Confirms it's done
                inform("Your mail adress has been changed.");
            }
        }
        if(isset($_POST["change_password"]))
        {
            if (empty($_POST["new_password"]) || empty($_POST["actual_password"]))
            {
                apologize("You must provide a password!");
            }
            else
            {    
                // check actual password
                $chk = query("SELECT hash from users WHERE id = ?", $_SESSION["id"])[0];
                
                if (crypt($_POST["actual_password"], $chk["hash"]) !== $chk["hash"])
                {
                    apologize("Incorrect password");
                }
                
                // password check
                if ($_POST["new_password"] === $_POST["confirm_new_password"])
                {
                    // password strenght check
                    if (check_password($_POST["new_password"]))
                    {
                        query("UPDATE users SET hash = ? WHERE id = ? ", crypt($_POST["new_password"]), $_SESSION["id"]);
                      
                        // Confirms it's done
                        inform("Your password has been changed.");
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
    }
    $query = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
    render("profile.php", ["title" => "Profil", "user_data" => $query[0]], true);    


?>
