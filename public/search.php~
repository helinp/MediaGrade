<?php

    // configuration
    require("../includes/config.php"); 
    header("Content-type: application/json; charset=utf-8'");
    // only works if admin logged
    
    if(!empty($_GET))
    {
        $autocomplete = "";
        
        if(isset($_GET["criterion"]))
        {
            $autocomplete = array_values(query("SELECT criteria, `cursor` FROM assessment WHERE criteria LIKE ?", $_GET["criterion"] . "%"));
        }

        print(json_encode($autocomplete, JSON_PRETTY_PRINT));
            
     }   
        

?>
