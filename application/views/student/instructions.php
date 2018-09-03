			<div class="row chapeau chapeau-modal">
				<div class="col-xs-12  col-md-12">
					<h2 class=" text-left"> <?= _('Consignes')?><small> / <?=$instructions->project_name?></small><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></h2>
				</div>
			</div>

			<?= ($instructions->txt ? $instructions->txt['instructions'] : '') ?>
			<?= makeHtmlObjectForPdf($instructions->pdf)?>
			<h3 style="margin-top:2em"><?= _('Grille d\'évaluation')?></h3>
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th><?= _('Compétences') ?></th>
						<th><?= _('Critères') ?></th>
						<th><?= _('Tu as: ') ?></th>
						<th class="rotate"><div><span><small><?= _('Maximum') ?></small></span></div></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($assessment_table as $row): ?>
						<tr>
							<td><span data-toggle="tooltip" title="<?= $row->skill_description ?>"><?= $row->skill_id ?></span></td>
							<td><?= $row->criterion ?></td>
							<td><?= $row->cursor ?></td>
							<td><strong><?= $row->max_vote ?></strong></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
