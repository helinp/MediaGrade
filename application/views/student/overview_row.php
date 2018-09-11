<div id="content" class="col-xs-12 col-md-10 ">
	<div class="row chapeau">
		<div class="col-md-12">
		</div>
	</div>


	<?php ; $term_tmp = NULL; $curr = 0; $projects = array_reverse($projects); ?>

	<?php foreach ($projects as $project):?>
		<?php
		if ($term_tmp !== $project->term_name)
		{
			$term_tmp = $project->term_name;
			echo '<h3>' ._('Période') . ' ' . $term_tmp . '</h3>
			';
		}
		?>
		<div class="row results-overview-row" style="<?= (countdown($project->raw_deadline) ? 'border-left: rgb(51, 122, 183) solid 5px;' : 'border-left: lightgray solid 5px;')?>">
			<div class="col-md-3">
				<h4><?= $project->teacher_name . ' / '  . $project->project_name; ?>
					<br /><small><?= $project->deadline; ?></small></h4>

					<!-- Labels -->
					<p style="margin-bottom:4px;">
						<span class="label label-<?= format_assessment_type($project->assessment_type)['label'] ?>"><span class="glyphicon glyphicon-pencil"> </span> <?= _('Évaluation') . ' '. format_assessment_type($project->assessment_type)['type'] ?></span>
					</p>
					<p style="margin-bottom:4px;">

						<?php if (countdown($project->raw_deadline)): ?>
							<span class="label label-info"><?= (countdown($project->raw_deadline) - 1) . ' ' . (countdown($project->raw_deadline) > 1 ? _('jours restants') : _('jour restant')) ?></span>
						<?php endif ?>


					</p>
					<div style="min-height:1.5em;">
						<?php foreach ($project->achievements as $achievement): ?>
							<img src="<?= $achievement->icon?>" style="height:1.4em;vertical-align: sub;" data-toggle="tooltip" data-placement="left" title="<?=  _('Contribue à l\'obtention du badge ') . $achievement->name ?> <?= str_repeat('&#9733;', $achievement->star)?>" />
						<?php endforeach ?>
					</div>
					<!-- ./ Labels -->
				</div>

				<div class="col-md-6">
					<h5>Mise en situation</h5>
					<p><?= unserialize($project->instructions_txt)['context'] ?></p>
					--
					<h5>
						<a data-toggle="modal" data-target="#projectModal" href="/student/project/instructions/<?= $project->project_id ?>"><span class="glyphicon glyphicon-arrow-right"> </span> Consignes et grille d'évaluation détaillées</a>
					</h5>
				</div>

				<div class="col-md-3">
					<?php if($project->submitted && $project->submitted_media[0]->extension == 'jpg' ): ?>
						<h5>Remis</h5>
						<div>
							<?php foreach($project->submitted_media as $key => $media):?>
								<a href="<?= $media->file?>" data-lightbox="project_<?= $project->project_id ?>">
									<img class="image-clip-square" src="<?= $media->thumbnail ?>" />
								</a>
							<?php endforeach ?>
							<?php $n_submitted =  count($project->submitted_media); ?>
							<?php if($project->number_of_files > $n_submitted): ?>
								<?php for($i = $project->number_of_files - $n_submitted ; $i <= $n_submitted ; $i++): ?>
									<!--<span class="image-clip-square"> </span>-->
									<img class="image-clip-square" src="/assets/img/not-found.jpg" alt="Projet non remis"/>
								<?php endfor ?>
							<?php endif ?>
						</div>
					<?php endif ?>
					<h5>Statut</h5>
					<p>
						<?php if($project->submitted): ?>
							<span class="label label-success">Remis</span>
						<?php else: ?>
							<span class="label label-danger">Non remis</span>
						<?php endif ?>
						<?php if(countdown($project->raw_deadline) > 0): ?>

						<?php else: ?>
							<span class="label label-success">Clôturé</span>
						<?php endif?>
						<?php if($project->graded): ?>
							<span class="label label-success">Évalué</span>
						<?php endif ?>
					</p>

					<?php if(countdown($project->raw_deadline) > 0): ?>
						<?php if(!$project->submitted && !$project->graded ): ?>
							<p><a  data-toggle="modal" data-target="#projectModal" href="/student/project/submit/<?= $project->project_id ?>"  type="button" class="btn btn-primary btn-xs">
									<span class="glyphicon glyphicon-download-alt"> </span> Remettre</a>
							</p>
						<?php elseif($project->submitted && ! $project->graded ): ?>
							<p><a  data-toggle="modal" data-target="#projectModal" href="/student/project/submit/<?= $project->project_id ?>"  type="button" class="btn btn-default btn-sm">
								<span class="glyphicon glyphicon-download-alt"> </span> Remettre à nouveau</a>
							</p>
						<?php endif ?>
					<?php else: ?>

						<p><button  type="button" class="btn btn-default btn-xs" disabled="1">Clôturé</button></p>

					<?php endif ?>
				</div>


			</div>
			<!-- /. row term-->

		<?php endforeach ?>
	</div>





	<script src="/assets/js/lightbox.js"></script><!-- lightbox -->
	<script>
	lightbox.option({
		'resizeDuration': 200,
		'wrapAround': true,
		'fitImagesInViewport':true
	})
</script> <!-- lightbox -->

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
