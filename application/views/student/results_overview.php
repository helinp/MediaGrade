<div id="content" class="col-xs-12 col-md-10 ">
	<div class="row chapeau">
		<div class="col-md-12">
		</div>
	</div>
	<?php $term_tmp = NULL; $curr = 0; $projects = array_reverse($projects); ?>
	<?php foreach ($projects as $project):?>
		<div class="row results-overview-row">
			<div class="col-md-3">
				<h4><?= $project->project_name; ?><br /><small><?= $project->term_name; ?> / <?= $project->deadline; ?></small></h4>
				<?php if(@$project->submitted[0]->file): ?>
					<div><img class="results-overview-thumb img-responsive" src="<?= @$project->submitted[0]->thumbnail?>" alt="Travail effectué mais non affiché." /></div>
				<?php else: ?>
					<p class="text-danger">Travail non remis.</p>
				<?php endif ?>
			</div>
			<div class="col-md-4">
				<h5>Mise en situation</h5>
				<p class="text-context"><?= ( isset($project->instructions_txt) && !empty(unserialize($project->instructions_txt)['context']) ? unserialize($project->instructions_txt)['context'] : '^_^')?></p>
				<h5>Commentaires sur ton projet</h5>
				<p><?= ( isset($project->comments) && !empty($project->comments) ? $project->comments : '- -')?></p>
			</div>
			<div class="col-md-5">
				<h5>Résultats simplifiés</h5>
				<?php if(isset($project->results[0]->percentage)):?>
					<table class="table table-condensed">

						<?php foreach ($project->results as $result): ?>
							<tr>
								<td>
									<td><span class="lsu lsu-<?= convertPercentageToLSUCode($result->percentage) ?>" data-toggle="tooltip" data-placement="top" title="<?= returnLSUTextFromLSUCode(convertPercentageToLSUCode($result->percentage)) ?>">&nbsp;&nbsp;&nbsp;&nbsp;</span>
									</td>
									<td>
										<p><?= _('J\'ai') . ' ' . $result->cursor ?></p>
									</td>
								</tr>
							<?php endforeach ?>
						</table>
					<?php else: ?>
						<p>Ce projet n'a pas encore évalulé, un peu de patience :-)</p>
					<?php endif ?>
				</div>
			</div>
		<?php endforeach ?>


	</div>
	<!-- Modal -->
	<div class="modal sudo"  id="projectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" style="padding:1em;"></div>
		</div>
	</div>
	<!-- Updates modal-->
	<script>
	$(document).on('hidden.bs.modal', function (e) {
		$(e.target).removeData('bs.modal');
	});
	</script>
