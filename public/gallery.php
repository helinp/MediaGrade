<?php

    // configuration
    require("../includes/config.php"); 
                
    // avoid question mark char coding error
    query("SET NAMES utf8");    

    // TODO add class
    
    // declares variable to use it out of scope
    $medias = [];
    
    // opens table to get content
    $users = query("SELECT * FROM users");
    
    // fills variable $media[]
    foreach ($users as $user)
    {
        // gets  users projects
        $query = 'SELECT file_path, project_id  FROM submitted ';

        // get only user's project if requested
        if (isset($_GET["my"]))
        {
               settype($_SESSION["id"], 'integer');
               $query .= ' WHERE user_id = ' . $_SESSION["id"];
        }
        
        // makes query
		// dump($query);
		$files = query($query);

		// fills variable
        foreach ($files as $file)
        {
            
            $projects = query("SELECT project_name FROM projects WHERE project_id = ?", $file["project_id"]);
            
            // empty results
            if(!isset($projects[0]["project_name"])) $projects[0]["project_name"] = "";
            
            $medias[] = [             
                "file" => $file["file_path"],
                "name" => $user["name"], 
                "class" => $user["class"],
                "last_name" => $user["last_name"],
                "project_name" => $projects[0]["project_name"]
                ];
        };
    };
    
    //dump($medias);
    
    render("gallery.php", ["title" => "Hall of fame", "medias" => $medias], true);        
?>
