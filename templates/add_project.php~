 
 <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />

<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
       

        
   <main class="col-md-10">         
       
       <form action="admin.php" method="post" enctype="multipart/form-data" id="form" role="form">
       
            <h4><?= $lang['PROJECT_INFO'] ?></h4>
            <div class="row">
                <div class = "col-xs-4">
                    <div class="form-group">
                        <label for="title"><?= $lang['PROJECT_TITLE'] ?></label>
                        <input type="text" class="form-control" id="title" name="project_name" value="<?= $curr_project["project_name"] ?>" required>
                    </div> 
                </div>
                <div class = "col-xs-3">
                    <label for="title"><?= $lang['ASSESSMENT_TYPE'] ?></label>
                    <select class="form-control" name="assessment_type">
                        <option<?php if($curr_project["assessment_type"] === $lang['ASSESSMENT_TYPE_1']) echo(" selected"); ?>><?=$lang['ASSESSMENT_TYPE_1']?></option>
                        <option<?php if($curr_project["assessment_type"] === $lang['ASSESSMENT_TYPE_2']) echo(" selected"); ?>><?=$lang['ASSESSMENT_TYPE_2']?></option>
                        <option<?php if($curr_project["assessment_type"] === $lang['ASSESSMENT_TYPE_3']) echo(" selected"); ?>><?=$lang['ASSESSMENT_TYPE_3']?></option>
                        <option<?php if($curr_project["assessment_type"] === $lang['ASSESSMENT_TYPE_4']) echo(" selected"); ?>><?=$lang['ASSESSMENT_TYPE_4']?></option>
                    </select>
                </div>
                <div class = "col-xs-3">                    
                        
                    <label for="title">Période</label>
                    <select class="form-control" name="periode">
                        <option<?php if($curr_project["periode"] === "1") echo(" selected"); ?>>1</option>
                        <option<?php if($curr_project["periode"] === "2") echo(" selected"); ?>>2</option>
                        <option<?php if($curr_project["periode"] === "3") echo(" selected"); ?>>3</option>
                        <option<?php if($curr_project["periode"] === "4") echo(" selected"); ?>>4</option>
                        <option<?php if($curr_project["periode"] === "XM Décembre") echo(" selected"); ?>>XM Décembre</option>
                        <option<?php if($curr_project["periode"] === "XM Juin") echo(" selected"); ?>>XM Juin</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class = "col-xs-4">
                    <div class="form-group">
                        <label for="title"><?= $lang['CLASS'] ?></label>
                        <select class="form-control" name="class" required>
                            <option<?php if($curr_project["class"] === "3AV") echo(" selected"); ?>>3AV</option>
                            <option<?php if($curr_project["class"] === "4AV") echo(" selected"); ?>>4AV</option>
                            <option<?php if($curr_project["class"] === "5AV") echo(" selected"); ?>>5AV</option>
                            <option<?php if($curr_project["class"] === "6AV") echo(" selected"); ?>>6AV</option>
                        </select>
                    </div> 
                </div>
                <div class = "col-xs-3">                    
                    <div class="form-group">
                        <label class="control-label"><?= $lang['DEADLINE'] ?></label>
                        <div class="date">
                            <div class="input-group input-append date" id="datePicker">
                                <input type="text" class="form-control" name="deadline" required value="<?= $curr_project["deadline"]?>" />
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class = "col-xs-3">
                </div>
            </div>
            <div class="row">
                <div class = "col-xs-10">
                    <div class="form-group">
                        <label for="title"><?= $lang['SKILLS_SEEN'] ?></label>
                        <select multiple class="form-control" name="skills[]" required multiple>

<?php foreach ($skills as $skill): ?>
                          <option<?php if(in_array($skill["skill_id"], $skills_selected)) echo(" selected"); ?>><?= $skill["skill_id"] . " " . $skill["skill"]?></option>
