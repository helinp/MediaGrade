<div id="content" class="col-xs-12 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau">
		<div class="col-md-12">
		</div>
	</div>
	<?php $term_tmp = NULL; $curr = 0; $projects = array_reverse($projects); ?>
	<?php foreach ($projects as $project):?>
		<div class="row results-overview-row">
			<div class="col-md-3">
				<h4><?= $project->project_name; ?><br /><small><?= $project->teacher_name . ' / '  .  $project->term_name; ?></small></h4>

				<?php if(isset($project->submitted[0]) && isset($project->submitted[0]->file)): ?>
					<?php foreach($project->submitted as $key => $media): ?>

							<?php if ($media->extension === 'mp4' || $media->extension ===  'mov' || $media->extension === 'avi' || $media->extension === 'mp3' || $media->extension === 'wav'):?>
							<div class="embed-responsive embed-responsive-16by9 " >
								<video class="embed-responsive-item thumbnail-180" preload="metadata" controls>
									<source src="<?= $media->file?>" type="video/mp4">
									</video>
							</div>
							<?php else: ?>
							<div>
								<a href="<?= $media->file?>" <?= ($key > 0 ? 'style="display:none;"': '') ?> data-lightbox="project_<?= $project->project_id ?>">
									<img class="imageClip results-overview-thumb img-responsive" src="<?= $media->thumbnail?>" alt="Travail effectué mais non affiché." />
								</a>
							</div>
								<?php if($key === 1):?>
									<a href="<?= $media->file?>" data-lightbox="project_<?= $project->project_id ?>" style="margin-top:.5em"><span class="glyphicon glyphicon-plus-sign"> </span> <small>Voir les <?= count($project->submitted) ?> photographies</small></a>
								<?php endif ?>
							<?php endif ?>
						<?php endforeach ?>

					<?php else: ?>
						<p class="text-danger">Travail non remis.</p>
					<?php endif ?>
				</div>
				<div class="col-md-4">
					<h5>Mise en situation</h5>
					<p class="text-context"><?= ( isset($project->instructions_txt) && !empty(unserialize($project->instructions_txt)['context']) ? unserialize($project->instructions_txt)['context'] : '^_^')?></p>
					<?php if($project->self_assessments): ?>
					<h5>Auto-évaluation</h5>
					<a  data-toggle="collapse" href="#collapse_<?= $project->project_id ?>" aria-expanded="false" aria-controls="collapse_<?= $project->project_id ?>">
						<span class="glyphicon glyphicon-resize-vertical"> </span> Voir les réponses...
					</a>
					<div class="collapse" id="collapse_<?= $project->project_id ?>">
						<?php foreach($project->self_assessments as $self_assessment): ?>
							<h6><?= $self_assessment['question'] ?></h6>
							<p><?= (isset($self_assessment['answer']) ? '"' . $self_assessment['answer'] . '"' : 'Pas de réponse.') ?></p>
						<?php endforeach ?>
					</div>
				<?php endif ?>
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
							<a  data-toggle="modal" data-target="#projectModal" href="/student/project/results/<?= $project->project_id ?>"><h5><span class="glyphicon glyphicon-arrow-right"> </span> Résultats détaillés</a></h5>
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

		<script src="/assets/js/lightbox.js"></script><!-- lightbox -->
		<script>
		lightbox.option({
			'resizeDuration': 200,
			'wrapAround': true,
			'fitImagesInViewport':true
		})
		</script> <!-- lightbox -->
