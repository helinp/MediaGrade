<?php

    // configuration
    require("../includes/config.php"); 

    // opens table for content
    $projects = query(" SELECT projects.project_id, 									
    						project_name, 
    						periode, 
                       	 	instructions, 
                        	deadline, 
                       		class, 
                        	assessment_id, 
                        	auto_assessment_id, 
                        	user_id, file_path
                        FROM projects 
                        LEFT JOIN submitted
                        ON projects.project_id = submitted.project_id
                        AND submitted.user_id = ?
                        WHERE class = ?
                        AND is_activated = 1
                        GROUP BY project_id
                        ORDER BY projects.periode DESC, submitted.time ASC", 
                        $_SESSION["id"], 
                        $_SESSION["class"]
                        );

                            
    /**
     *   POST METHOD
     *
    */
    if (!empty($_POST))
    {
        
        $project = query("	SELECT auto_assessment_id, 
        						project_name, 
                            	extension 
                            FROM projects 
                            WHERE project_id = ?", 
                         	$_POST["project_id"]);
      
        $project_name = $project[0]["project_name"];
        
        $sanitized_user_name = sanitize_name(strtoupper($_SESSION["last_name"]) . "_" .  $_SESSION["name"]);
        $sanitized_project_name = sanitize_name($project_name);
      
        // how many files submitted?
        $n_files = count($_FILES['submitted_file']['name']);

        for ($n_file = 0 ; $n_file < $n_files ; $n_file++)
        {
         
            if($_FILES['submitted_file']['name'][$n_file])
            {
            
                // rename file LASTNAME_Name_Project.ext
                $extension = strtolower(pathinfo($_FILES['submitted_file']['name'][$n_file], PATHINFO_EXTENSION));
                $rename =  $sanitized_user_name . '_' . $sanitized_project_name . '_' . sprintf("%02d", $n_file + 1) . '.' . $extension;
                
                // puts the file in ./upload/class/periode_#   TODO: add class_year     
                $upload_dir = 'uploads/' . get_school_year() . '/' . $projects[0]["class"] . '/p' . $projects[0]["periode"] . '/';
                $upload_file = $upload_dir . $rename; // basename($_FILES['submitted_file']['name']);
                $upload_file_thumb =  $upload_dir . 'thumb_' . $rename;
                
                // checks RFI check
                if (!empty(stristr(basename($_FILES['submitted_file']['name'][$n_file]), "php")))
                {
                    if (!DEMO_VERSION) 
                    {
                      $body_message = "USER_INCLUSION_EXPLOIT \nFrom user: " . 
                        					$_SESSION["id"] . "\nip: " . 
                        					$_SERVER["REMOTE_ADDR"];
                      $subject = "Tentative de RFI";
                      
                      // WARNING admin def invalid?
                      sendamail(ADMIN_MAIL, $subject, $body_message);
                    }
                    apologize(LABEL_USER_INCLUSION_EXPLOIT);
                }
                
                // checks file size
                if ($_FILES['submitted_file']['size'][$n_file] >  MAX_UPLOAD_FILE_SIZE)
                {
                    apologize(LABEL_MAX_SIZE_REACHED);
                }
                
                // Get mime type from database 
                $mime_format = query("SELECT mime FROM files_format WHERE extension = ?", $project[0]['extension']);
                
                if (!$mime_format) apologize(LABEL_UNEXPECTED_FILE_TYPE);
                
                foreach ($mime_format as $row)
                {
                    if($_FILES['submitted_file']['type'][$n_file] != $row['mime'])
                    {
                        apologize(LABEL_UNEXPECTED_FILE_TYPE);
                    }
                }
                
                // creates directory if doesn't exit
                if (!is_dir($upload_dir) && !mkdir($upload_dir, 0755, true))
                {
                    apologize( LABEL_DIR_CREATION_ERROR . $upload_dir);
                }

                // uploads the file
                if (!move_uploaded_file($_FILES['submitted_file']['tmp_name'][$n_file], $upload_file)) 
                {
                   apologize(LABEL_USER_EXPLOIT);
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
                //elseif($extension == "mp4" || $extension == "avi" || $extension == "mov")
                //{
                //    make_video_poster($upload_file, $upload_file_thumb);
                //    chmod($upload_file_thumb, 0755);
                //}
                
                
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
                query("	BEGIN");
                query("	DELETE FROM submitted 
                		WHERE project_id = ? 
                        	AND user_id = ? 
                            AND file_name = ?", 
                      	$_POST["project_id"], 
                      	$_SESSION["id"], 
                      	$rename);
              
                query("	INSERT INTO submitted 
                			(project_id, user_id, answers, file_path, file_name) 
                        VALUES (?, ?, ?, ?, ?)",
                        $_POST["project_id"], 
                      	$_SESSION["id"], 
                      	serialize($answers), 
                      	$upload_dir, 
                      	$rename);
              
                query("	COMMIT");
                
            }
        }
        
        if (!DEMO_VERSION) 
	    {
            $subject = 'Project submitted!';
	        $body_message = "SUBMITTED PROJECT\nProject: ". $project_name 
              					."\nFrom user: " . 
              					$_SESSION["last_name"] . " " . 
              					$_SESSION["name"] . "\nip: " . 
              					$_SERVER["REMOTE_ADDR"];
	        
	        $teacher_mail = query("	SELECT email 
            						FROM users 
                                    WHERE is_staff = 1")[0]['email'];
	        
	        sendamail($teacher_mail, $subject, $body_message);        
	    }
	
	inform(LABEL_PROJECT_SAVED);
    }
            
   
    /**
     *   GET METHOD
     *
     */
    if (isset($_GET["project"]))
    {
        $project = query("  SELECT instructions
                            FROM projects 
                            WHERE project_id = ?",
                            $_GET["id"]
                            );
        //dump($project);
        
        if ($project == false)
        {
            goto welcome_page;
        }
        else
        {
            if (is_file($project[0]["instructions"]))
            {
                $content = "<object data='". $project[0]["instructions"] .
                  				"#view=FitBH&navpanes=0&pagemode=thumbs' 
                       		     type='application/pdf' 
                          		 width='80%' 
                            	height='100%'>". 
                  				LABEL_NO_PDF_READER . 
                  				$project[0]["instructions"] . 
                  				"</object>";
            }
            else
            {
                $content = "<p>" . LABEL_NO_INSTRUCTIONS . "</p>";
            }
                                
            render("content.php", ["title" => LABEL_SUBMIT, 
                                   "projects" => $projects, 
                                   "content" => $content
                                  ]);
        }
    }
    elseif (isset($_GET["results"]))
    {
        // TODO Delete * AND 
        $query = query("SELECT * FROM results, projects, assessment
                        WHERE results.project_id = projects.project_id 
                            AND assessment.id = results.skill_id 
                            AND results.project_id = ? 
                            AND results.user_id = ?
                        ORDER BY assessment.objective", 
                       $_GET["id"], 
                       $_SESSION["id"]);
        
        // check query
        if ($query == false)
        {
            render("results.php", ["title" => LABEL_RESULTS, 
                                   "projects" => $projects, 
                                   "content" => "<p>". LABEL_NO_AVAILABLE_RESULTS ."</p>"
                                  ]);
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
                $content = LABEL_NOT_GRADED_YET;
            }
            
            // dump($content);
            render("results.php", ["title" => LABEL_RESULTS, "projects" => $projects, "content" => $content[0]]);
        }
        
        
        
    }
    elseif (isset($_GET["submit"]))
    {
        // reads from project table
        $project = query("  SELECT projects.project_id, 
        						`periode`, 
                                `instructions`, 
                                `deadline`, 
                                `project_name`, 
                                `class`, 
                                `assessment_id`, 
                                `auto_assessment_id`, 
                                `assessment_type`, 
                                `skill_id`, 
                                extension, 
                                file_name, 
                                file_path, 
                                number_of_files, 
                                answers 
                            FROM projects 
                            LEFT JOIN submitted
                            ON projects.project_id = submitted.project_id
                            AND submitted.user_id = ?
                            WHERE projects.project_id = ?", 
                            $_SESSION["id"],
                            $_GET["id"]);
        
        if ($project == false)
        {
            goto welcome_page;
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
                    $questions[] = ["question" => $row[0]["question"], 
                                    "id" => $row[0]["id"]];   
                }
                else
                {
                    $questions = NULL;
                }

            }  
            
            $answers = unserialize($project[0]['answers']);
            
            render("submit.php", ["title" => LABEL_SUBMIT, 
                        "projects" => $projects,
                        "project_data" => $project, 
                        "questions" => $questions,
                        "answers" => $answers,
                        "project_id" => $_GET["submit"],
                        "extension" => $project[0]["extension"],
                        "number_of_files" => $project[0]["number_of_files"]
                        ]);                                       
        }
    }
    else
    {

        if($_SESSION["admin"]) redirect("grade.php");
        
        welcome_page:
                
        $query = query("SELECT content FROM config WHERE type = 'welcome_message'");
        $message = "";
        
        if($query)
        {
            $message = $query[0]["content"];
            
            // replace user's variables
            $message = str_replace("%user_name%", $_SESSION["name"], $message);
            $message = str_replace("%user_lastname%", $_SESSION["last_name"], $message);
        }
            
        
            
        render("content.php", ["title" => "Projets", 
                               "projects" => $projects, 
                               "content" => $message
                              ]);    

    }

    
    
?>
