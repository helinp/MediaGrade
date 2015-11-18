<?php

    // configuration
    require("../includes/config.php"); 

    // opens table for content
    $projects = query(" SELECT projects.project_id, project_name, periode, instructions, deadline, class, assessment_id, auto_assessment_id, user_id, file_path
                        FROM projects 
                        LEFT JOIN submitted
                        ON projects.project_id = submitted.project_id
                        AND submitted.user_id = ?
                        WHERE class = ?
                        AND is_activated = 1", 
                        $_SESSION["id"], 
                        $_SESSION["class"]
                        );

                            
    /**
     *   POST METHOD
     *
    */
    if (!empty($_POST))
    {
        
        $project = query("SELECT auto_assessment_id, project_name FROM projects WHERE project_id = ?", $_POST["project_id"]);
        $project_name = $project[0]["project_name"];
        
        $sanitized_user_name = preg_replace("/[^a-zA-Z0-9_]/", "x", strtoupper($_SESSION["last_name"]) . "_" .  $_SESSION["name"]);
        $sanitized_project_name = preg_replace("/[^a-zA-Z0-9]/", "_", $project_name);
        $sanitized_project_name = preg_replace("/_+/", "_", $sanitized_project_name ); 
        
        // rename file LASTNAME_Name_Project.ext
        $extension = strtolower(pathinfo($_FILES['submitted_file']['name'], PATHINFO_EXTENSION));
        $rename =  $sanitized_user_name . "_" . $sanitized_project_name . "." . $extension;
        
        // puts the file in ./upload/class/periode_#   TODO: add class_year     
        $upload_dir = "uploads/" . $projects[0]["class"] . "/p" . $projects[0]["periode"] . "/";
        $upload_file = $upload_dir . $rename; // basename($_FILES['submitted_file']['name']);
        $upload_file_thumb =  $upload_dir . "thumb_" . $rename;
        
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
        if($_FILES['submitted_file']['type'] != 'image/jpeg' && $_FILES['submitted_file']['type'] != 'video/mp4')
        {
            apologize($lang['UNEXPECTED_FILE_TYPE']);
        }
        
        // creates directory if doesn't exit
        if (!is_dir($upload_dir) && !mkdir($upload_dir, 0755, true))
        {
            apologize( $lang['DIR_CREATION_ERROR'] . $upload_dir);
        }

        // uploads the file
        if (!move_uploaded_file($_FILES['submitted_file']['tmp_name'], $upload_file)) 
        {
           apologize($lang['USER_EXPLOIT']);
        }
        
        // sets files & directory mode
        chmod($upload_file, 0755);
        chmod($upload_dir, 0755);
        
        // create thumbnail
        if($extension == "jpg" || $extension == "png" || $extension == "gif" || $extension == "jpeg" ) 
        { 
            make_thumbnail($upload_file, $upload_file_thumb, 500);
            chmod($upload_file_thumb, 0755); 
        }
        
        
        
        // fills variable whith entries
        $answers = "";
        if (!empty($project[0]["auto_assessment_id"]))
        {
            $auto_assessment_ids = explode(",", $project[0]["auto_assessment_id"]);
            
            // puts answers into array
            foreach ($auto_assessment_ids as $auto_assessment_id)
            {
                 $row = query("SELECT * FROM auto_assesment WHERE id = ?", $auto_assessment_id);
                 $answers[] =  [
                    "id" => $row[0]["id"], 
                    "answer" => htmlspecialchars($_POST["auto_assessment_" . $row[0]["id"]])
                    ];
            }   
        }
        
        // saves serialized answers and path to database
        query("BEGIN");
        query("DELETE FROM submitted WHERE project_id = ? AND user_id = ?", $_POST["project_id"], $_SESSION["id"]);
        query("INSERT INTO submitted (project_id, user_id, answers, file_path, file_name) 
                VALUES (?, ?, ?, ?, ?)",
                $_POST["project_id"], $_SESSION["id"], serialize($answers), $upload_dir, $rename);
        query("COMMIT");
        
        sendamail(ADMIN_MAIL, "Project submitted!", "SUBMITTED PROJECT\nProject: ". $project_name ."\nFrom user: " . $_SESSION["last_name"] . " " . $_SESSION["name"] . "\nip: " . $_SERVER["REMOTE_ADDR"]);        
        inform($lang['PROJECT_SAVED']);
    }
            
   
    /**
     *   GET METHOD
     *
    */
    if (!empty($_GET["project"]))
    {
        $project = query("  SELECT instructions
                            FROM projects 
                            WHERE project_id = ?",
                            $_GET["project"]
                            );
        //dump($project);
        
        if ($project == false)
        {
            apologize($lang['USER_EXPLOIT']);
        }
        else
        {
            if (is_file($project[0]["instructions"]))
            {
                $content = "<object data='". $project[0]["instructions"] ."#view=FitBH&navpanes=0&pagemode=thumbs' 
                            type='application/pdf' 
                            width='80%' 
                            height='100%'>

                            ". $lang['NO_PDF_READER'] . $project[0]["instructions"] . "

                            </object>";
            }
            else
            {
                $content = "<p>" . $lang['NO_INSTRUCTIONS'] . "</p>";
            }
                                
            render_projects("content.php", ["title" => $lang['SUBMIT'], "projects" => $projects, "content" => $content]);
        }
    }
    elseif (!empty($_GET["results"]))
    {
        // TODO Delete * AND 
        $query = query("SELECT * FROM results, projects, assessment
                        WHERE results.project_id = projects.project_id 
                            AND assessment.id = results.skill_id 
                            AND results.project_id = ? 
                            AND results.user_id = ?
                        ORDER BY assessment.objective", $_GET["results"], $_SESSION["id"]);
        
        // check query
        if ($query == false)
        {
            render_projects("results.php", ["title" => $lang['RESULTS'], "projects" => $projects, "content" => "<p>Aucun résultat disponible.</p>"]);
        }
        else
        {
            //dump($query);
                
            // check if project graded
            if (isset($query[0]["project_name"]) && isset($query[0]["user_grade"]))
            {
                $content [] = $query;
            }
            // if not, inform user.
            else
            {
                $content = $lang['NOT_GRADED_YET'];
            }
            
            // dump($content);
            render_projects("results.php", ["title" => $lang['RESULTS'], "projects" => $projects, "content" => $content[0]]);
        }
        
        
        
    }
    elseif (!empty($_GET["submit"]))
    {
        // reads from project table
        $project = query("  SELECT projects.project_id, `periode`, `instructions`, `deadline`, `project_name`, `class`, 
                                `assessment_id`, `auto_assessment_id`, `assessment_type`, `skill_id`, extension, file_name, file_path  
                            FROM projects 
                            LEFT JOIN submitted
                            ON projects.project_id = submitted.project_id
                            AND submitted.user_id = ?
                            WHERE projects.project_id = ?", 
                            $_SESSION["id"],
                            $_GET["submit"]);
        
        if ($project == false)
        {
            apologize($lang['USER_EXPLOIT']);
        }
        else
        {         
            // fills variable with entries
            $auto_assessment_ids = explode(",", $project[0]["auto_assessment_id"]);
                  
            foreach ($auto_assessment_ids as $auto_assessment_id)
            {
                
                $row = query("SELECT * FROM auto_assesment WHERE id = ?", $auto_assessment_id);
                
                if (isset($row[0]))
                {
                    $questions[] = ["question" => $row[0]["question"], "id" => $row[0]["id"]];   
                }
                else
                {
                    $questions = NULL;
                }

            }  
            render_projects("submit.php", ["title" => $lang['SUBMIT'], 
                        "projects" => $projects,
                        "project_data" => $project[0], 
                        "questions" => $questions, 
                        "project_id" => $_GET["submit"],
                        "extension" => $project[0]["extension"]
                        ]);                                       
        }
    }
    else
    {

        if($_SESSION["admin"]) redirect("admin.php");
        render_projects("content.php", ["title" => "Projets", "projects" => $projects, "content" => $lang['HOWTO_PROJECTS']]);    

    }

    
    
?>
