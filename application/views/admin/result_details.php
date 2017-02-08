    <div class="row chapeau chapeau-modal">
        <div class="col-xs-12  col-md-12">
            <h2> <?= _('Résultats détaillés') ?> <small>/ <?= $project_name?></small></h2>
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
    <table class="table table-hover table-striped" style="margin-top:5em">
        <thead>
            <tr>
                <th><?= _('Compétences') ?></th>
                <th><?= _('Critères') ?></th>
                <th><?= _('Indicateurs (l\'élève a:)') ?></th>


                <?php foreach(array_values($students)[0] as $student): ?>
                <th class="rotate"><div><span><small><?= $student->last_name . ' ' . substr($student->name, 0, 1) . '.'?></small></span></div></th>
                <?php endforeach ?>
                <th class="rotate"><div><span><small><?= _('Maximum') ?></small></span></div></th>
                <th class="rotate"><div><span><small><?php if(count($results[0]->results) > 1) echo _('Moyenne') ?></small></span></div></th>
            </tr>
        </thead>


        <tbody>
            <?php foreach($results as $row): ?>
            <tr>
                <td><?= $row->skills_group ?></td>
                <td><?= $row->criterion ?></td>
                <td><?= $row->cursor ?></td>

                <?php foreach($row->results as $result): ?>
                <td<?php if ($result < ($row->max_vote / 2) && is_numeric($result)) echo(' class="text-danger dotted_underline" ') ?>><?= $result ?></td>
                <?php endforeach ?>
                <td><strong><?= $row->max_vote ?></strong></td>
                <td>
                  <?php if(count($row->results) > 1):?>
                    <strong><?= (count(array_filter($row->results,'is_numeric')) ? custom_round(array_sum($row->results) / count(array_filter($row->results,'is_numeric'))) : _('NA'))?></strong></td>
                  <?php endif ?>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
