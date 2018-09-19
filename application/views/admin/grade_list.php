<div id="content" class="col-xs-10 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau">
		<div class="col-md-12">
			<form id="filter" action="" method="get" class="form-inline" style="margin-top:1.5em">
				<div class="pull-right">
					<label><?= _('Classe') ?>: </label>
					<div class="input-group">
						<select class="form-control input-sm" name="class" onchange="this.form.submit()">
							<option value="all"><?php echo _('Toutes') ?></option>
							<?php foreach($classes as $class): ?>
								<?= '<option value="' . $class->id . '"' . ($this->uri->segment(4) === $class->id ? ' selected' : '') . '>' . $class->description . '</option>' . "\n" ?>
							<?php endforeach?>
						</select>
					</div>
					<label><?= _('Période') ?>: </label>
					<div class="input-group">
						<select class="form-control input-sm" name="term" onchange="this.form.submit()">
								<option value="all"><?php echo _('Toutes') ?></option>
							<?php foreach($terms as $term): ?>
								<?= '<option value="' . $term->id . '"' . ($this->uri->segment(5) === $term->id ? ' selected' : '') . '>' . $term->name . '</option>' . "\n" ?>
							<?php endforeach?>
						</select>
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php foreach($grade_table as $class): ?>
		<h3><?= array_values($class)[0]['user']->class_name ?></h3>
		<div class="row">
			<?php foreach($class as $user_projects): ?>
				<div class="col-md-3 thumb">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?= mb_strtoupper($user_projects['user']->last_name) . " ". $user_projects['user']->first_name ?>
						</div>

						<ul class="list-group">
							<?php foreach($user_projects['projects'] as $project): ?>
								<li class="list-group-item">
									<?php if ($project->is_graded): ?>
										<span style="color:green" class="glyphicon glyphicon-check"></span>
									<?php elseif ($project->is_submitted): ?>
										<span style="color:red" class="glyphicon glyphicon-edit"></span>
									<?php else: ?>
										<span style="color:gray" class="glyphicon glyphicon-inbox"></span>
									<?php endif ?>
									<a data-toggle="modal" data-target="#projectModal"  href="/admin/grade/assess/<?= $user_projects['user']->class ?>/<?= $project->project_id ?>/<?= $user_projects['user']->id?>?origin=<?= htmlentities($_SERVER['REQUEST_URI']) ?>" class="text-muted" >
										<small><?= $project->term_name ?> - </small>  <?= $project->project_name?>
									</a>
								</li>
							<?php endforeach ?>
						</ul>

					</div>
				</div>
			<?php endforeach ?>
		</div>
	<?php endforeach ?>
</div>
<!-- Modal -->
<div class="modal sudo"  id="projectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="padding:1em;">
		</div>
	</div>
</div>

<script>
// this is just an example, remember to adapt the selectors to your code!
$('body').on('hidden', '.modal', function () {
	$(this).removeData('modal');
});

$('.modal-link').click(function(e) {
	var modal = $('#modal'), modalBody = $('#projectModal .modal-body');

	modal
	.on('show.bs.modal', function () {
		modalBody.load(e.currentTarget.href)
	})
	.modal();
	e.preventDefault();
});
</script>
