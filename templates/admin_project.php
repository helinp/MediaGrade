 
 <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />

<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>

   <main class="col-md-10">         
       
       <form action="admin.php" method="post" enctype="multipart/form-data">
       
            <h4>Informations pratiques</h4>
            <div class="row">
                <div class = "col-xs-4">
                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input type="text" class="form-control" id="title" name="project_name" placeholder="Titre de la leçon" required>
                    </div> 
                </div>
                <div class = "col-xs-3">
                    <label for="title">Type d'évaluation</label>
                    <select class="form-control" name="assessment_type">
                        <option>Formative</option>
                        <option>Certificative</option>
                        <option>Diagnostique</option>
                        <option>Sommative</option>
                    </select>
                </div>
                <div class = "col-xs-3">                    
                        
                    <label for="title">Période</label>
                    <select class="form-control" name="periode">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>XM Décembre</option>
                        <option>XM Juin</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class = "col-xs-4">
                    <div class="form-group">
                        <label for="title">Classe</label>
                        <select class="form-control" name="class" required>
                            <option>3AV</option>
                            <option>4AV</option>
                            <option>5AV</option>
                            <option>6AV</option>
                        </select>
                    </div> 
                </div>
                <div class = "col-xs-3">                    
                    <div class="form-group">
                        <label class="control-label">Date de remise</label>
                        <div class="date">
                            <div class="input-group input-append date" id="datePicker">
                                <input type="text" class="form-control" name="deadline" required />
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
                        <label for="title">Compétences travaillées</label>
                        <select multiple class="form-control" name="skills" required>

<?php foreach $skills as $skill ?>
                          <option><?= $skill["skill"]?></option>
<? endforeach ?>

                        </select>
                        <p class="help-block with-errors">Appuyez sur CTRL pour une sélection multiple</p>
			        </div>
			    </div>
            </div>
            <div class="form-group">  
                <label class="control-label">Téléverser les consignes</label>            
                <input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_UPLOAD_FILE_SIZE ?>" />
                <input id="inputfile" name="submitted_file" data-error="<?= $lang['NO_FILE'] ?>"  type="file" required>
                <p class="help-block with-errors">Format PDF seulement</p>
            </div>
                        
            <hr/> 
            
            
            
            
            <h4>Grille d'évaluation</h4>      
            <div id="cloned">
                <div class="well clone-input" id="clone0"> <!-- Criteria row--> 
                    <div class="form-group ">                
                        <div class="row"> 
                            <div class="col-xs-4">
                            <label for="title">Objectif</label>
                                <div class="form-group">
                                    <select class="form-control input-sm" name="objective01" required>
                                        <option>FAIRE</option>
                                        <option>APPRECIER</option>
                                        <option>REGARDER ET ECOUTER</option>
                                        <option>CONNAIRE</option>
                                        <option>S'EXPRIMER</option>
                                    </select>
                                
                                    <label for="title">Critères</label>
				                    <input class="form-control input-sm" placeholder="Nouveau critère (autocomplète)" name="criteria01" required />
				                </div>
			                </div>
			

                            <div class = "col-xs-6">
                            
                                <div class="form-group" id="cursors" >                 
				                    <label for="title">Indicateurs (l'élève a:)</label>
				                    <textarea class="form-control input-sm" rows="2" placeholder="Nouveau critère (autocomplète)" name="cursor01" required></textarea>
                                    <fieldset class="cursor">
                                        <textarea class="form-control input-sm" rows="2" style="display:inline-block;" placeholder="Nouveau critère (autocomplète)" name="cursor01"></textarea>
                                        <span class="glyphicon glyphicon-trash clear-icon"></span>
                                    </fieldset>
                                </div>
                                <button type="button" class="btn btn-xs add_cursor"><span class="glyphicon glyphicon-plus"></span> Ajouter un indicateur</button>
                           </div>
                           
                    </div> 
                </div> <!-- close Criteria row-->

             </div>
         </div>
            <div class="form-group">
                <button type="button" class="btn btn-info btn-xs add_criteria"><span class="glyphicon glyphicon-plus"></span> Ajouter un critère</button>
            </div>
            <hr />
            <h4>Auto-évaluation</h4>
            
                    <div class="row"> 
                        <div class="col-xs-5">
                            <div class="checkbox">
                                <input type="checkbox" value="">
                                <textarea class="form-control input-sm" rows="3" placeholder="Nouveau critère (from database)" name="auto_eval1" required></textarea>
                            </div>
                        </div>
                        <div class="col-xs-5">
                            <div class="checkbox">
                                <input type="checkbox" value="">
                                <textarea class="form-control input-sm" rows="3" placeholder="Nouveau critère (from user)" name="auto_eval1" required></textarea>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-info btn-xs add_criteria"><span class="glyphicon glyphicon-plus"></span> Ajouter une auto-évaluation</button>
                            </div>
                         
                        </div>
                    </div>
            <hr />
            <button type="submit" class="btn btn-primary">Enregistrer la leçon</button>   
       </form>
        
   </main>
   
   
   <script>
$(document).ready(function() {
    $('#datePicker')
        .datepicker({
            format: 'mm/dd/yyyy'
        })
        .on('changeDate', function(e) {
            // Revalidate the date field
            $('#eventForm').formValidation('revalidateField', 'date');
        });

    $('#eventForm').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The name is required'
                    }
                }
            },
            date: {
                validators: {
                    notEmpty: {
                        message: 'The date is required'
                    },
                    date: {
                        format: 'MM/DD/YYYY',
                        message: 'The date is not a valid'
                    }
                }
            }
        }
    });
});


/*
 *     Deletes and adds forms
 *
 */
 
//set a counter
var i = $('#cursors :input').length + 1;
var j = $('#clone0 :input').length + 1;

//add cursor input
$('.add_cursor').click(function () {
    
    // TODO use clone funtion
    $('<div class="input-group"><textarea class="form-control input-sm" rows="2" name="cursor-' + j + '-' + i 
        + '" placeholder="Nouveau critère (autocomplète)"></textarea>' +
        '<span class="glyphicon glyphicon-trash clear-icon"></span></div>').fadeIn("slow").appendTo('#cursors');
    
    // increments cursor id counter
    i++;
    
    return false;
});


// fadeout selected item and remove
$("#cursors").on('click', '.clear-icon', function () {
    $(this).parent().fadeOut(300, function () {
        $(this).empty();
        
        return false;
    });
});


/*
 *  Clones entire form
 *
 */
var regex = /^(.*)(\d)+$/i;
var cloneIndex = $(".clone-input").length;

// clones
$('.add_criteria').click(function (){
    $(this).parents(".clone-" + j).clone()
        .appendTo("#cloned")
        .attr("id", ".clone-" + j + 1)
        //.find("*")
        
        // increase id counters on form's inputs
        // TODO use "'j' + 'i'" format 
        .each(function() {
            var id = this.id || "";
            var match = id.match(regex) || [];
            if (match.length == 3) {
                this.id = match[1] + (j);
            }
        })
    
    // increments form id counter
    j++;
}

// removes form
$('.remove_criteria').click(function (){
    $(this).parents(".clone-input").remove();
}

 // TODO function to return last id and POST it through hidden input 


</script>
