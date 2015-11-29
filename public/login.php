<?php

    // configuration
    require("../includes/config.php"); 
    
    
    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("login_form.php", ["title" => "Log In"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["password"]))
        {
            apologize(LABEL_PROVIDE_USERNAME);
        }
        elseif (empty($_POST["username"]))
        {
            apologize( LABEL_PROVIDE_PASSWORD);
        }

        // query database for user
        $rows = query("SELECT * FROM users WHERE username = ?", $_POST["username"]);

        // if we found user, check password
        if (count($rows) == 1)
        {
            // first (and only) row
            $row = $rows[0];

            // compare hash of user's input against hash that's in database
            if (crypt($_POST["password"], $row["hash"]) == $row["hash"])
            {
                // remember that user's now logged in by storing user's ID in session
                $_SESSION["id"] = $row["id"];
                $_SESSION["class"] = $row["class"];
                $_SESSION["name"] = $row["name"];
                $_SESSION["last_name"] = $row["last_name"];
                $_SESSION["admin"] = $row["is_staff"];
                
                // redirect to portfolio
                redirect("/");
            }
        }

        // else apologize
        apologize(LABEL_INVALID_USER_PASS);
    }

?>
