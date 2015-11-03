 <div class="row">
    <aside id="projects" class="col-md-2 bs-docs-sidebar">
        <nav itemscope itemtype="http://schema.org/SiteNavigationElement">
            <h4><?= $project["project_name"]?></h4> 
            <ul class="list-group small" itemprop="project">
                <?php foreach($users as $user_list): ?>    
                    <li><a class="list-group-item <?= ($user_list["id"] == $_GET["user"]) ? "active" : ""?>" href="rating.php?rate=<?= $project["project_id"] ?>&user=<?= $user_list["id"]?>"> <?= strtoupper($user_list["last_name"]) . " " . $user_list["name"]?><span class="<?= ($user_list["is_rated"] ? "glyphicon glyphicon-check" : "glyphicon glyphicon-pencil") ?> pull-right"></a></li>
                <?php endforeach ?>
            </ul>
        </nav>
    </aside>
    
    <main class="col-md-10" id="content">
        <div class="row">
            <div class="col-md-12">       
                <h4><?= $user["class"]?> // <?= $user["name"] . " " . $user["last_name"]?></h4>
                <?php if (isset($submitted[0]["file_path"])): ?>
                <video width="50%" controls preload="metadata">
                    <source src="<?= $submitted[0]["file_path"]?>" type="video/mp4">
                    <source src="mov_bbb.ogg" type="video/ogg">
                    <p><?= $lang['NO_HTML5_VIDEO'] ?> <a href="<?= $submitted[0]["file_path"]?>"><?= $lang['HERE'] ?></a></p>
                </video>       
                <?php else: ?>
                <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-warning-sign"></span> <?= $lang['NOT_SUBMITTED'] ?></div>
                <?php endif ?>
            </div>
        </div>
         <div class="row">  
            <!-- CRITERIA -->
            <div class="col-md-12">  
            
            <form action="rating.php" method="post" id="form" role="form">
             <table id="rows" class="table table-striped">         
     	            <col width="10%">
                    <col width="15%">
                    <col width="55%">
                    <col width="10%">
     	            <thead>
	                    <tr>
		                    <th><?= $lang['SKILLS_GROUP'] ?></th>
		                    <th><?= $lang['CRITERIA'] ?></th>
		                    <th><?= $lang['CURSORS'] ?></th>
		                    <th><?= $lang['RATING'] ?></th>
	                    </tr>
	                </thead>
	                <tbody>
                     <?php foreach ($criteria as $key_obj => $objective): ?> 
                        <?php foreach ($objective as $key_cri => $criterion): ?>
                            <?php foreach ($criterion as $cursor => $val): ?>   
                        <tr>
                            <td><?= $key_obj ?></td>
                            <td><?= $key_cri ?></td>
                            <td><?= $val ?></td>
                            <td><input type="text" class="slider" data-slider-value="<?php if($is_rated) echo($rated[$cursor]["user_grade"]); 
                            else echo("5") ?>" value="<?= ($is_rated ? $rated[$cursor]["user_grade"] : "5") ?>
                            " data-slider-min="0" data-slider-max="10" name="eval[]" >
                                <input type="hidden" name="eval_cursor[]" value="<?= $id_criterion[$cursor]?>">
                            </td>
                        </tr>
                            <?php endforeach ?> 
                        <?php endforeach ?> 
                     <?php endforeach ?> 
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-save"></span><?=$lang['SAVE_RATING']?></button>        
            </div> 
            <!-- END CRITERIA -->
            <input type="hidden" name="user_id" value="<?= $user["id"] ?>">
            <input type="hidden" name="project" value="<?= $project["project_id"] ?>">    
        </form>
        </div>     
    </main>
    
    <script src="js/bootstrap-slider.js"></script>
    <script>


    $('.slider').slider({
                
                formater: function(value) {
                
                switch(value){
                
                    case 10:
                        return("<?= $lang['VOTE_10']?> (" + value + ")" );
                        break;
                    case 9:
                        return("<?= $lang['VOTE_09']?> (" + value + ")" );
                        break;
                    case 8:
                        return("<?= $lang['VOTE_08']?> (" + value + ")" );
                        break;
                    case 7:
                        return("<?= $lang['VOTE_07']?> (" + value + ")" );
                        break;
                    case 6:
                        return("<?= $lang['VOTE_06']?> (" + value + ")" );
                        break;
                    case 5:
                        return("<?= $lang['VOTE_05']?> (" + value + ")" );
                        break;
                    case 1: case 2: case 3: case 4:
                        return("<?= $lang['VOTE_04']?> (" + value + ")" );
                        break;
                    case 0:
                        value--;
                        return("<?= $lang['VOTE_00']?>");
                        break;
                }
              }
            });
            

    </script>
        
    <script>
    myVideoPlayer.addEventListener('loadedmetadata', function() {
    console.log(videoPlayer.duration);
    });
    </script>
