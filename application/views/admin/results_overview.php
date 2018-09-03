<div id="content" class="col-xs-12 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau">
		<div class="col-xs-4  col-md-4">
		</div>
		<div class="col-xs-8  col-md-8">
			<form id="filter" method="get" class="form-inline" style="margin-top:1.5em">
				<div class="pull-right">

					<label><?= _('Classe') ?>: </label>

					<select class="form-control input-sm" name="classe" onchange="this.form.submit()">
						<option value="all" <?=( @$_GET['classe'] === 'all' ? ' selected' : '') ?>><?= _('Toutes les classes') ?></option>
						<?php foreach($classes as $class): ?>
							<?= '<option value="' . $class->id . '"' . ( @$_GET['classe'] === $class->id ? ' selected' : '') . '>' . $class->description . '</option>' . "\n" ?>
						<?php endforeach?>
					</select>

					<label><?= _('Élève') ?>: </label>

					<select id="student-select" class="form-control input-sm" name="student" onchange="this.form.submit()">
						<?php foreach($students as $class): ?>
							<?php foreach($class as $row): ?>
								<?= '<option value="' . $row->id . '"' . ( $this->uri->segment(4) === $row->id  ? ' selected' : '') . '>' . $row->first_name . ' ' . $row->last_name . '</option>' . "\n" ?>
							<?php endforeach ?>
						<?php endforeach?>
					</select>
				</div>
			</form>
		</div>
	</div>
	<?php $term_tmp = NULL; $curr = 0; $projects = array_reverse($projects); ?>
	<?php foreach ($projects as $project):?>
		<div class="row results-overview-row">
			<div class="col-md-3">
				<h4><?= $project->project_name; ?><br /><small><?= $project->term_name; ?> / <?= $project->deadline; ?></small></h4>
				<?php if(@$project->submitted[0]->file || $project->number_of_files == 0 ): ?>
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
