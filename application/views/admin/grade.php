<main class="col-md-10" id="content">


               <h2><?= $user->class?><small> // <?= $user->name . " " . $user->last_name?></small></h2>

                   <hr style="margin-top:0;" />

       <div class="row">
           <?php $n_files = count($submitted); ?>

           <?php  if($n_files == 1) {$max_files = 1 ; $cols = 10;}
                  elseif($n_files < 7 && $n_files) { $max_files = 6 ; $cols = 4;}
                  else { $max_files = 12 ; $cols = 2;}
           ?>


               <?php foreach(range(0, $max_files - 1) as $count): ?>
               <div class="col-md-<?= $cols ?>"  style="padding-bottom: 1em;">

                   <?php if (isset($submitted[$count]->file_path)): ?>
                       <?php if ($submitted[$count]->extension == "mp4" || $submitted[$count]->extension == "mov" || $submitted[$count]->extension == "avi"): ?>
                           <video width="50%" controls preload = "auto">
                               <source src="/assets/<?= $submitted[$count]->file_path . $submitted[$count]->file_name ?>" type="video/mp4">
                               <p><?= LABEL_NO_HTML5_VIDEO ?> <a href="<?= $submitted[$count]->file_path?>"><?= LABEL_HERE ?></a></p>
                           </video>
                       <?php elseif($submitted[$count]->extension == "jpg" || $submitted[$count]->extension == "jpeg" || $submitted[$count]->extension == "png" || $submitted[$count]->extension == "gif"): ?>

                           <a href="/assets/<?= $submitted[$count]->file_path . $submitted[$count]->file_name ?>">
                               <img alt="<?= $user->name . " " . $user->last_name . " / " . $project->project_name?>"
                               style="max-height:500px;"
                               src="/assets/<?= $submitted[$count]->file_path . "thumb_" . $submitted[$count]->file_name?>" />
                               </a>

                       <?php endif ?>
                    <?php endif ?>
               </div>
               <?php endforeach ?>


      </div>
      <div class="row">
           <div class="col-md-12">
               <?php if (empty($submitted)): ?>
               <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-warning-sign"></span> <?= LABEL_NOT_SUBMITTED ?></div>
             <?php else: ?>
                <div class="alert alert-info"><?= LABEL_SUBMITTED_ON . $submitted{$n_files - 1}->time ?></div>
             <?php endif ?>
           </div>


       </div>


        <div class="row">

           <!-- CRITERIA -->
           <div class="col-md-12">

               <?php if(!empty($self_assessments)): ?>
               <h3><?=  LABEL_SELF_ASSESSMENT ?></h3>
               <?php foreach($self_assessments as $self_assessment):?>
                   <b> <?= $self_assessment['question'] ?> </b><p> <?= (!empty($self_assessment['answer']) ? '&quot;' . $self_assessment['answer'] . '&quot;': _("L'élève n'a pas répondu à la question.")) ?></p>
               <?php endforeach ?>

               <hr />
               <?php endif ?>
               <h3><?=  LABEL_ASSESSMENT ?></h3>
               <form action="" method="post" id="form">
                <table id="rows" class="table table-striped">
                       <col width="10%">
                       <col width="15%">
                       <col width="40%">
                       <col width="25%">
                       <thead>
                           <tr>
                               <th><?= _('Groupe de compétences') ?></th>
                               <th><?= LABEL_CRITERIA ?></th>
                               <th><?= LABEL_CURSORS ?></th>
                               <th><?= _('Évaluation') ?></th>
                               <th><?= _('Note max. pondérée') ?></th>
                           </tr>
                       </thead>
                       <tbody>
                       <?php $i = 0;?>
                        <?php foreach ($assessment_table as $row): ?>

                           <tr>
                               <td><?= $row->skills_group ?></td>
                               <td><?= $row->criterion ?></td>
                               <td><?= $row->cursor ?></td>
                               <td>
                                   <input name="user_vote[]" class="range-assessment" type="range" value="<?= ($row->user_vote ? ($row->user_vote / $row->max_vote * 10) : '5') ?>" max="10" min="0" step="1">
                                   <span class="small" data-onload="genAssessment()"></span>
                                   <input type="hidden" name="assessments_id[]" value="<?= $row->id?>">
                               </td>
                               <td><!--<span class="balancedVote"><?= ($row->user_vote ? $row->user_vote : '5') ?></span> / --><?= $row->max_vote ?></td>
                           </tr>
                           <?php $i++ ?>
                        <?php endforeach ?>
                       </tbody>
                   </table>

               <!-- END CRITERIA -->
               <hr />
               <h3><?=  LABEL_COMMENT ?></h3>
               <textarea rows="5" cols="10" name="comment"><?= $comment ?></textarea>

               <hr />
               <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span><?= LABEL_SAVE_RATING ?></button>
               <input type="hidden" name="submitted_project_date" value="<?= @$submitted[0]->time ?>">
               <input type="hidden" name="user_id" value="<?= $user->id ?>">
               <input type="hidden" name="project_id" value="<?= $project->id ?>">
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


   $('input.range-assessment').on( "input", function(){
       $("input.range-assessment").change(function(){
           var newval = $(this).val();
           $(".balancedVote").text(newval);
       });
   } );


   function genAssessment() {

           var currValue =  $( this  ).val();
           var maxValue = $( this  ).attr('max');

           var text = getAssessment(currValue, maxValue);

           $( this  ).next().text(text);

           }

   $('input.range-assessment').on( "input", genAssessment );
   </script>
