<?php

    // configuration
    require("../includes/config.php"); 

    /**
     *  POST
     *
     */
    // gets new skills or del skill
    if (!empty($_POST["skill_id"]) && !empty($_POST["skill"]))
    {
         query("INSERT INTO skills (skill_id, skill) VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE skill = ?", 
                $_POST["skill_id"], $_POST["skill"], $_POST["skill"] ); 
         
         redirect("adm_config.php?skills");   
    }
    elseif (!empty($_POST["del_skill"]))
    {
        $delete = explode("+++", $_POST["del_skill"][0]);
        query("DELETE FROM skills WHERE skill_id = ? AND skill = ?", $delete[0], $delete[1]);  
        
        redirect("adm_config.php?skills"); 
    }    
    elseif (!empty($_POST["del_user"]))
    {
        $delete = explode("_", $_POST["del_user"][0]);
        
        // don't remove last admin or current user
        if(count(query("SELECT is_staff FROM users WHERE id = ?", $delete[1])) == 1 || $_SESSION["id"] == $delete[1])
        {
           apologize("Cannot delete current user or last admin.");
        }
        else
        {
            query("DELETE FROM users WHERE id = ?", $delete[1]);  
            redirect("adm_config.php?users"); 
        }
    }       
    // add user
    elseif (!empty($_POST["add_user"]))
    {
        
        // check if all fields are filled
        if (empty($_POST["class"]) || empty($_POST["name"]) || empty($_POST["last_name"]) || empty($_POST["username"]) || empty($_POST["password"]))
        {
            apologize("All fields must be filled.");
        }
        
        // check if user already exist
        if (!query("SELECT id FROM users WHERE username = ?",$_POST["username"]))
        {
            query("INSERT INTO users (username, name, last_name, class, hash) VALUES (?, ?, ?, ?, ?)",
                    $_POST["username"], $_POST["name"], $_POST["last_name"], $_POST["class"], crypt($_POST["password"]));
            
            redirect("adm_config.php?users"); 
        }
        else
        {
            apologize("User already exist.");
        }
    }    
    // TODO commit changes
    elseif (!empty($_POST["update_user"]))
    {
        $user_id = $_POST["update_user"][0];
        
        // checks if all fields are filled
        if (empty($_POST["class"][$user_id]) || empty($_POST["name"][$user_id]) || empty($_POST["last_name"][$user_id]) || empty($_POST["username"][$user_id]))
        {
            apologize("All fields must be filled.");
        }
        else
        {
            // update all but password
            if (empty($_POST["password"][$user_id]))
            {
                query("UPDATE users SET class = ?, name = ?, last_name = ?, username = ?, is_staff = 0 WHERE id = ?",
                    $_POST["class"][$user_id], $_POST["name"][$user_id], $_POST["last_name"][$user_id], $_POST["username"][$user_id], $user_id);
            }
            // update all password inclued
            else
            {
                query("UPDATE users SET class = ?, name = ?, last_name = ?, username = ?, is_staff = 0, hash = ? WHERE id = ?",
                    $_POST["class"][$user_id], $_POST["name"][$user_id], $_POST["last_name"][$user_id], $_POST["username"][$user_id], crypt($_POST["password"][$user_id]), $user_id);
            }
        }
        redirect("adm_config.php?users");
                
        //if password value then change pass
    }
    elseif(!empty($_POST["message_board"]))
    { 
        $input = html_purify($_POST["message_board"]);
        
        query("UPDATE config SET content = ? WHERE type='welcome_message'", $input);
        
        goto adm_welcome;
    }
       
    /**
     *  GET
     *
     */
    if(isset($_GET["skills"]))
    {    
        // get competences from database
        $skills = query("SELECT skill, skill_id FROM skills ORDER BY skill_id"); 
    
        // renders
        render("adm_skills.php", ["title" =>  LABEL_ADMIN_SKILLS,  
            "skills" => $skills], true, true);
    }
    elseif(isset($_GET["welcome"]))
    {    
        adm_welcome:
        
        $query = query("SELECT content FROM config WHERE type = 'welcome_message'");
        
        $message = "";
        
        if($query)
        {
            $message = $query[0]["content"];
        }
        
        
        // renders
        render("adm_message_board.php", ["title" =>  LABEL_ADMIN_SKILLS, "message" => $message], true, true);
    }
    else //if(isset($_GET["users"]))
    {    
        // get users
        $users = array();
        $classes = query("SELECT class FROM users ORDER BY class");
        
        foreach($classes as $class )
        {
            $users[$class["class"]] = 
            query("SELECT * FROM users WHERE class = ? ORDER BY class, username ", $class["class"]);
            
        }

        // checks if user is admin
        if (!query("SELECT is_staff FROM users WHERE id = ? AND is_staff = 1", $_SESSION["id"]))
        {
            redirect("login.php");
        }
            
        // renders
        render("adm_users.php", ["title" =>  LABEL_ADMIN_USERS,  
            "users" => $users], true, true);
    }
?>
