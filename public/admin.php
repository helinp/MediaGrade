<?php

    // configuration
    require("../includes/config.php"); 

    // get competences from database
    $skills = query("SELECT skill, skill_id FROM skills ORDER BY skill_id "); 

    // select objectives name
    $objectives = array_column(query("SELECT DISTINCT objective FROM assessment ORDER BY objective"), "objective");

    // get questions and ids from auto_assessment and fills array 
    $auto_assessments = query("SELECT * FROM auto_assesment LIMIT 5");
    
    // adds ckecked value
    foreach(array_keys($auto_assessments) as $key)
    {
        $auto_assessments[$key]["checked"] = "";
    }
            
    // declare array to vaoid error if empty
    $skills_selected = array();    
    $assessments = "";
    
    // checks if user is admin
    if (!query("SELECT is_staff FROM users WHERE id = ? AND is_staff = 1", $_SESSION["id"]))
    {
        redirect("login.php");
    }
    
    // gets post from user 
    if (!empty($_POST))
    {
        // check if delete action is requested
        if(isset($_POST["delete"]))
        {
            // TODO ask confirmation
            
            query("DELETE FROM projects WHERE project_id = ?", $_POST["delete"]);
            
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
               apologize("FORM'S NOT COMPLETE");
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
                apologize($lang['USER_INCLUSION_EXPLOIT']);
            }
            
            // checks file size
            if ($_FILES['submitted_file']['size'] >  MAX_UPLOAD_FILE_SIZE)
            {
                apologize($lang['MAX_SIZE_REACHED']);
            }
            
            // checks file mime type TODO: get mime type from database 
            if($_FILES['submitted_file']['type'] != 'application/pdf' && $_FILES['submitted_file']['type'] != 'application/x-pdf' 
                && $_FILES['submitted_file']['type'] != 'application/acrobat' && $_FILES['submitted_file']['type'] != 'applications/vnd.pdf' 
                && $_FILES['submitted_file']['type'] != 'text/pdf' && $_FILES['submitted_file']['type'] != 'text/x-pdf')
            {
                // dump($_FILES['submitted_file']);
                apologize($lang['UNEXPECTED_FILE_TYPE']);
            }
            
            // creates directory if doesn't exit
            if (!is_dir($upload_dir) && !mkdir($upload_dir, 0774, true))
            {
                apologize($lang['DIR_CREATION_ERROR'] . $upload_dir);
            }
            
            // uploads the file
            if (!move_uploaded_file($_FILES['submitted_file']['tmp_name'], $upload_file)) 
            {
               apologize(USER_EXPLOIT);
            }
            
            chmod($upload_file, 0774);
            
            inform("Project updated!");
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
        
        // insert or update criteria in assessment table
        
        // objective
        $count_objective = 0;
        
        $i = 0;
        
        foreach($_POST["objective"] as $objective)
        {
            
            //$check = query("SELECT id FROM assessment WHERE objective = ? AND criteria = ? AND `cursor` = ?", 
             //              $objective, $_POST["criterion"][$i], $_POST["cursor"][$i]);
                            
            query("INSERT INTO assessment (objective, criteria, `cursor`) VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE objective = objective, criteria=criteria, `cursor`=`cursor`", 
                $objective, $_POST["criterion"][$i], $_POST["cursor"][$i] );
            
            
            $assessments .= query("SELECT id FROM assessment WHERE objective = ? AND criteria = ? AND `cursor` = ?", 
            $objective, $_POST["criterion"][$i], $_POST["cursor"][$i])[0]["id"] . ",";
            
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
        
        if(isset($POST["user_eval"]))
        {
            foreach($POST["user_eval"] as $user_eval)
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
        
        if(isset($POST["data_eval_id"]))
        {        
            foreach($POST["data_eval_id"] as $data_eval)
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
           query("INSERT INTO projects VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", 
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
        
        redirect("admin.php");
    }
   
    /**
     * RENDERS EXISTING PROJECT PAGE
     *
     **/
    elseif(isset($_GET["project"]))
    {
        // gets projects for side menu
        $projects = query("SELECT * FROM projects");
        
        // gets current project
        $curr_project = query("SELECT * FROM projects WHERE project_id = ?", $_GET["project"]);
        
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
               
            // adds selected assessements TODO Does not work
            // dump($query);
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
            apologize($lang['PROJECT_NOT_FOUND']);
        }
        else
        {
            //dump($curr_project[0]);
            render_admin("add_project.php", ["title" => "Modifier un projet", "projects" => $projects, 
                "curr_project" => $curr_project[0], "skills" => $skills, "self_assessments" => $auto_assessments, 
                "skills_selected" => $skills_selected, "rows" => $rows, "objectives_list" => $objectives]);           
        }
    }
    
    /**
     * RENDERS NEW PROJECT PAGE
     *
     **/
    else
    {
        
        // gets projects for side menu
        $projects = query("SELECT * FROM projects");
        
        // TODO:  autocomplete criteria and cursors 
        
        $criteria_autocomplete = "";
        $i = 0;
        
        $curr_project = [
            "periode" => "",
            "deadline" => "",
            "project_name" => $lang['PROJECT_LEN'],
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
        render_admin("add_project.php", ["title" => $lang['ADMIN'], "projects" => $projects, 
            "skills" => $skills, "curr_project" => $curr_project, "self_assessments" => $auto_assessments, 
            "skills_selected" => $skills_selected, "objectives_list" => $objectives, "rows" => $rows]);
    }
?>
