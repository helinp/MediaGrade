<?php

   // configuration
   require("../includes/config.php"); 
                
   // avoid question mark char coding error
   query("SET NAMES utf8");    
    
   // declare empty variable in case of no results
   $results = "";
    
   // opens table for content
   $projects = query("SELECT * FROM projects");
    
   $query = query("SELECT AVG(user_grade), assessment.max_vote, date, objective
                     FROM  assessment
                     LEFT JOIN results 
                     ON assessment.id = skill_id
                     WHERE results.user_id = ?
                     GROUP BY date, objective
                     ORDER BY date ASC", 
                     $_SESSION["id"]);
   
    
    foreach ($query as $key => $node)
    {
        $results[$node["objective"]] = array();
    }
    foreach ($query as $key => $node)
    {
        array_push($results[$node["objective"]], $node);
    }
    
    // dump($results);
    
    /**
     *   GET METHOD
     *
    */

    // render page
    render("gradebook.php", ["title" => "Compétences", "results" => $results], true);    

    
     
?>
