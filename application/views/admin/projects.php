<div id="content" class="col-xs-10 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau">
		<div class="col-xs-12  col-md-12">
			<form id="filter" action="" method="get" class="form-inline" style="margin-top:1.5em">
				<div class="pull-right">
					<label><?= _('Classe') ?>: </label>
					<div class="input-group">
						<select class="form-control input-sm" name="class" onchange="this.form.submit()">
							<option value=""><?php echo _('Toutes') ?></option>
							<?php foreach($classes as $class): ?>
								<?= '<option value="' . $class->id . '"' . ($this->input->get('class') === $class->id ? ' selected' : '') . '>' . $class->description . '</option>' . "\n" ?>
							<?php endforeach?>
						</select>
					</div>
					<label><?= _('Période') ?>: </label>
					<div class="input-group">
						<select class="form-control input-sm" name="term" onchange="this.form.submit()">
							<option value=""><?php echo _('Toutes') ?></option>
							<?php foreach($terms as $term): ?>
								<?= '<option value="' . $term->id . '"' . (@$_GET['term'] === $term->id ? ' selected' : '') . '>' . $term->name . '</option>' . "\n" ?>
							<?php endforeach?>
						</select>
					</div>
					<label><?= _('Année scolaire') ?>: </label>
					<div class="input-group">
						<select class="form-control input-sm" name="school_year" onchange="this.form.submit()">
							<?php foreach($school_years as $school_year): ?>
								<?= '<option value="' . $school_year->school_year . '"' . (@$_GET['school_year'] === $school_year->school_year ? ' selected' : '') . '>' . $school_year->school_year . '</option>' . "\n" ?>
							<?php endforeach?>
						</select>
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php $term_tmp = NULL; $curr = 0; ?>

	<?php foreach ($projects as $project):?>
		<?php
		if ($term_tmp !== $project->term_name)
		{
			$term_tmp = $project->term_name;
			echo '<h3>' ._('Période') . ' ' . $term_tmp . '</h3>
			<div class="row">';
			$classe_tmp = $project->class;
		}
		if ($classe_tmp !== $project->class)
		{
			echo '<div class="row">';
			$classe_tmp = $project->class;
		}
		?>

		<div class="col-xs-12 col-md-3 col-lg-3">
			<div class="panel panel-default <?php

			if($project->is_activated && countdown($project->deadline)) echo 'current';
			elseif ($project->is_activated && !countdown($project->deadline)) echo 'reached-deadline';
			else echo 'desactivated';

			?>">
			<div class="panel-body projects-panel-body" style="min-height: 9em">
				<h4 style="margin-top: 0;"><span data-toggle="tooltip" data-placement="left" title="<?= $project->project_name ?>"><b><?= $project->course_name . ' / ' . character_limiter($project->project_name, 15) ?></b></span></h4>
				<p class="pull-right">
					<span class="label label-<?= format_assessment_type($project->assessment_type)['label'] ?>"><?= format_assessment_type($project->assessment_type)['type'] ?></span>
					<?= ($project->external ? ' <span class="label label-info">Externe</span> ' : '') ?>
				</p>
				<p style="font-size:small">
					<?php if( ! $project->external): ?>
						<span data-toggle="tooltip" data-placement="left" title="<?=  _('Date de début') ?>"><?= ($project->start_date ? date_format(date_create($project->start_date),"d M.") : '--'); ?></span>
						<span class="glyphicon glyphicon-chevron-right" > </span>
					<?php endif ?>
					<span data-toggle="tooltip" data-placement="left" title="<?=  _('Deadline') ?>"><?= date_format(date_create($project->raw_deadline),"d M. Y"); ?></span>
				</p>

				<?php if($achievements_by_project[$project->project_id]): ?>
					<div style="min-height:1.5em;">
						<?php $badge_ctrl = ''?>
						<?php foreach ($achievements_by_project[$project->project_id] as $achievement): ?>
							<?php if($badge_ctrl !== $achievement->name): ?>
								<img src="<?= $achievement->icon?>" style="height:1.5em;margin-top: -4px;" data-toggle="tooltip" data-placement="left" title="<?= $achievement->name ?> <?= str_repeat('&#9733;', $achievement->star)?>" />
								<?php $badge_ctrl = $achievement->name; ?>
							<?php endif ?>
						<?php endforeach ?>
					</div>
				<?php endif ?>
				<span class="classe"><?php echo $project->class_name ?></span>
			</div>
			<div class="progress progress-modal"  data-toggle="tooltip" data-placement="left" title="<?= _('Non remis:') . ' ' . ($n_students[$project->project_id] - $n_graded[$project->project_id]) ?>">
				<div class="progress-bar progress-bar-primary" style="width: <?= round($n_graded[$project->project_id] / $n_students[$project->project_id] * 100) ?>%" data-toggle="tooltip" data-placement="left" title="<?= _('Corrigés:') . ' ' . $n_graded[$project->project_id] ?>">
					<span class="sr-only">Corrigés</span>
				</div>
				<div class="progress-bar progress-bar-warning" style="width: <?= round($n_submitted[$project->project_id] / $n_students[$project->project_id] * 100) - round($n_graded[$project->project_id] / $n_students[$project->project_id] * 100) ?>%" data-toggle="tooltip" data-placement="left" title="<?= _('Remis:') . ' ' . $n_submitted[$project->project_id] ?>">
					<span class="sr-only">Remis</span>
				</div>
			</div>
			<!-- results -->
			<div class="progress progress-modal">
				<div class="progress-bar progress-bar-danger" style="width: <?= @round($success[$project->project_id]['fail'] / $n_students[$project->project_id] * 100) ?>%" data-toggle="tooltip" data-placement="left" title="<?= _('Moins de 50%:') . ' ' .  $success[$project->project_id]['fail'] ?>">
					<span class="sr-only">Echecs</span>
				</div>
				<div class="progress-bar progress-bar-warning" style="width: <?= @round($success[$project->project_id]['pass'] / $n_students[$project->project_id] * 100)?>%" data-toggle="tooltip" data-placement="left" title="<?= _('Entre 50 et 79%:') . ' ' . $success[$project->project_id]['pass'] ?>">
					<span class="sr-only">Entre 50 et 79%</span>
				</div>
				<div class="progress-bar progress-bar-success" style="width: <?= @round($success[$project->project_id]['success'] / $n_students[$project->project_id] * 100)?>%" data-toggle="tooltip" data-placement="left" title="<?= _('Plus de 79%:') . ' ' .  $success[$project->project_id]['success'] ?>">
					<span class="sr-only">Plus de 79%</span>
				</div>
			</div>
			<div class="panel-footer clearfix">
				<?php if (countdown($project->raw_deadline)): ?>
					<span class="label label-info">J - <?= countdown($project->raw_deadline); ?></span>
				<?php endif ?>

				<span class="label label-<?php

				if($project->is_activated && countdown($project->raw_deadline)) echo 'success';
				elseif ($project->is_activated && !countdown($project->raw_deadline)) echo 'danger';
				else echo 'warning';

				?>">
				<?php
				if($project->is_activated && countdown($project->raw_deadline)) echo _('En cours');
				elseif ($project->is_activated && !countdown($project->raw_deadline)) echo _('Terminé');
				elseif (!$project->is_activated) echo _('Désactivé');
				?>
			</span>

			<div class="dropdown pull-right">
				<a data-toggle="modal" data-target="#projectModal" href="/admin/project/management/<?= $project->project_id; ?>?modal=1" class="btn btn-default btn-xs" role="button" data-toggle="tooltip" title=<?= _('Modifier')?>> <span class="glyphicon glyphicon-pencil"></span></a>
				<a data-toggle="modal" data-target="#projectModal"  href="/admin/project/instructions/<?= $project->project_id ?>?modal=1" class="btn btn-default btn-xs" role="button" data-toggle="tooltip" title=<?= _('Consignes')?>> <span class="glyphicon glyphicon-file"></span> </a>
				<a data-toggle="modal" data-target="#projectModal"  href="/admin/project/statistics/<?= $project->project_id ?>?modal=1" class="btn btn-default btn-xs" role="button" data-toggle="tooltip" title=<?= _('Statistiques')?>> <span class="glyphicon glyphicon-stats"></span> </a>
			</div>
		</div>
	</div>
</div>

<?php

if ($projects[$curr]->class !== @$projects[$curr + 1]->class && $projects[$curr]->term == @$projects[$curr + 1]->term)
{
	echo '<hr style="border-top:dashed 1px #ddd; width:100%"/></div>';
}
elseif ($projects[$curr]->class !== @$projects[$curr + 1]->class)
{
	echo '</div>';
}
elseif ($projects[$curr]->term !== @$projects[$curr + 1]->term)
{
	echo '</div> <!-- row -->';
}
$curr++;
?>
<?php endforeach ?>


</div>

<!-- Modal -->
<div class="modal sudo"  id="projectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="padding:1em;">
		</div>
	</div>
</div>

<!-- Updates modal-->
<script>
$(document).on('hidden.bs.modal', function (e) {
	$(e.target).removeData('bs.modal');
});
</script>
