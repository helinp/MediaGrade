   <main class="col-md-10">         
       
       
        
        <form action="index.php" role="form" enctype="multipart/form-data" method="post" data-toggle="validator">
            <div class="form-group">  
     <?php if (!empty($extension)):?>
                <h5><span class="glyphicon glyphicon-download-alt"></span> <?= $lang['SUBMIT_FILE'] ?></h5>            
                <input type="hidden" name="project_id" value="<?= $project_id ?>" />
                <label for="inputfile"><?= $lang['SELECT_FILE'] ?></label>
                <input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_UPLOAD_FILE_SIZE ?>" />
                <input id="inputfile" name="submitted_file" data-error="<?= $lang['NO_FILE'] ?>"  type="file" required>
                
                <input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_UPLOAD_FILE_SIZE ?>" />
                <p class="help-block with-errors">Max. 200 Mo.</p>
                <?= (empty($project_data["file_path"]) ? "" : '<input type="hidden" name="submited" value="1" /><label for="inputfile">Fichier remis:</label><pre>'. $project_data["file_path"] .'</pre>') ?>
     <?php endif ?>              
            </div>
            
       <?php if(isset($questions)): ?>    
         
            <h5><span class="glyphicon glyphicon-pencil"></span><?= $lang['SELF_ASSESSMENT'] ?></h5>

            <div class="well">       
               <?php foreach($questions as $question): ?> 
               <div class="form-group">            
                   <label><?= $question["question"] ?></label>
                   <textarea name="auto_assessment_<?= $question["id"] ?>" class="form-control" rows="3"  data-error="<?= $lang['REQUIRED_ANSWER'] ?>"  required></textarea>
                   <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                   <span class="help-block with-errors"></span>
               </div>
               <?php endforeach ?>
            </div>
       <?php endif ?>
           
           
           <button type="submit" class="btn btn-primary"><?= $lang['SUBMIT_WORK'] ?></button>
                     
        </form>
        
    </main>
