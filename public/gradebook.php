<?php

    // configuration
    require("../includes/config.php"); 
                
    // avoid question mark char coding error
    query("SET NAMES utf8");    

    // opens table for content
    $projects = query("SELECT * FROM projects");
    
   
    /**
     *   GET METHOD
     *
    */
    if (false)
    {
      
        
    }
    else
    {
        // gets grades, projects names, semestre, 
        /*$query["FAIRE"] = query("SELECT * FROM results WHERE user_id = ? AND objective = ?", $_SESSION["id"], "FAIRE");
        $query["APPRECIER"] = query("SELECT * FROM results WHERE user_id = ? AND objective = ?", $_SESSION["id"], "APPRECIER");
        $query["REGARDER"] = query("SELECT * FROM results WHERE user_id = ? AND objective = ?", $_SESSION["id"], "REGARDER");
        $query["CONNAITRE"] = query("SELECT * FROM results WHERE user_id = ? AND objective = ?", $_SESSION["id"], "CONNAITRE");
        $query["EXPRIMER"] = query("SELECT * FROM results WHERE user_id = ? AND objective = ?", $_SESSION["id"], "S'EXPRIMER");
        */
        
        // TODO make array whith loop
        $results[] = ["F" => query("SELECT user_grade, date FROM results WHERE user_id = ? AND skill_id = ? ORDER BY date ASC", $_SESSION["id"], "1"), 
                      "A" => query("SELECT user_grade, date FROM results WHERE user_id = ? AND skill_id  = ? ORDER BY date ASC", $_SESSION["id"], "2"), 
                      "R" => query("SELECT user_grade, date FROM results WHERE user_id = ? AND skill_id  = ? ORDER BY date ASC", $_SESSION["id"], "3"), 
                      "C" => query("SELECT user_grade, date FROM results WHERE user_id = ? AND skill_id  = ? ORDER BY date ASC", $_SESSION["id"], "4"),
                      "E" => query("SELECT user_grade, date FROM results WHERE user_id = ? AND skill_id  = ? ORDER BY date ASC", $_SESSION["id"], "5")
                      
                     ];
        // dump($results[0]);
        // $results[0]["F"][0]["user_grade"]
        // render page
        render("gradebook.php", ["title" => "Compétences", "results" => $results[0]], true);    
    }

    
     
?>
