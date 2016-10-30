<div class="row chapeau chapeau-modal">
		<div class="col-xs-12  col-md-12">
				<h2 class=" text-left"> <?= _('Résultats')?><small> / <?=$project->project_name?></small><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></h2>
		</div>
</div>
    <div class="row">
        <?php if(!empty($submitted)): ?>
            <?php   $n = count($submitted);
                if($n == 1) {$max_files = 1 ; $cols = 6;}
                elseif($n < 7) { $max_files = 6 ; $cols = 2;}
                else { $max_files = 12 ; $cols = 2;}
                ?>
            <?php foreach ($submitted as $project_url): ?>
                <div class="col-md-<?= $cols ?>">
                    <?php if ($project_url->extension == "mp4" || $project_url->extension == "mov" || $project_url->extension == "avi"):?>
                          <div class="thumbnail">
                              <div class="embed-responsive embed-responsive-16by9">
                                  <video class="embed-responsive-item" preload="metadata" controls>
                                      <source src="<?= $project_url->file?>" type="video/mp4">
                                      <p><?= LABEL_NO_HTML5_VIDEO ?> <a href="<?= $project_url->file ?>"><?= LABEL_HERE ?></a></p>
                                  </video>
                              </div>
                          </div>

                <?php elseif($project_url->extension == "jpeg" ||$project_url->extension == "jpg" || $project_url->extension == "png" || $project_url->extension == "gif"): ?>
                      <div class="thumbnail">
                          <a  href="<?= $project_url->file ?>">
                          <img class="img-responsive thumbnail-180" src="<?= $project_url->thumbnail ?>" alt="<?= $project_url->file ?>" />
                          </a>
                      </div>
                <?php endif ?>
                </div>
            <?php endforeach ?>
        <?php endif ?>
    </div>

    <h3><?= _('Évaluation') ?></h3>
    <?php if ($results{0}->user_vote != 0):?>
    <table class="table table-hover ">
        <thead>
            <tr>
                <th><?= _('Compétence') ?></th>
                <th><<?= _('Critère') ?></th>
                <th><?= _('Tu as: ') ?></th>
                <th><?= _('Résultat') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($results as $result)
                {
                    $percentage = 0;
                    if ($result->user_vote != 0) $percentage = ($result->user_vote / $result->max_vote) * 100;

                    $class = "";

                    if ($percentage > 79) $class = "success";
                    if ($percentage < 49) $class = "warning";
                    if ($percentage < 30) $class = "danger";

                    echo
                    (
                    '<tr class=' .'"' . $class . '">'
                            . '<td>' . $result->skills_group . "</td>\n"
                            . '<td>' . $result->criterion . "</td>\n"
                            . '<td>' . $result->cursor . "</td>\n"
                            . '<td>' . $result->user_vote . " / " . $result->max_vote . "</td>\n"
                    . "</tr>"
                    );
                }?>
        </tbody>
    </table>
	    <?php if (!empty($comments->comment)):?>
	    <?= '<h3>' . _('Commentaire du professeur') . '</h3><pre class="comment">' . $comments->comment . '</pre>'?>
	    <?php endif ?>
    <?php else: ?>
    <?= _('Ton travail n\'a pas encore été évalué.') ?>
    <?php endif ?>
