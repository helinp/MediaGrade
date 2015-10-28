   <main class="col-md-10">
       
       <?php if (is_array($content)):?>
           
           <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Compétence</th>
                    <th>Critère</th>
                    <th>Curseur (L'élève a:)</th>
                    <th>Résultat</th>
                  </tr>
                </thead>
                
                
         
                
                <tbody>
                 
                
                 
            
              <?php foreach($content as $result)
                    {
                        //dump($result);
                        $percentage = 0;
                        if ($result["user_grade"] !== 0) $percentage = ($result["user_grade"] / $result["max_grade"]) * 100;
                        
                        $class = "";
                        
                        if ($percentage > 79) $class = "success";
                        if ($percentage < 49) $class = "warning";
                        if ($percentage < 30) $class = "danger";
                        
                        
                        echo 
                        ( 
                        "<tr class=" . $class .">"
                                . "<td>" . $result["objective"] . "</td>\n"
                                . "<td>" . $result["criteria"] . "</td>\n"
                                . "<td>" . $result["cursor"] . "</td>\n"
                                . "<td>" . $result["user_grade"] . " / " . $result["max_grade"] . "</td>\n"
                        . "</tr>"
                        );
                    }       
                 ?>
            
                </tbody>
          </table>
        <?php else: ?>
            <?= $content ?>
        <?php endif ?>
        
        
   </main>
