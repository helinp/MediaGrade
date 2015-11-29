<?php

    // configuration
    require("../includes/config.php"); 
    header("Content-type: application/json; charset=utf-8'");
    
    if(!empty($_GET))
    {
        $autocomplete = "";
        
        if(isset($_GET["criterion"]))
        {
            $autocomplete = array_values(query("SELECT DISTINCT criteria FROM assessment WHERE criteria LIKE ? LIMIT 5", "%" . $_GET["criterion"] . "%"));
        }
        if(isset($_GET["cursor"]))
        {
            $autocomplete = array_values(query("SELECT DISTINCT `cursor` FROM assessment WHERE `cursor` LIKE ? LIMIT 5", "%" . $_GET["cursor"] . "%"));
        }

        print(json_encode($autocomplete, JSON_PRETTY_PRINT));
            
     }   
        

?>
