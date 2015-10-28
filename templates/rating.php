 <div class="row">
    <aside id="projects" class="col-md-2 bs-docs-sidebar">
        <nav itemscope itemtype="http://schema.org/SiteNavigationElement">
            <ul class="list-group small" itemprop="project">
                    <li><a class="list-group-item" href="config.php?skills">Compétences<span class="glyphicon glyphicon-pencil pull-right"></a></li>
                    <li><a class="list-group-item" href="config.php?users">Liste élèves<span class="glyphicon glyphicon-pencil pull-right"></a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="col-md-10" id="content">
        
        <h4><?= $project["project_name"]?> // <?= $user["name"]?> <?= $user["last_name"]?></h4>
        
        <div class="row">
            <div class="col-md-8">       
                
                <?php if (!isset($submitted[0]["file_path"])): ?>
                <video width="100%" controls preload="metadata">
                    <source src="<?= $submitted[0]["file_path"]?>" type="video/mp4">
                    <source src="mov_bbb.ogg" type="video/ogg">
                    Your browser does not support HTML5 video.
                </video>       
                <?php else: ?>
                Media not submitted
                <?php endif ?>
            </div>
            
            <!-- CRITERIA -->
            <?php foreach ($criteria as $criterion)?>
            <div class="col-md-4"> 
                <dl>
                    <dt><?= $criterion["criterion"] ?></dt>
                    <dd><?= $criterion["cursor"] ?></dd>
                    <dd><input type="text" class="span2" value="4" id="sl1" ></dd>
                </dl>
            </div>
            <!-- END CRITERIA -->
        </div>     
    </main>
    
    <script src="js/bootstrap-slider.js"></script>
    <script>

    $('#sl1').slider({
              formater: function(value) {
                return 'Current value: '+value;
              }
            });
    </script>
        
    <script>
    myVideoPlayer.addEventListener('loadedmetadata', function() {
    console.log(videoPlayer.duration);
    });
    </script>
