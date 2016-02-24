<?php

    // configuration
    require('../includes/config.php'); 
                
    // avoid question mark char coding error
    query('SET NAMES utf8');    

    // TODO add class
    
    // declares variable to use it out of scope
    $medias = [];
    
    // Get
    
    if (isset($_GET['my']) && isset($_SESSION['id']))
    {
        $projects = query(' SELECT user_id, file_path, file_name, submitted.project_id, project_name, name, last_name, users.class, extension
                            FROM submitted 
                            LEFT JOIN users
                                ON users.id = submitted.user_id
                            LEFT JOIN projects
                                ON submitted.project_id = projects.project_id
                            WHERE users.id = ?
                            LIMIT 16',
                            $_SESSION['id']);   
    }
    else
    {
        $projects = query(' SELECT user_id, file_path, file_name, submitted.project_id, project_name, name, last_name, users.class, extension
                            FROM submitted 
                            LEFT JOIN users
                                ON users.id = submitted.user_id
                            LEFT JOIN projects
                                ON submitted.project_id = projects.project_id
                            ORDER BY users.class, submitted.project_id, users.name
                            LIMIT 16');       
    }

    // fills variable $media[]
    foreach ($projects as $project)
    {
        // empty results
        if(!isset($projects[0]['project_name'])) $projects[0]['project_name'] = '';
           
        $medias[] = [             
            'file' => $project['file_path'] . $project['file_name'],
            'thumbnail' => $project['file_path'] . 'thumb_' . $project['file_name'],
            'name' => $project['name'], 
            'class' => $project['class'],
            'last_name' => $project['last_name'],
            'project_name' => $project['project_name'],
            'extension' => $project['extension']
            ];
    };
    
    render('gallery.php', ['title' => 'Hall of fame', 'medias' => $medias]);        
?>
