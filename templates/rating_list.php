

        <aside id="projects" class="col-md-2 bs-docs-sidebar">
        <nav itemscope itemtype="http://schema.org/SiteNavigationElement">
            <ul class="list-group small" itemprop="project">
                            <li><a class="list-group-item<?= (isset($_GET["class"]) ? "" : " active") ?>" href="rating.php">Toutes les classes<span class="glyphicon glyphicon-pencil pull-right"></a></li>
                    <?php foreach($classes as $class): ?>
                        <?php if(!empty($class["class"])): ?>
                            <li><a class="list-group-item<?= (isset($_GET["class"]) ? ($_GET["class"] == $class["class"] ? " active" : "") : ""); ?>" href="rating.php?class=<?= trim($class["class"], " ")?>"><?= $class["class"]  ?><span class="glyphicon glyphicon-pencil pull-right"></a></li>
                        <?php endif ?>
                    <?php endforeach ?><li>
            </ul>
        </nav>
    </aside>
    
    <main class="col-md-10" id="content">
        
        <div class="row">
             <?php foreach($users as $user): ?>
            <div class="col-md-3 thumb"> 
              <div class="well well-bkg">  
                <dl class="rating-list">
                    <dt><?= strtoupper($user["last_name"]) . " ". substr($user["name"], 0, 1) . "." ?></dt>
                    
                    <?php foreach($user["projects"] as $project): ?>
                        <dd>
                            <?= ($project["is_rated"] ? '<span style="color:green" class="glyphicon glyphicon-check"></span> ' : ($project["is_submitted"] ? '<span style="color:red" class="glyphicon glyphicon-edit"></span> ' : '<span style="color:gray" class="glyphicon glyphicon-inbox"></span> ')); ?>
                            <a href="?rate=<?= $project["project_id"]?>&user=<?= $user["id"]?>" class="text-muted" ><small>P<?= $project["periode"]?> - </small>  <?= $project["project_name"]?></a>
                        </dd>
                    <?php endforeach ?>
                    
                    
                    
                
                </dl>
            </div>
            </div>
            <?php endforeach ?>
        </div>     
    </main>
    
   
