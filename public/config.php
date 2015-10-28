<?php

    // configuration
    require("../includes/config.php"); 

    // get competences from database
    $skills = query("SELECT skill, skill_id FROM skills ORDER BY skill_id"); 

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
    
    
    
    
    
    /**
     *  POST
     *
     */
    // gets new skills or del skill
    if (!empty($_POST["skill_id"]) && !empty($_POST["skill"]))
    {
         query("INSERT INTO skills (skill_id, skill) VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE skill_id = skill_id, skill = skill", 
                $_POST["skill_id"], $_POST["skill"] ); 
         
         redirect("skills.php");   
    }
    elseif (!empty($_POST["del"]))
    {
        $delete = explode("+++", $_POST["del"][0]);
        query("DELETE FROM skills WHERE skill_id = ? AND skill = ?", $delete[0], $delete[1]);  
        redirect("skills.php"); 
    }    
   
    // TODO remove user
    
        // don't remove last admin
   
    /**
     *  GET
     *
     */
    if(isset($_GET["skills"]))
    {    
        // renders
        render_admin("skills.php", ["title" => $lang['ADMIN'],  
            "skills" => $skills], false);
    }
    else //if(isset($_GET["users"]))
    {    
        // renders
        render_admin("users.php", ["title" => $lang['ADMIN'],  
            "users" => $users], false);
    }
?>