
 <div class="row">
    <aside id="projects" class="col-md-2 bs-docs-sidebar">
        <nav>
            <ul class="list-group small" itemprop="project">
                    <li><a class="list-group-item alert-info<?php 
                    
                    if(!isset($curr_project["project_id"]))
                    {
                        echo(" active");
                    }; 
                    
                    ?>" href="admin.php">Nouveau projet<span class="glyphicon glyphicon-pencil pull-right"></span></a></li>
    <?php foreach ($projects as $project):?>
                    <li><a class="list-group-item<?php 
                    
                    if(isset($curr_project["project_id"]))
                    {
                        if($curr_project["project_id"] == $project["project_id"])
                        {
                            echo(" active");
                        }
                    }
                    
                    ?>" href="admin.php?project=<?= $project["project_id"]; ?>"><?= ($project["is_activated"] ? '' : '<span class="glyphicon glyphicon-ban-circle" style="color:#d9534f"></span> ')?><b><?= $project["class"] . " / " . $project["project_name"] . "</b><br /><em>P" . $project["periode"] . " / " . $project["deadline"] ?></em><span class="glyphicon glyphicon-chevron-right pull-right"></span></a></li>
    <?php endforeach?>
            </ul>
        </nav>
    </aside>
  



