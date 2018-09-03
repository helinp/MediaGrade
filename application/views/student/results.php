<div class="row chapeau chapeau-modal">
	<div class="col-xs-12  col-md-12">
		<h2 class=" text-left"> <?= _('Résultats détaillés')?><small> / <?=$project->project_name?></small><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></h2>
	</div>
</div>


	<?php if ( ! is_null($results{0}->user_vote)):?>
		<table class="table table-hover ">
			<thead>
				<tr>
					<th><?= _('Compétence') ?></th>
					<th><?= _('Critère') ?></th>
					<th><?= _('Tu as: ') ?></th>
					<th><?= _('Résultats') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($results as $result): ?>
					<tr>
						<td><span data-toggle="tooltip" title="<?= $result->skill_description ?>"><?= $result->skill_id ?></span></td>
						<td><?= $result->criterion ?></td>
						<td><?= $result->cursor ?></td>
						<td style="width:22%; border-left: solid 5px <?= returnLSUColorFromPercentage($result->percentage) ?>"><small><?= returnLSUTextFromPercentage($result->percentage) ?> (<?= $result->user_vote ?> / <?= $result->max_vote ?>)</small></td>
					</tr>
				<?php endforeach ?>
				</tbody>
			</table>
		<?php else: ?>
			<?= _('Ton travail n\'a pas encore été évalué.') ?>
		<?php endif ?>
