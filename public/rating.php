<?php

    // configuration
    require("../includes/config.php"); 

    // checks if user is admin
    if (!query("SELECT is_staff FROM users WHERE id = ? AND is_staff = 1", $_SESSION["id"]))
    {
        redirect("login.php");
    }

    // get competences from database
    $skills = query("SELECT skill, skill_id FROM skills ORDER BY skill_id"); 

    // get users
    $users = array();
    $classes = query("SELECT DISTINCT class FROM users ORDER BY class");
    
    
    /**
     *  POST
     *
     */
    // gets new skills or del skill
    if (!empty($_POST["skill_id"]) && !empty($_POST["skill"]))
    {

    }    
   
    // TODO remove user
    
        // don't remove last admin
   
    /**
     *  GET
     *
     */
    
    // TODO optimize array and sql queries
    
    // user list page
    if (!empty($_GET["class"]))
    {
        $users = query("SELECT id, username, name, last_name, class
                         FROM users WHERE `class` = ? AND `is_staff` = 0 ORDER BY username ", $_GET["class"]);  
        $i = 0;
        foreach ($users as $user)
        {
            $users[$i]["projects"] = query("SELECT project_name, project_id, deadline, assessment_type
                             FROM `projects` WHERE class = ?", $users[$i]["class"]); 
            $i++;
        }
        
        // renders
        render_admin("rating_list.php", ["title" => $lang['ADMIN'],  
            "skills" => $skills, "users" => $users, "classes" => $classes ], false);
    }
    
    // rating page
    elseif (!empty($_GET["rate"]))
    {
    
        $user = query("SELECT id, username, name, last_name, class
                       FROM users WHERE `id` = ?", $_GET["user"])[0]; 
        
        $project = query("SELECT project_name, project_id, deadline, assessment_type
                             FROM `projects` WHERE  `project_id` = ?", $_GET["rate"])[0];
                             
        $submitted = query("SELECT file_path FROM submitted WHERE user_id = ? AND project_id = ?", $_GET["user"], $_GET["rate"]);
        
        // CRITERIA STUFF
        // gets current project
        $curr_project = query("SELECT * FROM projects WHERE project_id = ?", $_GET["rate"]);
        
        // gets selected criteria and cursors from database
        $assessments_id = explode(",", $curr_project[0]["assessment_id"]);

        $i = 0;
        $query = "SELECT * FROM assessment WHERE";
        foreach ($assessments_id as $assessment_id)
        {
            $query .= " id= " . $assessment_id . " OR";
        }   
        $query = rtrim($query, " OR"); 
       
        //dump($query);
        $results = query($query);
        

        foreach($results as $result)
        {        
            $criteria[$i] = [
                "criterion" => $result["criteria"],
                "cursor" => $result["cursor"],
                "coefficient" => ""
                ]; 
            $i++;
        }
        
        
        // renders
        render_admin("rating.php", ["title" => $lang['ADMIN'],  
            "skills" => $skills, "user" => $user, "project" => $project, "criteria" => $criteria, "submitted" => $submitted ], false);
    }
    else
    {
        // renders default page
        
        $users = query("SELECT id, username, name, last_name, class
                         FROM users WHERE `is_staff` = 0 ORDER BY username ");  
        $i = 0;
        foreach ($users as $user)
        {
            $users[$i]["projects"] = query("SELECT project_name, project_id, deadline, assessment_type
                             FROM `projects` WHERE class = ?", $users[$i]["class"]); 
            $i++;
        }
        
        render_admin("rating_list.php", ["title" => $lang['ADMIN'],  
                        "classes" => $classes, "users" => $users ], false);   
    }
    
    
    // dump($users);

    
?>
