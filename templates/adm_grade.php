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
            </div>
        </div>
        
        <div class="row">
                  
            <?php  if(count($submitted) == 1) {$max_files = 1 ; $cols = 10;} 
                   elseif(count($submitted) < 7 && count($submitted)) { $max_files = 6 ; $cols = 4;} 
                   else { $max_files = 12 ; $cols = 2;} 
            ?>
            
            
                <?php foreach(range(0, $max_files - 1) as $count): ?>
                <div class="col-md-<?= $cols ?>"> 
            
                    <?php if (isset($submitted[$count]["file_path"])): ?>
                        <?php if ($extension == "mp4" || $extension == "mov" || $extension == "avi"): ?>
                            <video width="50%" controls preload="metadata">
                                <source src="<?= $submitted[$count]["file_path"] . $submitted[$count]["file_name"] ?>" type="video/mp4">
                                <p><?= LABEL_NO_HTML5_VIDEO ?> <a href="<?= $submitted[$count]["file_path"]?>"><?= LABEL_HERE ?></a></p>
                            </video>       
                        <?php elseif($extension == "jpg" || $extension == "jpeg" || $extension == "png" || $extension == "gif"): ?>
                        
                            <a href="<?= $submitted[$count]["file_path"] . $submitted[$count]["file_name"] ?>">
                                <img alt="<?= $user["name"] . " " . $user["last_name"] . " / " . $project["project_name"]?>" 
                            
                                src="<?= $submitted[$count]["file_path"] . "thumb_" . $submitted[$count]["file_name"]?>" />
                                </a>
                        
                        <?php endif ?>
                     <?php endif ?>
                </div>
                <?php endforeach ?>
              
             
       </div>
       <div class="row">
            <div class="col-md-12">         
                <?php if (!isset($submitted[0]["file_path"])): ?>
                <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-warning-sign"></span> <?= LABEL_NOT_SUBMITTED ?></div>
              <?php else: ?>
              	 <div class="alert alert-info"><?= LABEL_SUBMITTED_ON . $last_submitted_date ?></div>
              <?php endif ?>
            </div>
           

        </div>
        
        
         <div class="row">  
             
            <!-- CRITERIA -->
            <div class="col-md-12">  
                
                <?php if(!empty($self_assessments)): ?>
                <h4><?=  LABEL_SELF_ASSESSMENT ?></h4>
                <?php foreach($self_assessments as $self_assessment):?>
                    <?= "<b>" . $self_assessment["question"] . "</b><p>&quot;". $self_assessment["answer"] ."&quot;</p>"?>
                <?php endforeach ?>
                
                <hr />
                <?php endif ?>
                <h4><?=  LABEL_ASSESSMENT ?></h4>
                <form action="grade.php" method="post" id="form">
                 <table id="rows" class="table table-striped">         
         	            <col width="10%">
                        <col width="15%">
                        <col width="40%">
                        <col width="25%">
         	            <thead>
	                        <tr>
		                        <th><?= LABEL_SKILLS_GROUP ?></th>
		                        <th><?= LABEL_CRITERIA ?></th>
		                        <th><?= LABEL_CURSORS ?></th>
		                        <th><?= LABEL_RATING ?></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    <?php $i = 0;?>
                         <?php foreach ($criteria as $key_obj => $objective): ?> 
                            <?php foreach ($objective as $key_cri => $criterion): ?>
                                <?php foreach ($criterion as $cursor => $val): ?>   
                                    
                            <tr>
                                <td><?= $key_obj ?></td>
                                <td><?= $key_cri ?></td>
                                <td><?= $val ?></td>
                                <td><!-- <input type="text" class="slider" data-slider-value="<?= ($is_rated ? $rated[$cursor]["user_grade"] : $max_vote[$i] / 2) ?>"
                                     value="<?= ($is_rated ? $rated[$cursor]["user_grade"] : $max_vote[$i] / 2 ) ?>"
                                     data-slider-min="0" data-slider-max="<?= $max_vote[$i] ?>" name="eval[]" > -->
                                    
                                    <input name="eval[]" class="range-assessment" type="range" value="<?= ($is_rated ? $rated[$cursor]["user_grade"] : $max_vote[$i] / 2) ?>" max="<?= $max_vote[$i] ?>" min="0" step="1">
                                    <span class="small" data-onload="genAssessment()"></span><input row="<?= $i ?>" type="hidden" name="max_vote[]" value="<?= $max_vote[$i] ?>">
                                    <input type="hidden" name="eval_id[]" value="<?= $id_criterion[$cursor]?>">
                                    
                                </td>
                            </tr>
                                    <?php $i++; ?>
                                <?php endforeach ?> 
                            <?php endforeach ?> 
                         <?php endforeach ?> 
                        </tbody>
                    </table>
                    
                <!-- END CRITERIA -->
                <hr />
                <h4><?=  LABEL_COMMENT ?></h4>
                <textarea rows="5" cols="10" name="comment"><?= $comment ?></textarea>
                
                <hr />                
                <button type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-save"></span><?= LABEL_SAVE_RATING ?></button>        
                <input type="hidden" name="submitted_project_date" value="<?= $submitted[0]["time"] ?>">
                <input type="hidden" name="user_id" value="<?= $user["id"] ?>">
                <input type="hidden" name="project" value="<?= $project["project_id"] ?>">    
            </form>
        </div> 
      </div>      
    </main>
    
    <script>
    function getAssessment(currVal, maxVal) {
                
                
                    var append = " (" + currVal + " / " + maxVal + ")";
                    switch(Math.round(currVal / maxVal * 10)){
                
                    case 10:
                        return("<?= LABEL_VOTE_10 ?>" + append);
                        break;
                    case 9:
                        return("<?= LABEL_VOTE_09 ?>" + append);
                        break;
                    case 8:
                        return("<?= LABEL_VOTE_08 ?>" + append);
                        break;
                    case 7:
                        return("<?= LABEL_VOTE_07 ?>" + append);
                        break;
                    case 6:
                        return("<?= LABEL_VOTE_06 ?>" + append);
                        break;
                    case 5:
                        return("<?= LABEL_VOTE_05 ?>" + append);
                        break;
                    case 1: case 2: case 3: case 4:
                        return("<?= LABEL_VOTE_04 ?>" + append);
                        break;
                    case 0:
                        currVal--;
                        return("<?= LABEL_VOTE_00 ?>" + append);
                        break;
                }
    }
    
    $('input.range-assessment').on( "input", genAssessment );
    
    
    function genAssessment() {
            
            var currValue =  $( this  ).val();
            var maxValue = $( this  ).attr('max');
            
            var text = getAssessment(currValue, maxValue);
            
            $( this  ).next().text(text);
            
            }
    
    // text-assessment
    </script>
    
    <!--
    <script src="js/bootstrap-slider.js"></script>
    <script>
    var max;
    $('.slider').slider(function(){
                // get max value
                max = $(this).attr("data-slider-max");

                console.log(value + '-' + max + '-' + Math.round(value / max * 10));
        });        
                
     //max = $('.slider').attr('data-slider-max');
    $('.slider').slider({
                
                
                
                formater: function(value) {
                
                
                
                switch(Math.round(value / max * 10)){
                
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
            

    </script> -->
        
