    <aside id="projects" class="col-md-2 bs-docs-sidebar">
        <nav itemscope itemtype="http://schema.org/SiteNavigationElement">
            <h4><?= $project["project_name"]?></h4> 
            <ul class="list-group small" itemprop="project">
                <?php foreach($users as $user_list): ?>    
                    <li><a class="list-group-item <?= ($user_list["id"] == $_GET["user"]) ? "active" : ""?>" href="grade.php?rate=<?= $project["project_id"] ?>&amp;user=<?= $user_list["id"]?>"> <?= strtoupper($user_list["last_name"]) . " " . $user_list["name"]?><span class="pull-right <?= ($user_list["is_submitted"] ? ($user_list["is_rated"] ? "glyphicon glyphicon-check\" style=\"color:green;\"" : "glyphicon glyphicon-pencil\"") : "\"") ?>></span></a></li>
                <?php endforeach ?>
            </ul>
        </nav>
    </aside>
    
    <main class="col-md-10" id="content">
        <div class="row">
            <div class="col-md-12">       
                <h4><?= $user["class"]?> // <?= $user["name"] . " " . $user["last_name"]?></h4>
                
                <?php if (isset($submitted[0]["file_path"])): ?>
                    <?php if ($extension == "mp4" || $extension == "mov" || $extension == "avi"): ?>
                        <video width="50%" controls preload="metadata">
                            <source src="<?= $submitted[0]["file_path"] . $submitted[0]["file_name"] ?>" type="video/mp4">
                            <p><?= LABEL_NO_HTML5_VIDEO ?> <a href="<?= $submitted[0]["file_path"]?>"><?= LABEL_HERE ?></a></p>
                        </video>       
                    <?php elseif($extension == "jpg" || $extension == "jpeg" || $extension == "png" || $extension == "gif"): ?>
                    
                        <img alt="<?= $user["name"] . " " . $user["last_name"] . " / " . $project["project_name"]?>" src="<?= $submitted[0]["file_path"] . "thumb_" . $submitted[0]["file_name"]?>" />
                    
                    <?php endif ?>
                <pre style="margin-top:1em"><a href="<?= $submitted[0]["file_path"] . $submitted[0]["file_name"] ?>"><?= $submitted[0]["file_path"] . $submitted[0]["file_name"] ?></a></pre>
                <?php else: ?>
                <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-warning-sign"></span> <?= LABEL_NOT_SUBMITTED ?></div>
                <?php endif ?>
            
            </div>
        </div>
         <div class="row">  
             
            <!-- CRITERIA -->
            <div class="col-md-12">  
                
                <?php if(!empty($self_assessments)): ?>
                <h4>Auto-évaluation</h4>
                <?php foreach($self_assessments as $self_assessment):?>
                    <?= "<b>" . $self_assessment["question"] . "</b><p>&quot;". $self_assessment["answer"] ."&quot;</p>"?>
                <?php endforeach ?>
                
                <hr />
                <?php endif ?>
                <h4>Évaluation</h4>
                <form action="grade.php" method="post" id="form">
                 <table id="rows" class="table table-striped">         
         	            <col width="10%">
                        <col width="15%">
                        <col width="55%">
                        <col width="10%">
         	            <thead>
	                        <tr>
		                        <th><?= LABEL_SKILLS_GROUP ?></th>
		                        <th><?= LABEL_CRITERIA ?></th>
		                        <th><?= LABEL_CURSORS ?></th>
		                        <th><?= LABEL_RATING ?></th>
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
                    
                <!-- END CRITERIA -->
                <hr />
                
                <button type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-save"></span><?= LABEL_SAVE_RATING ?></button>        
                <input type="hidden" name="user_id" value="<?= $user["id"] ?>">
                <input type="hidden" name="project" value="<?= $project["project_id"] ?>">    
            </form>
        </div> 
      </div>      
    </main>
    
    <script src="js/bootstrap-slider.js"></script>
    <script>


    $('.slider').slider({
                
                formater: function(value) {
                
                switch(value){
                
                    case 10:
                        return("<?= LABEL_VOTE_10 ?> (" + value + ")" );
                        break;
                    case 9:
                        return("<?= LABEL_VOTE_09 ?> (" + value + ")" );
                        break;
                    case 8:
                        return("<?= LABEL_VOTE_08 ?> (" + value + ")" );
                        break;
                    case 7:
                        return("<?= LABEL_VOTE_07 ?> (" + value + ")" );
                        break;
                    case 6:
                        return("<?= LABEL_VOTE_06 ?> (" + value + ")" );
                        break;
                    case 5:
                        return("<?= LABEL_VOTE_05 ?> (" + value + ")" );
                        break;
                    case 1: case 2: case 3: case 4:
                        return("<?= LABEL_VOTE_04 ?> (" + value + ")" );
                        break;
                    case 0:
                        value--;
                        return("<?= LABEL_VOTE_00 ?>");
                        break;
                }
              }
            });
            

    </script>
        