<?php endforeach ?>

                        </select>
                        <p class="help-block with-errors"><?= $lang['PRESS_CTRL_SELECT'] ?></p>
			        </div>
			    </div>
            </div>
            <div class="form-group">  
                <label class="control-label"><?= $lang['UPLOAD_INSTRUCTIONS'] ?></label>            
                <input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_UPLOAD_FILE_SIZE ?>" />
                <input id="inputfile" name="submitted_file" data-error="<?= $lang['NO_FILE'] ?>"  type="file">
                <p class="help-block with-errors"><?= $lang['ONLY_PDF_ALLOWED'] ?></p>
            </div>
                        
            <hr/> 
            
            <h4><?= $lang['ASSESSMENT_GRID'] ?></h4>      
    
            <table id="rows" class="table">
	            <thead>
	            <tr>
		            <th>Objectif</th>
		            <th>Critère</th>
		            <th>Indicateur (l'élève a:)</th>
		            <th>Coefficient</th>
		            <th></th>
	            </tr>
	            </thead>
	            <tbody>
	        <?php foreach($rows as $row): ?>    
	            <tr>
		            <td>
		                <select class="form-control input-sm" name="objective[]" required>
                                                        <?php foreach($objectives_list as $objective_list): ?>   
                                                        <option <?php if($objective_list == $row["objective"]) echo("selected") ?>><?= $objective_list ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </td>
		            <td><input class="typeahead_criteria form-control input-sm" placeholder="Critère" value="<?= $row["criterion"] ?>" name="criterion[]" autocomplete required /></td>
		            <td>
		                <textarea class="typeahead_cursors form-control input-sm" cols="50" rows="3" fixed" 
                            placeholder="<?= $lang['NEW_CRITERION'] ?>""<?= $lang['NEW_CRITERION'] ?>" name="cursor[]"><?= $row["cursor"] ?>
                        </textarea>
                    </td>
		            <td> <input class="form-control input-sm" placeholder="5" name="coefficient[]" autocomplete required size="2" /></td>
		            <td> <button type="button" class="btn btn-info btn-xs btn-danger pull-right" onClick="deleteRow('rows', this)"><span class="glyphicon glyphicon-remove"></span> Supprimer</button></td>
	            </tr>
	        <?php endforeach ?>
	            </tbody>
            </table>
                        
            
            
            <div class="form-group">
                <button type="button" class="btn btn-info btn-xs" onClick="addRow('rows')"><span class="glyphicon glyphicon-plus"></span><?= $lang['ADD_CRITERION'] ?></button>
                
            </div>
            <hr />
            <h4><?= $lang['SELF_ASSESSMENT'] ?></h4>
            
                    <div class="row"> 
                        <div class="col-xs-5">
                    <?php foreach($self_assessments as $self_assessment): ?>       
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="<?= $self_assessment["id"] ?>" name="data_eval_id[]" <?= $self_assessment["checked"] ?>>
                                    <?= $self_assessment["question"] ?>
                                </label>
                            </div>
                    <?php endforeach ?> 
                        </div>
                        <div class="col-xs-5">
                            <div class="auto-assessments">    
                                <div class="checkbox">
                                    <textarea class="form-control input-sm " rows="3" value="Nouveau critère (from user)" name="user_eval"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                    <button type="button" class="btn btn-xs add_auto-assessment"><span class="glyphicon glyphicon-plus"></span><?= $lang['ADD_QUESTION'] ?></button>
                            </div>
                        </div>
                    </div>
            <hr />
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><?= $lang['SAVE_PROJECT'] ?></button> 
                <input type="hidden" name="project_id" value="<?php if(isset($_GET["project"])) {echo($_GET["project"]);} else {echo("-1");}; ?>">
            </form>    
            <?php if(isset($_GET["project"])): ?>    
                
                <form action="admin.php" method="post" enctype="multipart/form-data">
                    <button type="submit" class="btn btn-danger  pull-right" name="remove_levels" value="delete"><span class="glyphicon glyphicon-remove"></span><?= $lang['DEL_PROJECT'] ?></button>
                    <input type="hidden" name="delete" value="<?= $_GET["project"] ?>">
                </form>
                
            <?php endif ?>
            
            </div>       

       


   </main>
   
   
   <script>
   
   
$(document).ready(function() {
    $('#datePicker')
        .datepicker({
            format: 'yyyy-mm-dd'
        })
        .on('changeDate', function(e) {
            // Revalidate the date field
            $('#eventForm').formValidation('revalidateField', 'date');
        });
});





</script>
