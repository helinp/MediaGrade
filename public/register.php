<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("register_form.php", ["title" => "Register"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["username"]) || empty($_POST["name"]) || empty($_POST["last_name"]))
        {
            apologize($lang['ALL_FIELDS_REQUIRED']);
        }
        elseif (empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
        {
            apologize($lang['PROVIDE_EMAIL']);
        }
        elseif ($_POST["password"] !== $_POST["confirmation"])
        {
            apologize($lang['PASSWORD_MISMATCH']);
        }
        elseif (empty($_POST["password"]))
        {
            apologize($lang['PROVIDE_PASSWORD']);
        }
        elseif (!check_password($_POST["password"]))
        {
            apologize($lang['SAFE_PASSWORD']);
        }
        else
        {
            // register
            $register_query = query("INSERT INTO users (name, last_name, username, hash, cash, email) VALUES(?, ?, 10000.00, ?)", $_POST["name"], $_POST["last_name"], $_POST["username"], crypt($_POST["password"]), $_POST["email"] );
            
            // validation check
            if ($register_query === false) 
            {
                apologize($lang['ALREADY_USER']);
            } 
            else
            {
                // get ID of new user
                $rows = query("SELECT LAST_INSERT_ID() AS id");
                           
                // log user
                $_SESSION["id"] = $rows[0]["id"];
                redirect("/");
            }
        }
    }
?>
