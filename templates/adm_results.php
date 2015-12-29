   <aside id="projects" class="col-md-2 bs-docs-sidebar">
       <nav itemscope itemtype="http://schema.org/SiteNavigationElement">
           <ul class="list-group small" itemprop="project">
                       <li><a class="list-group-item<?= (isset($_GET["period"]) ? "" : " active") ?>" href="results.php?class=<?= (isset($_GET["class"]) ? trim($_GET["class"], " ") : "") ?>"><?= LABEL_ALL_PERIODS ?><span class="glyphicon glyphicon-th-list pull-right"></span></a></li>
                   <?php foreach($periods as $period): ?>
                       <li><a class="list-group-item<?= (!empty($_GET["period"]) ? ($_GET["period"] == $period["periode"] ? " active" : "") : "" ); ?>" href="results.php?class=<?= (isset($_GET["class"]) ? trim($_GET["class"], " ") : "") ?>&amp;period=<?= trim($period["periode"], " ")?>"><?= LABEL_PERIOD ?> <?= $period["periode"]  ?><span class="glyphicon glyphicon-th-list pull-right"></span></a></li>
                   <?php endforeach ?>
           </ul>
       </nav>
   </aside>
     
   <main class="col-md-10">
       <div class="row"> 
           <div class="col-md-10">
       <?php if(is_array($results)):?>
           
           <table class="table table-hover ">
                    <col width="35%">
                    
                
                <thead>
                  <tr>
                    <th><?= LABEL_STUDENTS_FROM ?> <?= $users_class ?></th>
                    <?php foreach($projects as $project): ?>
                   
                        <?php foreach ($objectives as $key => $objective): ?>
                        
                        <th class="rotate" >
                            <div><span><small style="margin-left:-2.4em;"><?= (strlen($project["project_name"]) > 13 ? substr($project["project_name"], 0, 13) . '...' : $project["project_name"])?></small></span></div>
                        
                            <div><span class="text-muted"><small><span class="glyphicon glyphicon-arrow-right"></span> <?= $objective["objective"]?></small></span></div>
                        </th>
                        <?php endforeach ?>
                   
                    <?php endforeach ?>
                    <th class="rotate"><div><span><?= LABEL_AVERAGE ?></span></div></th>
                  </tr>
                </thead>
                
                <tbody>
                
                <?php foreach ($results as $user => $values): ?>
                    <tr>
                        <td><?= $user ?></td > 
                    
                    <?php foreach ($values as $project =>  $values): ?>
                    
                             
                         <?php foreach ($values as $objective => $results): ?>
                            <?php foreach ($results as $result): ?>    
                                        <td<?= ($result["AVG(user_grade)"] < 5 && is_numeric($result["AVG(user_grade)"]) ? ' class="text-danger"' : '' )?>><?= custom_round($result["AVG(user_grade)"]) ?></td >  
                             <?php endforeach ?> 
                                        
                         <?php endforeach ?>     
                    
                    <?php endforeach ?>   
                        <td><?= custom_round($averages[$user]) ?></td>
                    </tr>
                <?php endforeach ?> 
                
                </tbody>
          </table>
          </div>
      </div>
        <?php else: ?>
            <div class = "row">
                <div class="col-md-10">   
                <?= LABEL_NO_AVAILABLE_RESULTS ?>
                </div>
           </div>
        <?php endif ?>
        
        
   </main>
