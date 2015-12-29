<?php

    // configuration
    require("../includes/config.php"); 
    
    // redirect if user is not admin
    if (!$_SESSION["admin"]) redirect("index.php");
    
    else
    {
        
        // get periods
        $periods = query("SELECT DISTINCT periode FROM projects ORDER BY periode");
        
        // get all students from selected class
        if(!empty($_GET["class"]))
        {
            $users = query("SELECT id, last_name, name, class FROM users WHERE class = ? AND is_staff = false ORDER BY last_name", $_GET["class"]);
        }
        
        // wrong $_GET protection
        if (empty($users) || !isset($_GET["class"]) || empty($_GET["class"]))
        {
            $first_class = query("SELECT DISTINCT class FROM users WHERE is_staff = 0 ORDER BY class LIMIT 1")[0]["class"];
            $users = query("SELECT id, last_name, name, class FROM users WHERE is_staff = false AND class=? ORDER BY class, last_name", $first_class);
        }
        
        $results = "";
        
        if (isset($_GET["period"]))
        {
            $projects = query("SELECT project_name, project_id FROM projects WHERE class = ? AND periode = ?", $users[0]['class'], $_GET["period"]);
        }
        
        // wrong $_GET protection
        if (!isset($_GET["period"]) || empty($_GET["period"]) || empty($projects) )
        {
            $projects = query("SELECT project_name, project_id FROM projects WHERE class = ?", $users[0]['class']);
        }
        
        $objectives = query("SELECT DISTINCT objective FROM assessment ORDER BY objective");
       
        $average = "";

        foreach($users as $user)
        {

            foreach($projects as $project)
            {
                
                foreach($objectives as $objective)
                {
                    $query = query("SELECT assessment.id, AVG(user_grade) 
                                    FROM results 
                                    LEFT JOIN assessment
                                    ON results.skill_id = assessment.id 
                                    WHERE results.project_id = ?
                                    AND user_id = ? 
                                    AND objective = ?
                                    GROUP BY objective",
                                    $project['project_id'],
                                    $user["id"],
                                    $objective["objective"]
                                    );
                    
                    if(empty($query[0]["AVG(user_grade)"])) $query[0]["AVG(user_grade)"] = "--";

                    $results[ strtoupper($user["last_name"]) . " " . $user["name"][0] . "." ][ $project["project_name"] ][ $objective["objective"] ] = $query;
                    
                   
                }
            }
            
            // results average 
            if (isset($_GET["period"])) 
            {
                $average = query("  SELECT AVG(user_grade) 
                                    FROM results
                                    INNER JOIN projects
                                    ON results.project_id = projects.project_id
                                    WHERE results.user_id = ? 
                                    AND projects.periode = ?", 
                                    $user["id"], 
                                    $_GET["period"]
                                    );
            }
            else 
            {
                $average = query("SELECT AVG(user_grade) FROM results WHERE user_id = ?", $user["id"]);
            }
            
            // if query didn't found anything
            if(empty($average[0]["AVG(user_grade)"])) $average[0]["AVG(user_grade)"] = "--";
            
            // put in variable
            $averages[ strtoupper($user["last_name"]) . " " . $user["name"][0] . "." ] = $average[0]["AVG(user_grade)"];
        }
                
        render("adm_results.php", ["title" =>  LABEL_ADMIN, "results" => $results, "projects" => $projects, 
                   "objectives" => $objectives, "averages" => $averages, "users_class" => $users[0]["class"], "periods" => $periods]); 
    }
    
?>
