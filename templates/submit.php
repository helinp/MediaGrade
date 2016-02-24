        <?php include('projects_nav.php'); ?> 
       <main class="col-md-10">         
            <form action="index.php" role="form" enctype="multipart/form-data" method="post" data-toggle="validator">
                <div class="form-group"> 
                <h5><span class="glyphicon glyphicon-download-alt"></span> <?= ($number_of_files > 1 ? LABEL_SUBMIT_FILES : LABEL_SUBMIT_FILE) ?></h5>  
         <?php while($number_of_files--): ?>
         <?php if (!empty($extension)):?>
                               
                    <input type="hidden" name="project_id" value="<?= $project_id ?>" />
                    <label for="inputfile"><?= LABEL_SELECT_FILE ?></label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_UPLOAD_FILE_SIZE ?>" />
                    <input id="inputfile" name="submitted_file[]" data-error="<?= LABEL_NO_FILE ?>"  type="file" <?= (!empty($project_data[$number_of_files - 1]["file_name"]) ? '' : 'required') ?>
                    
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_UPLOAD_FILE_SIZE ?>" />
                    <p class="help-block with-errors">Max. <?= format_bytes(MAX_UPLOAD_FILE_SIZE) ?>.</p>
                    <?= (empty($project_data[$number_of_files - 1]["file_name"]) ?  '' : '<p>' . LABEL_SUBMITTED_FILE . ' <a href="' . $project_data[$number_of_files - 1]["file_path"] . $project_data[$number_of_files - 1]["file_name"] . '">' . $project_data[$number_of_files - 1]["file_path"] . $project_data[$number_of_files - 1]["file_name"] . '</a></p>') ?>
         <?php endif ?> 
                    <hr />
         <?php endwhile ?>             
                    
                </div>
                
           <?php if(isset($questions)): ?>    
             
                <h5><span class="glyphicon glyphicon-pencil"></span><?= LABEL_SELF_ASSESSMENT ?></h5>

                <div class="well">       
                   <?php $i = 0; ?>
                   <?php foreach($questions as $question): ?> 
                   <div class="form-group">            
                       <label><?= $question["question"] ?></label>
                       <textarea name="auto_assessment_<?= $question["id"] ?>" class="form-control" rows="3"  data-error="<?= LABEL_REQUIRED_ANSWER ?>"  required><?= $answers[$i]['answer'] ?></textarea>
                       <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                       <span class="help-block with-errors"></span>
                   </div>
                   <?php $i++; ?>
                   <?php endforeach ?>
                </div>
           <?php endif ?>
               
               <button type="submit" class="btn btn-primary"><?= LABEL_SUBMIT_WORK ?></button>

            </form>
        </main>
