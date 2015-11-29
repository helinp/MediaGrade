<?php

    // configuration
    require("../includes/config.php"); 
    
    // redirect if user is not admin
    if (!$_SESSION["admin"]) redirect("index.php");

    
    /**
     *  POST
     *
     **/  
    if (!empty($_POST))
    {
        // check if disactivate action is requested
        if(isset($_POST["disactivate_project"]))
        {
            query(" UPDATE projects 
                    SET is_activated = NOT is_activated
                    WHERE project_id = ?", $_POST["disactivate_project"]);
            
            redirect("admin.php");
        }
        
        // check if delete action is requested
        if(isset($_POST["delete_project"]))
        {
            // TODO ask confirmation
            
            query("DELETE FROM projects WHERE project_id = ?", $_POST["delete_project"]);
            
            redirect("admin.php");
        }
        

        /*
         * checks if form is full and download the file
         *
         */           
        if(empty($_POST["project_name"]) || empty($_POST["skills"]) || empty($_POST["assessment_type"]) 
           || empty($_POST["deadline"]) || empty($_POST["criterion"]) 
           || empty($_POST["periode"]) || empty($_POST["class"]))
        {
               apologize(LABEL_FORM_NOT_COMPLETE);
        }
        
        if(is_uploaded_file($_FILES['submitted_file']['tmp_name']))
        {
            $sanitized_project_name = preg_replace("/[^a-zA-Z0-9]/", "_", $_POST["project_name"]);
            $sanitized_project_name = preg_replace("/_+/", "_", $sanitized_project_name ); 
            
            
            // rename file CLASS_PERIODE_Project.ext
            $extension = pathinfo($_FILES['submitted_file']['name'], PATHINFO_EXTENSION);
            $rename = strtoupper(strip_tags($_POST["class"])) . "_" .  strip_tags($_POST["periode"]) . "_" . $sanitized_project_name . "." . strip_tags($extension);
            
            
            // puts the file in ./upload/class/periode_#   TODO: add class_year     
            $upload_dir = "uploads/". $_POST["class"] . "/p" . $_POST["periode"] . "/";
            $upload_file = $upload_dir . $rename;
            
            // checks RFI check
            if (!empty(stristr(basename($_FILES['submitted_file']['name']), "php")))
            {
                sendamail(ADMIN_MAIL, "Tentative de RFI", "USER_INCLUSION_EXPLOIT \nFrom user: " . $_SESSION["id"] . "\nip: " . $_SERVER["REMOTE_ADDR"]);
                apologize(LABEL_USER_INCLUSION_EXPLOIT);
            }
            
            // checks file size
            if ($_FILES['submitted_file']['size'] >  MAX_UPLOAD_FILE_SIZE)
            {
                apologize(LABEL_MAX_SIZE_REACHED);
            }
            
            // checks file mime type TODO: get mime type from database 
            if($_FILES['submitted_file']['type'] != 'application/pdf' && $_FILES['submitted_file']['type'] != 'application/x-pdf' 
                && $_FILES['submitted_file']['type'] != 'application/acrobat' && $_FILES['submitted_file']['type'] != 'applications/vnd.pdf' 
                && $_FILES['submitted_file']['type'] != 'text/pdf' && $_FILES['submitted_file']['type'] != 'text/x-pdf')
            {
                // dump($_FILES['submitted_file']);
                apologize(LABEL_UNEXPECTED_FILE_TYPE);
            }
            
            // creates directory if doesn't exit
            if (!is_dir($upload_dir) && !mkdir($upload_dir, 0774, true))
            {
                apologize(LABEL_DIR_CREATION_ERROR . $upload_dir);
            }
            
            // uploads the file
            if (!move_uploaded_file($_FILES['submitted_file']['tmp_name'], $upload_file)) 
            {
               apologize(USER_EXPLOIT);
            }
            
            chmod($upload_file, 0774);
            
         }
         
         // if no file uploaded by user
         else
         {
            $upload_file = "";
         }
        
        /*
         * UPDATE ASSESSMENT TABLE WITH USER $_POST DATA
         * 
         */
        $i = 0;
        
        foreach($_POST["objective"] as $objective)
        {
            
            // insert or update criteria in assessment table
            query(" INSERT INTO assessment
                    VALUES(DEFAULT, ?, ?, ?)", 
                    $_POST["objective"][$i], 
                    $_POST["criterion"][$i], 
                    $_POST["cursor"][$i]);

            // concatenate ids in variable 
            $assessments .= query(" SELECT id 
                                    FROM assessment 
                                    WHERE objective = ? 
                                    AND criteria = ? 
                                    AND `cursor` = ?", 
                                    $_POST["objective"][$i],
                                    $_POST["criterion"][$i], 
                                    $_POST["cursor"][$i])[0]["id"] . ",";
            
            // increment key
            $i++;
        }
        
        // removes last comma
        $assessments = rtrim($assessments, ",");


        /**
         *  Update AUTO_ASSESSMENT table and puts ids in string $auto_assessment
         *
         **/
        $auto_assessments = "";
        
        if(!empty($_POST["user_eval"]))
        {
            foreach($_POST["user_eval"] as $user_eval)
            {                   
                // checks if entry already exists
                $auto_assessment_id = query("SELECT * FROM auto_assesment WHERE question = ?", $user_eval);
                
                if(empty($auto_assessment_id))
                {
                      // insert into database
                      query("INSERT INTO auto_assesment (question) VALUES(?)", $user_eval);
                          
                      // puts id in variable
                      $auto_assessments .= query("SELECT LAST_INSERT_ID()")[0]["LAST_INSERT_ID()"] . ",";      
                }
                else
                {
                    // find id of existent auto_assessment
                    $auto_assessments .= $auto_assessment_id . ",";               
                }
            }
        }
        
        if(!empty($_POST["data_eval_id"]))
        {        
            foreach($_POST["data_eval_id"] as $data_eval)
            {
                $auto_assessments .= $data_eval . ",";
            }
        }     
        
        // removes last comma
        $auto_assessments = rtrim($auto_assessments, ",");

        /**
         * UPDATE PROJECTS TABLE
         *
         **/
        // get $_POST data criterion and cursor
        $assess_id = array();

        // get $_POST array skills and put prefix in array $skills
        $skills = array();
        
        foreach ($_POST["skills"] as $id)
        {
            array_push($skills, substr($id, 0, 2));
        }

        /**
         * INSERT (-1) or UPDATE (any ID) new row in PROJECT table
         *
         **/
        if ($_POST["project_id"] === "-1")
        {
           // add new project in table projects
           query("INSERT INTO projects VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)", 
                     $_POST["periode"], 
                     $upload_file, 
                     $_POST["deadline"], 
                     $_POST["project_name"], 
                     $_POST["class"], 
                     $assessments, 
                     $auto_assessments, 
                     $_POST["assessment_type"], 
                     implode(",", $skills),
                     $_POST["extension"]
                );
        }
        else
        {
            // updates project
            query("UPDATE projects 
                      SET periode = ?, instructions = ?, deadline = ?, project_name = ?, class = ?, assessment_id = ?, auto_assessment_id = ?, skill_id = ?, extension = ?
                      WHERE project_id = ?", 
                       $_POST["periode"], $upload_file, $_POST["deadline"], 
                       $_POST["project_name"], $_POST["class"], $assessments, $auto_assessments, implode(",", $skills), 
                       $_POST["extension"],
                       $_POST["project_id"]);
        }
        
        inform(LABEL_PROJECT_UPDATED);
    }
   
    /**
     *      $_GET
     *  
     **/
    
    
    if(isset($_GET["results"]))
    {
        
        // get results
        if(!empty($_GET["results"]))
        {
            $users = query("SELECT id, last_name, name, class FROM users WHERE class = ? AND is_staff = false ORDER BY last_name", $_GET["results"]);
        }
        
        if (empty($users) || !isset($_GET["results"]) || empty($_GET["results"]))
        {
            $first_class = query("SELECT DISTINCT class FROM users WHERE is_staff = 0 ORDER BY class")[0]["class"];
            $users = query("SELECT id, last_name, name, class FROM users WHERE is_staff = false AND class=? ORDER BY class, last_name", $first_class);
        }
        
        $results = "";
        
        if (isset($_GET["period"]))
        {
            $projects = query("SELECT project_name, project_id FROM projects WHERE class = ? AND periode = ?", $users[0]['class'], $_GET["period"]);
        }
        if (!isset($_GET["period"]) || empty($_GET["period"]) || empty($projects) )
        {
            $projects = query("SELECT project_name, project_id FROM projects WHERE class = ?", $users[0]['class']);
        }
        
        $objectives = query("SELECT DISTINCT objective FROM assessment ORDER BY objective");
       
        $average = "";

        foreach($users as $user)
        {

            foreach($projects as $key => $project)
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
            $average = query("SELECT AVG(user_grade) FROM results WHERE user_id = ?", $user["id"]);
            
            // if query didn't found anything
            if(empty($average[0]["AVG(user_grade)"])) $average[0]["AVG(user_grade)"] = "--";
            
            // put in variable
            $averages[ strtoupper($user["last_name"]) . " " . $user["name"][0] . "." ] = $average[0]["AVG(user_grade)"];
        }
                
        render_admin("adm_results.php", ["title" =>  LABEL_ADMIN, "results" => $results, "projects" => $projects, 
                    "objectives" => $objectives, "averages" => $averages, "user_class" => $users[0]["class"]], false); 
    }
    
    /**
     * RENDERS PROJECT PAGES
     *
     **/
     
    // get competences from database
    $skills = query("SELECT skill, skill_id FROM skills ORDER BY skill_id "); 

    // select objectives name
    $objectives = array_column(query("SELECT DISTINCT objective FROM assessment ORDER BY objective"), "objective");

    // get questions and ids from auto_assessment and fills array 
    $auto_assessments = query("SELECT * FROM auto_assesment LIMIT 5");

    // adds checked value
    foreach(array_keys($auto_assessments) as $key)
    {
        $auto_assessments[$key]["checked"] = "";
    }
            
    // declare array to vaoid error if empty
    $skills_selected = array();    
    $assessments = "";
     
    // gets projects for side menu
    $projects = query("SELECT * FROM projects ORDER BY periode DESC, project_id DESC ");
    
    // gets classes for <select> in templace adm_add_project  
    $classes = query("SELECT DISTINCT class FROM users ORDER BY class"); 

    // Renders open project page
    if(isset($_GET["project"]))
    {
       
        // gets current project
        $curr_project = query("SELECT * FROM projects WHERE project_id = ?", $_GET["project"]);
        
        if(!$curr_project){apologize(LABEL_USER_EXPLOIT);}
        
        
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
            $rows[$i] = [
                "objective" => $result["objective"],
                "criterion" => $result["criteria"],
                "cursor" => $result["cursor"],
                "coefficient" => ""
                ]; 
            $i++;
        }
            
        
         //dump($criteria);
        
        // converts string skills 1, 2, 3 .. in array
        $skills_selected = explode(",", $curr_project[0]["skill_id"]);        
        
        // make a variable to get unselected self-assessement questions
        $query = "SELECT * FROM auto_assesment ";
        
        //dump($curr_project);
        
        if(!empty($curr_project[0]["auto_assessment_id"]))
        {
            // flushes variable
            $auto_assessments = array();
            
            $query .= "WHERE ";
            
            // converts string 1, 2, 3 .. in array
            $autoassesment_id = explode(",", $curr_project[0]["auto_assessment_id"]);
        
            foreach($autoassesment_id as $id)
            {
                $push = query("SELECT * FROM auto_assesment WHERE id = ? ", $id)[0];
                $push["checked"] = "checked";
                array_push($auto_assessments, $push);
                
                $query .= " id != " . $id . " AND ";
            }

            // remove last 'AND' from query
            $query = rtrim($query, "AND ");
               
            $autoassesment_id = query($query);
              
            foreach($autoassesment_id as $id)
            {
                $push = $id; 
                $push["checked"] = "";
                array_push($auto_assessments, $push); 
            }
        }

        
        
        //dump($auto_assessments);
        
        if (!$curr_project)
        {
            apologize(LABEL_PROJECT_NOT_FOUND);
        }
        else
        {
            //dump($curr_project[0]);
            render_admin("adm_add_project.php", ["title" =>  LABEL_MANAGE_PROJECTS, "projects" => $projects, 
                "curr_project" => $curr_project[0], "skills" => $skills, "self_assessments" => $auto_assessments, 
                "skills_selected" => $skills_selected, "rows" => $rows, "objectives_list" => $objectives, "classes" => $classes]);           
        }
    }
    
    /**
     * RENDERS NEW PROJECT PAGE
     *
     **/
    else
    {
        $criteria_autocomplete = "";
        $i = 0;
        
        $curr_project = [
            "periode" => "",
            "deadline" => "",
            "project_name" =>  LABEL_PROJECT_TITLE,
            "class" => "",
            "assessment_id" => "",
            "auto_assessment_id" => "",
            "assessment_type" => "",
            "skill_id" => "",
            "extension" => ""
            ];
            
        // creates dummy empty array
        $rows[0] = [
            "objective" => "",
            "criterion" => "",
            "cursor" => "",
            "coefficient" => ""
            ];    
          
        
        // TODO simplificate array
        render_admin("adm_add_project.php", ["title" =>  LABEL_ADMIN, "projects" => $projects, 
            "skills" => $skills, "curr_project" => $curr_project, "self_assessments" => $auto_assessments, 
            "skills_selected" => $skills_selected, "objectives_list" => $objectives, "rows" => $rows, "classes" => $classes]);
    }

?>
