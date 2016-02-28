       <?php include('projects_nav.php'); ?>
        
       <main class="col-md-10">
           <div class="row"> 
               
            <?php   $n = count($projects_url);
                    if($n == 1) {$max_files = 1 ; $cols = 8;} 
                   elseif($n < 7) { $max_files = 6 ; $cols = 4;} 
                   else { $max_files = 12 ; $cols = 2;} 
            ?>
				
        <?php foreach ($projects_url as $project_url): ?>
               <div class="col-md-<?= $cols ?>"> 
                    <?php if ($project_url['extension'] == "mp4" || $project_url['extension'] == "mov" || $project_url['extension'] == "avi"):?>    
                    <div class="thumbnail">
                       <div class="embed-responsive embed-responsive-16by9">
                            <video class="embed-responsive-item" preload="metadata" controls>
                                <source src="<?= $project_url['file']?>" type="video/mp4">
                                <p><?= LABEL_NO_HTML5_VIDEO ?> <a href="<?= $project_url['file'] ?>"><?= LABEL_HERE ?></a></p>
                            </video>  
                          
                        </div>
                    </div>
                    <?php elseif($project_url['extension'] == "jpeg" || $project_url['extension'] == "jpg" || $project_url['extension'] == "png" || $project_url['extension'] == "gif"): ?>
                    <div class="thumbnail">    
                        <a  href="<?= $project_url['file'] ?>">
                            <img class="img-responsive thumbnail-180" src="<?= $project_url['thumbnail'] ?>" alt="<?= $project_url['file'] ?>" />
                        </a>
                    </div>
                    <?php endif ?>
               </div>
         <?php endforeach ?>        
				
			</div>
			<div class = "row">
				<div class="col-md-12">
                 
           <?php if (is_array($content)):?>
               <?= (empty($comment) ? '' : '<hr /><h4>' . LABEL_COMMENT . "</h4><p>$comment</p><hr />"); ?>
               
               <h4><?= LABEL_RESULTS ?></h4>
               <table class="table table-hover ">
                    <thead>
                      <tr>
                        <th><?= LABEL_SKILLS ?></th>
                        <th><?= LABEL_CRITERION ?></th>
                        <th><?= LABEL_CURSOR ?></th>
                        <th><?= LABEL_RESULTS ?></th>
                      </tr>
                    </thead>
                    
                    <tbody>
                     
                  <?php foreach($content as $result)
                        {
                            //dump($result);
                            $percentage = 0;
                            if ($result["user_grade"] !== 0) $percentage = ($result["user_grade"] / $result["max_vote"]) * 100;
                            
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
                                    . "<td>" . $result["user_grade"] . " / " . $result["max_vote"] . "</td>\n"
                            . "</tr>"
                            );
                        }       
                     ?>
                
                    </tbody>
              </table>
              
              </div>
          </div>
            <?php else: ?>
                <div class = "row">
                    <div class="col-md-12">   
                        <?= $content ?>
                    </div>
               </div>
            <?php endif ?>
            
            
       </main>
