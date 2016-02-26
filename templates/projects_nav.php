        <!--<aside id="projects" class="col-md-2 bs-docs-sidebar">
        <?php foreach ($projects as $project):?>
            <nav>
                <ul class="list-group small nav nav-stacked">
                        <li><h5 style="margin-bottom:0"><?= "P" . $project["periode"] . " / " . $project["project_name"]?></h5>
                            <?= (!empty($project["user_id"]) ? '<span class="glyphicon glyphicon-ok" style="color:#5cb85c;"></span> <em  style="color:#5cb85c;">' . LABEL_SUBMITTED_PROJECT . '</em>' : '<em>' . 'Deadline: ' . $project["deadline"] . '</em>') ?>
                        </li>
                        <li>
                                <ul>
                                    <li><a class="list-group-item <?php if(isset($_GET["project"])) {if($_GET["project"] == $project["project_id"]){echo("active");}};?>" href="?project=<?= $project["project_id"]?>"><span class="glyphicon glyphicon-chevron-right pull-right"></span><?= LABEL_INSTRUCTIONS ?></a></li>
                                    <li> <a class="list-group-item <?php if(isset($_GET["submit"]))   {if($_GET["submit"] == $project["project_id"]){echo("active");}};?>"  href="?submit=<?= $project["project_id"]?>"><span class="glyphicon glyphicon-chevron-right pull-right"></span><?= LABEL_SUBMIT ?></a></li>
                                    <li><a class="list-group-item <?php if(isset($_GET["results"])) {if($_GET["results"] == $project["project_id"]){echo("active");}};?>"  href="?results=<?= $project["project_id"]?>"><span class="glyphicon glyphicon-chevron-right pull-right"></span><?= LABEL_RESULTS ?></a></li>
                                </ul>
                        </li>
                </ul>
            </nav>
        <?php endforeach?>    
        </aside>
-->
        <aside id="projects" class="col-md-2 bs-docs-sidebar">
        <div style="margin-top:2em;"></div>
        <?php foreach ($projects as $project):?>
            <nav>
                            <div class="list-group"  >
                                <a href="?id=<?= $project["project_id"] ?>&project" class="list-group-item <?= (@$_GET['id'] == $project['project_id'] ? 'active' : '') ?>">
                                    <h5 class="list-group-item-heading"><?= "P" . $project["periode"] . " / " . $project["project_name"]?></h5>
                                    <p class="list-group-item-text"><?= (!empty($project["user_id"]) ? '<span class="glyphicon glyphicon-ok" style="color:#5cb85c;"></span> <em>' . LABEL_SUBMITTED_PROJECT . '</em>' : '<em>' . 'Deadline: ' . $project["deadline"] . '</em>') ?></p>
                                </a>
                            </div>
            </nav>
        <?php endforeach?>    
        </aside>
        
          <?php if(isset($_GET['id'])): ?>  
        
            <div class="col-md-10">
                                <ul style="margin-left:0;margin-top:1em;" class="nav nav-tabs nav-justified">
                                    <li <?= (isset($_GET["project"]) ? 'class="active"' : '') ?>><a href="?id=<?= $_GET['id'] ?>&project"><?= LABEL_INSTRUCTIONS ?></a></li>
                                    <li <?= (isset($_GET["submit"]) ? 'class="active"' : '') ?>> <a href="?id=<?= $_GET['id'] ?>&submit"><?= LABEL_SUBMIT ?></a></li>
                                    <li <?= (isset($_GET["results"]) ? 'class="active"' : '') ?>><a href="?id=<?= $_GET['id'] ?>&results"><?= LABEL_RESULTS ?></a></li>
                                </ul>
            </div>
            
         <?php endif ?>
