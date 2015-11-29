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
    // gets results
    if (!empty($_POST["eval"]))
    {
       // puts results in database
       foreach($_POST["eval"] as $key => $eval)
       {
            
            if(empty(query("SELECT id 
                            FROM results 
                            WHERE user_id = ? 
                            AND project_id = ? 
                            AND skill_id = ?", 
                            $_POST["user_id"], 
                            $_POST["project"], 
                            $_POST["eval_cursor"][$key])))
            {
                query(" INSERT 
                        INTO results (user_id, project_id, skill_id, user_grade, date) 
                        VALUES (?, ?, ?, ?, ?)", 
                        $_POST["user_id"], $_POST["project"], $_POST["eval_cursor"][$key], $eval, date("Y-m-d"));
            }
            else
            {
                query("START TRANSACTION");
                   
                    query(" DELETE 
                            FROM results 
                            WHERE user_id = ? 
                            AND project_id = ? 
                            AND skill_id = ?", 
                            $_POST["user_id"], 
                            $_POST["project"], 
                            $_POST["eval_cursor"][$key]);
                            
                    query(" INSERT 
                            INTO results (user_id, project_id, skill_id, user_grade, date) 
                            VALUES (?, ?, ?, ?, ?)", 
                            $_POST["user_id"], $_POST["project"], $_POST["eval_cursor"][$key], $eval, date("Y-m-d"));
                
                query("COMMIT");
            }
            
            
       }
       goto render_default;   
    }    
   
   
    /**
     *  GET
     *
     */
    
    // TODO optimize array and sql queries
    
    // user list page
    if (!empty($_GET["class"]))
    {
        $users = query("SELECT id, username, name, last_name, class 
                         FROM users 
                         WHERE class = ? AND `is_staff` = 0 
                         ORDER BY last_name",
                         $_GET["class"]); 
        
        $i = 0;
        foreach ($users as $user)
        {
            $j = 0;
            $users[$i]["projects"] = query("SELECT project_name, project_id, deadline, assessment_type, periode
                             FROM `projects` WHERE class = ?", $users[$i]["class"]); 
            
            // Checks if already rated
            foreach($users[$i]["projects"] as $project)
            {
                $rated = query("SELECT user_grade FROM  `results` WHERE user_id = ? AND project_id = ?", $users[$i]["id"],  $project["project_id"]);
                
                if (!empty($rated))
                {
                    $users[$i]["projects"][$j]["is_rated"] = true;
                }
                else
                {
                    $users[$i]["projects"][$j]["is_rated"] = false;
                    
                    // Checks if already submitted
                    $submitted = query("SELECT id FROM  `submitted` WHERE user_id = ? AND project_id = ?", $users[$i]["id"],  $project["project_id"]);
                    
                    (empty($submitted) ? $users[$i]["projects"][$j]["is_submitted"] = false : $users[$i]["projects"][$j]["is_submitted"] = true );
                }
                $j++;
             }
             $i++;
        }
        // renders
        render_admin("adm_grade_list.php", ["title" =>  LABEL_RATE,  
            "skills" => $skills, "users" => $users, "classes" => $classes ], false);
    }
    
    // render rating page
    elseif (!empty($_GET["rate"]) && !empty($_GET["user"]))
    {
    
        // getting generic data 
        $user = query("SELECT id, username, name, last_name, class
                       FROM users WHERE `id` = ?", $_GET["user"])[0]; 
                       
        // gets users and join submitted
        $users = query("SELECT users.id, name, last_name, file_path, file_name, answers
                       FROM users 
                       LEFT JOIN submitted
                       ON users.id = submitted.user_id
                       AND submitted.project_id = ?
                       WHERE users.class = ? 
                       ORDER BY last_name ASC, submitted.id ASC", 
                       $_GET["rate"], $user["class"]); 
        
        
        $i = 0;
        foreach ($users as $row)
        {
            (empty($row["answer"]) && empty($row["file_path"]) ? $users[$i]["is_submitted"] = false : $users[$i]["is_submitted"] = true);
            
            $rated = query("SELECT user_grade FROM  `results` WHERE project_id = ? AND user_id = ?", $_GET["rate"], $row["id"]);
            
            (empty($rated) ? $users[$i]["is_rated"] = false : $users[$i]["is_rated"] = true);
            
            $i++;
        }
        
        
        $project = query("SELECT project_name, project_id, deadline, assessment_type
                             FROM `projects` WHERE  `project_id` = ?", $_GET["rate"])[0];
                             
        $submitted = query("SELECT file_path, file_name, answers FROM submitted WHERE user_id = ? AND project_id = ?", $_GET["user"], $_GET["rate"]);
        
        $rated = query("SELECT user_grade FROM  `results` WHERE project_id = ? AND user_id = ?", $_GET["rate"], $_GET["user"]);
        
        if($submitted)
        {
            if ($submitted[0]["answers"] !== 's:0:"";')
            { 
                $self_assessments = unserialize($submitted[0]["answers"]);
                
                // get self_assessment questions
                foreach ($self_assessments as $key => $self_assessment)
                {
                    $query = query("SELECT question FROM auto_assesment WHERE id = ?", $self_assessment["id"]);
                    $self_assessments[$key]["question"] = $query[0]["question"];
                }
            }
            else
            {
                $self_assessments = array();
            }
        }
        else
        {
            $self_assessments = array();
        }
               
        (empty($rated) ? $is_rated = false : $is_rated = true);
        
        // CRITERIA STUFF
        // gets current project
        $curr_project = query("SELECT * FROM projects WHERE project_id = ?", $_GET["rate"]);
        
        // gets selected criteria and cursors from database
        $assessments_id = explode(",", $curr_project[0]["assessment_id"]);

        
        $query = "SELECT * FROM assessment WHERE";
        foreach ($assessments_id as $assessment_id)
        {
            $query .= " id= " . $assessment_id . " OR";
        }   
        $query = rtrim($query, " OR"); 
       
        $results = query($query);
        
        $criteria = array();
        
        $i = 0;
        foreach($results as $result)
        {
            $criteria[ $result["objective"] ] [ $result["criteria"] ][ $i ] = $result["cursor"];
            $id_criterion[$i] = $result["id"];
            
            $i++;
        }  
        // END CRITERIA STUFF
        
        // renders
        render_admin("adm_grade.php", ["title" =>  LABEL_RATE,  
            "skills" => $skills, "user" => $user, "users" => $users, "project" => $project, "is_rated" => $is_rated, "rated" => $rated, "self_assessments" =>  $self_assessments,
            "criteria" => $criteria, "submitted" => $submitted, "id_criterion" => $id_criterion, "extension" => $curr_project[0]["extension"]], false);
    }
    else
    {
        // renders default page
        render_default:
        
        $users = query("SELECT id, username, name, last_name, class
                         FROM users WHERE `is_staff` = 0 ORDER BY class ");  
        $i = 0;
        foreach ($users as $user)
        {
            $j = 0;
            $users[$i]["projects"] = query("SELECT project_name, project_id, deadline, assessment_type, periode
                                            FROM `projects` 
                                            WHERE class = ?", 
                                            $users[$i]["class"]); 
            
            // Checks if already rated
            foreach($users[$i]["projects"] as $row)
            {
                
                $query = query("SELECT answers, file_path FROM submitted WHERE user_id = ? AND project_id = ?", $users[$i]["id"], $row["project_id"]);
                
                (empty($query[0]) ? $users[$i]["projects"][$j]["is_submitted"] = false : $users[$i]["projects"][$j]["is_submitted"] = true);
            
                $rated = query("SELECT user_grade FROM  `results` WHERE project_id = ? AND user_id = ?", $row["project_id"],  $users[$i]["id"]);
                
                (empty($rated) ? $users[$i]["projects"][$j]["is_rated"] = false : $users[$i]["projects"][$j]["is_rated"] = true);
                
                $j++;
             }
             $i++;
        }
        render_admin("adm_grade_list.php", ["title" =>  LABEL_ADMIN,  
                        "classes" => $classes, "users" => $users ], false);   
    }
    
?>