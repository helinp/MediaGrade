 <div class="row">

        <aside id="projects" class="col-md-2 bs-docs-sidebar">
        <nav itemscope itemtype="http://schema.org/SiteNavigationElement">
            <ul class="list-group small" itemprop="project">
                    <?php foreach($classes as $class): ?>
                        <?php if(!empty($class["class"])): ?>
                            <li><a class="list-group-item" href="rating.php?class=<?= trim($class["class"], " ")?>"><?= $class["class"]  ?><span class="glyphicon glyphicon-pencil pull-right"></a></li>
                        <?php endif ?>
                    <?php endforeach ?><li>
            </ul>
        </nav>
    </aside>
    
    <main class="col-md-10" id="content">
        
        <div class="row">
             <?php foreach($users as $user): ?>
            <div class="col-md-3 thumb"> 
              <div class="well  bg-warning">  
                <dl>
                    <dt><?= $user["last_name"] . " ". substr($user["name"], 0, 2) . "." ?></dt>
                    
                    <?php foreach($user["projects"] as $project): ?>
                        <dd><a href="?rate=<?= $project["project_id"]?>&user=<?= $user["id"]?>" class="text-muted" ><?= $project["project_name"]?></a></dd>
                    <?php endforeach ?>
                    
                    
                    
                    <dd><span style="font-size:0.7em;" class="glyphicon glyphicon-pencil"></span> <a href="#"  class="text-info">Projet 2</a> </dd>
                
                </dl>
            </div>
            </div>
            <?php endforeach ?>
        </div>     
    </main>
    
   
