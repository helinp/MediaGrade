<div id="content" class="col-xs-12 col-md-10 ">
	<div class="row chapeau">
		<div class="col-md-12">
			<h1> <?= _('Mes projets') ?>
			</h1>
		</div>
	</div>
	<?php $term_tmp = NULL; $curr = 0; $projects = array_reverse($projects); ?>
	<?php foreach ($projects as $project):?>
		<?php
		if ($term_tmp !== $project->term)
		{
			$term_tmp = $project->term;
			echo '<h3>' ._('Période') . ' ' . $term_tmp . '</h3>
			<div class="row">';
		}
		?>
		<div class="col-md-4 col-lg-4">
			<div class="panel <?= (countdown($project->raw_deadline) ? 'panel-primary' : 'panel-default')?>">
				<div class="panel-heading">
					<h4 class="panel-title"><?= $project->project_name; ?>

						<?php if(isset($project->result->total_user)): ?>
							<span class="badge pull-right" style="background-color: #fff;">
								<span class="text-<?= ($project->result->total_user > $project->result->total_max / 2 ? 'success' : 'danger') ?>"><strong><?= $project->result->total_user . ' / ' . $project->result->total_max ?></strong>
								</span>
							<?php endif ?></h4>
						</div>
						<div class="panel-body">
							<p><?= unserialize($project->instructions_txt)['context'] ?> </p>
							<p style="margin-bottom:4px;">
								<span class="label label-info"><span class="glyphicon glyphicon-time"></span> <?=$project->deadline?></span>
								<br /><span class="label label-<?= format_assessment_type($project->assessment_type)['label'] ?>"><span class="glyphicon glyphicon-pencil"> </span> <?= _('Évaluation') . ' '. format_assessment_type($project->assessment_type)['type'] ?></span>
								<?php if (countdown($project->raw_deadline)): ?>
									<span class="label label-info"><?= countdown($project->raw_deadline) . ' ' . (countdown($project->raw_deadline) > 1 ? _('jours restants') : _('jour restant')) ?></span></p>
								<?php endif ?>

								<p>
									<?php if($project->submitted): ?>
										<span class="label label-success">Remis</span>
									<?php else: ?>
										<span class="label label-danger">Non remis</span>
									<?php endif ?>
									<?php if(countdown($project->raw_deadline)): ?>
										<span class="label label-warning">En cours</span>
									<?php else: ?>
										<span class="label label-info">Clôturé</span>
									<?php endif?>
									<?php if($project->graded): ?>
										<span class="label label-primary">Évalué</span>
									<?php endif ?>
								</p>

								<div style="min-height:1.5em;">
									<small><?= ($project->achievements ? _('Badges à obtenir:') : '')?></small>
									<?php foreach ($project->achievements as $achievement): ?>
										<img src="<?= $achievement->icon?>" style="height:1em;" data-toggle="tooltip" data-placement="left" title="<?= $achievement->name ?> <?= str_repeat('&#9733;', $achievement->star)?>" />
									<?php endforeach ?>
								</div>
							</div>
							<div class="btn-group btn-group-justified" role="group" aria-label="...">
								<div class="btn-group" role="group">
									<a data-toggle="modal" data-target="#projectModal" href="/student/project/instructions/<?= $project->project_id ?>" type="button" class="btn btn-sm <?= (countdown($project->raw_deadline) ? 'btn-primary' : 'btn-default')?>"><span class="glyphicon glyphicon-file"> </span> Consignes</a>
								</div>
								<div class="btn-group" role="group">
									<a href="<?= (countdown($project->raw_deadline) ? '/student/project/submit/' . $project->project_id . '" data-toggle="modal" data-target="#projectModal" ' : '#"' ) ?>
										type="button" class="btn btn-sm <?= (countdown($project->raw_deadline) ? 'btn-primary' : 'btn-default')?>" <?= (countdown($project->raw_deadline) ? '' : 'disabled') ?>>
										<span class="glyphicon glyphicon-download-alt"> </span> Remise</a>
									</div>
									<div class="btn-group" role="group">
										<a data-toggle="modal" data-target="#projectModal" href="/student/project/results/<?= $project->project_id ?>" type="button" class="btn btn-sm <?= (countdown($project->raw_deadline) ? 'btn-primary' : 'btn-default')?>"><span class="glyphicon glyphicon-list-alt"> </span> <?= _('Évaluation')?></a>
									</div>

								</div>
							</div>
						</div>
						<?php
						if ($projects[$curr]->term !== @$projects[$curr + 1]->term)
						{
							echo '</div>
							<!-- /. row term-->';
						}
						$curr++;
						?>
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
