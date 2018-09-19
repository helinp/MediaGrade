<!-- https://github.com/twitter/typeahead.js/ -->
<script src="/assets/js/typeahead.bundle.js"></script>
<script src="/assets/js/scripts.js"></script>

<?php if($this->input->get('modal')):?>
	<div class="row chapeau chapeau-modal">
		<div class="col-xs-12  col-md-12">
			<h2 class=" text-left"> <?= _('Gestion du projet')?><small> / <?= (isset($curr_project->project_name) ? $curr_project->project_name : _('Nouveau projet') )?></small></h2>
		</div>
	</div>
<?php endif ?>

<?php if( ! $this->input->get('modal')):?>
	<div id="content" class="col-xs-10 col-md-10 ">
		<?php $this->view('templates/submenu'); ?>
	<?php endif ?>
	<form action="/admin/project/record/<?= @$curr_project->id ?>" method="post" enctype="multipart/form-data" id="form"  style="margin-top: 1em;">
		<div class="row">
			<div class = "col-md-6">
				<div class="form-group">
					<label for="title"><?= LABEL_PROJECT_TITLE ?></label>
					<input type="text" class="form-control" id="title" name="project_name" value="<?= @$curr_project->project_name ?>" required>
				</div>
			</div>
			<div class = "col-xs-3">
				<label for="title"><?=  LABEL_ASSESSMENT_TYPE ?></label>
				<select class="form-control" name="assessment_type" required>
					<option value="diagnostic"<?php if(@$curr_project->assessment_type ===  'diagnostic') echo(" selected"); ?>><?= LABEL_ASSESSMENT_DIAGNOSTIC?></option>
					<option value="type_1"<?php if(@$curr_project->assessment_type ===  'type_1') echo(" selected"); ?>><?= LABEL_ASSESSMENT_TYPE_1?></option>
					<option value="type_2"<?php if(@$curr_project->assessment_type ===  'type_2') echo(" selected"); ?>><?= LABEL_ASSESSMENT_TYPE_2?></option>
					<option value="certificative"<?php if(@$curr_project->assessment_type ===  'certificative') echo(" selected"); ?>><?= LABEL_ASSESSMENT_CERTIFIED?></option>
				</select>
			</div>
			<div class = "col-xs-3">
				<label for="title"><?= LABEL_PERIOD ?></label>
				<select class="form-control" name="term">
					<?php foreach($terms as $term): ?>
						<option<?php if(@$curr_project->term === $term->id) echo(' selected'); ?> value="<?= $term->id ?>"><?= $term->name ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class = "col-md-3">
				<div class="form-group">
					<label class="control-label"><?=  _('Début du projet') ?></label>
					<div class="date">
						<div class="input-group input-append date datePicker">
							<input type="text" class="form-control" name="start_date" required value="<?= @$curr_project->start_date?>" />
							<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
						</div>
					</div>
				</div>
			</div>
			<div class = "col-md-3">
				<div class="form-group">
					<label class="control-label"><?=  _('Deadline (jour inclu)') ?></label>
					<div class="date">
						<div class="input-group input-append date datePicker">
							<input type="text" class="form-control" name="deadline" required value="<?= @$curr_project->deadline?>" />
							<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
						</div>
					</div>
				</div>
			</div>
			<div class = "col-xs-3">
				<div class="form-group">
					<label for="title"><?=  LABEL_CLASS ?></label>
					<select class="form-control" name="class" style=" white-space: nowrap;
					overflow: hidden;
					text-overflow: ellipsis;" required>
					<?php foreach($classes as $class): ?>
						<option  <?php if(@$curr_project->class === $class->id) echo(" selected"); ?> value="<?= $class->id?>"><?= $class->description ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class = "col-xs-3">
			<div class="form-group">
				<label for="title"><?= _('Cours') ?></label>
				<select class="form-control" name="course" style=" white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis;" disabled>
				<option value="">--</option>
				<?php foreach($courses as $course): ?>
					<option  <?php if(@$curr_project->course === $course->id) echo(" selected"); ?>><?= $course->class_name ?> <?= $course->name ?></option>
				<?php endforeach ?>
			</select>
		</div>
	</div>

</div>
<div class="row">
	<div class = "col-xs-12">
		<div class="form-group">
			<label for="title"><?=  LABEL_SKILLS_SEEN ?></label>
			<select id="select_skills" multiple class="form-control" name="seen_skill_ids[]" required size="10">
				<?php foreach ($skills as $skill): ?>
					<option<?= (@in_array($skill->skill_id, (array) @$active_skills) ? ' selected' : '') ?> value="<?= $skill->skill_id ?>">
					<?= $skill->skill_id . ' ' . word_limiter($skill->skill, 15) ?>
				</option>
			<?php endforeach ?>
		</select>
		<p class="help-block with-errors"><?=  LABEL_PRESS_CTRL_SELECT ?></p>
	</div>
</div>
</div>
<div class="row">
	<div class = "col-xs-12">
		<div class="form-group">
			<label for="title"><?= _('Matières abordées') ?></label>
			<input class="form-control input-sm" value="<?= @$curr_project->material ?>" name="material" />
			<p class="help-block with-errors"><?=  _('Séparez les termes par une virgule.') ?></p>
		</div>
	</div>
</div>

<div class="row">
	<div class = "col-xs-12">
		<div class="form-group">
			<h3><?= LABEL_PROJECT_FILE_INFO?></h3>
			<label class="control-label"><?=  LABEL_UPLOAD_INSTRUCTIONS ?></label>
			<input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_UPLOAD_FILE_SIZE ?>" />
			<input id="inputfile" name="instructions_pdf" data-error="<?=  LABEL_NO_FILE ?>"  type="file">
			<p class="help-block"><a href="../../assets/<?= @$curr_project->instructions_pdf ?>" target="_blank"><?= @$curr_project->instructions_pdf ?></a></p>
			<p class="help-block with-errors"><?=  LABEL_ONLY_PDF_ALLOWED ?></p>
		</div>
	</div>
</div>

<!-- MISE EN SITUATION / CONSIGNES-->
<label><?= _('Mise en situation') ?></label>
<textarea name="context_txt" class="form-control" rows="3"><?= @$curr_project->instructions_txt['context'] ?></textarea>
<p class="help-block">HTML tags: <code style="color:gray;"><?= htmlspecialchars($this->config->item('allowed_html_tags')) ?></code> <br />Custom: <code style="color:gray;">%user_name% %user_lastname%</code></p>

<label><?= _('Consignes') ?></label>
<textarea name="instructions_txt" class="form-control tinymce" rows="12"><?= @$curr_project->instructions_txt['instructions'] ?></textarea>
<p class="help-block">HTML tags: <code style="color:gray;"><?= htmlspecialchars($this->config->item('allowed_html_tags')) ?></code> <br />Custom: <code style="color:gray;">%user_name% %user_lastname%</code></p>
<hr />
<h3><?= _('Remises')?></h3>
<div class="row">
	<div class = "col-xs-4">
		<div class="form-group">
			<label class="control-label"><?=  LABEL_EXPECTED_FILE ?></label>
			<select class="form-control" name="extension">
				<option value="" <?= (empty($curr_project->extension) ? " selected" : "")?>><?= _('Pas de remise fichier')?></option>
				<option disabled>---</option>
				<?php foreach ($file_formats as $format): ?>
					<option<?= (@$curr_project->extension == $format->extension ? ' selected' : '')?> value="<?= $format->extension ?>"><?= strtoupper($format->extension) ?></option>
				<?php endforeach ?>
			</select>
		</div>
	</div>
	<div class = "col-xs-4">
		<div class="form-group">
			<label class="control-label"><?= LABEL_HOW_MANY_FILES ?></label>
			<select class="form-control" name="number_of_files">
				<?php $n = 0; while($n <= 8): ?>
					<option<?= (@$curr_project->number_of_files == $n ? " selected" : "")?>><?= $n ?></option>
					<?php $n++; endwhile ?>
				</select>
			</div>
		</div>
		<div class = "col-xs-4">
			<!-- <div class="form-group">
			<label class="control-label"><?=  _('Nombre minimum de fichiers à remettre') ?></label>
			<select class="form-control" name="minimum_number_of_files">
			<?php $n = 1; while($n <= 8): ?>
			<option<?= (@$curr_project->number_of_files == $n ? " selected" : "")?>><?= $n ?></option>
			<?php $n++; endwhile ?>
		</select>
	</div> -->
</div>
</div>

<div class="row">
	<div class="col-md-12">
		<h3><?=  LABEL_ASSESSMENT_GRID ?></h3>
		<table id="rows" class="table">
			<thead>
				<tr>
					<th><?= ($this->config->item('assessment_mode') === 'skills_group' ? _('Pôle') : _('Compétence')) ?></th>
					<th><?= LABEL_CRITERION?></th>
					<th><?= LABEL_CURSOR?></th>
					<th><?= _('Type d\'évaluation') ?></th>
					<th><?= LABEL_COEFFICIENT ?></th>
					<th><?= _('Badge') ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($assessment_table as $row): ?>
					<tr>
						<td>
							<?php if($this->config->item('assessment_mode') === 'skills_group'): ?>
								<select class="form-control input-sm" name="skills_group[]" required>
									<?php foreach($skills_groups as $skills_group): ?>
										<option<?php if($skills_group->name === $row->skills_group) echo(' selected') ?>><?= $skills_group->name ?></option>
									<?php endforeach ?>
								<?php else: ?>
									<select class="form-control input-sm" name="skill_ids[]" required>
										<option value="0">--</option>
										<?php foreach($skills as $skill): ?>
											<option value="<?= $skill->id ?>"<?php if($skill->skill_id === @$row->skill_id) echo(' selected') ?>><?= $skill->skill_id ?></option>
										<?php endforeach ?>
									<?php endif ?>
								</select>
							</td>
							<td>
								<input class="typeahead_criterion form-control input-sm" placeholder="Critère" value="<?= $row->criterion ?>" name="criterion[]" required />
							</td>
							<td>
								<textarea class="typeahead_cursors form-control input-sm" cols="50" rows="3"
								placeholder="<?=  LABEL_NEW_CRITERION ?>" name="cursor[]" required><?= $row->cursor ?></textarea>
							</td>
							<td>
								<select class="form-control input-sm" name="grading_type[]" required>
									<option value="4-steps"  <?php if('4-steps' === @$row->grading_type) echo(' selected') ?>>NA-EA-A-M</option>
									<option value="default"  <?php if('default' === @$row->grading_type) echo(' selected') ?>>1-10</option>
								</select>
							</td>
							<td>
								<input class="form-control input-sm" value="<?= $row->max_vote ?>" name="max_vote[]" required size="1" />
							</td>
							<td>
								<select class="form-control input-sm" name="achievement_id[]">
									<option value="">--</option>
									<?php foreach($achievements as $achievement): ?>
										<option value="<?= $achievement->id ?>"<?php if($achievement->id === @$row->achievement_id) echo(' selected') ?>><?= $achievement->name ?> <?= str_repeat('&#9734;', $achievement->star)?></option>
									<?php endforeach ?>
								</select>
								<input type="hidden" name="assessment_id[]" value="<?= $row->id ?>" />
							</td>

							<td>
								<button type="button" class="btn btn-info btn-xs btn-danger pull-right" onClick="deleteRow('rows', this)">
									<span class="glyphicon glyphicon-remove"></span> </button>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-12">
				<div class="form-group">
					<button type="button" class="btn btn-info btn-xs" onClick="addRow('rows')"><span class="glyphicon glyphicon-plus"></span> <?= LABEL_ADD_CRITERION ?></button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class = "col-md-12">
				<h3><?=  LABEL_SELF_ASSESSMENT ?></h3>
				<div class="col-md-12">
					<?php foreach($self_assessments as $self_assessment): ?>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="<?= $self_assessment->id ?>" name="self_assessment_id[]" <?= (@in_array($self_assessment->id, $active_self_assessments) ? 'checked' : '') ?>>
								<?= $self_assessment->question ?>
							</label>
						</div>
					<?php endforeach ?>
				</div>
				<div class="col-md-6">
					<div class="auto-assessments">
						<div class="checkbox">
							<textarea class="form-control input-sm " rows="3" placeholder="<?= _('Nouvelle question') ?>" name="new_self_assessment[]"></textarea>
						</div>
					</div>
					<div class="form-group">
						<button type="button" class="btn btn-xs add_auto-assessment"><span class="glyphicon glyphicon-plus"></span> <?= _('Ajouter l\'auto-évaluation') ?></button>
					</div>
				</div>
			</div>
		</div>

		<div class = "row">
			<div class = "col-md-12">
				<div class="form-group">
					<input type="hidden" name="project_id" value="<?= (isset($curr_project->id) ? $curr_project->id : '-1'); ?>">
					<?php if(isset($curr_project->id)): ?>
						<?php if($curr_project->school_year === get_school_year()): ?>
							<button type="submit" class="btn btn-success" name="update_project" value="1"><span class="glyphicon glyphicon-save"></span> <?=  LABEL_SAVE_PROJECT ?></button>
							<button type="submit" class="btn btn-primary" name="duplicate_project" value="1"><span class="glyphicon glyphicon-copy"></span> <?=  _('Dupliquer') ?></button>
							<button type="submit" class="btn btn-danger pull-right" name="delete_project" value="<?= $curr_project->id ?>"><span class="glyphicon glyphicon-remove"></span><?=  LABEL_DEL_PROJECT ?></button>
							<button type="submit" class="btn <?= (@$curr_project->is_activated ? 'btn-warning' : 'btn-success'); ?>  pull-right"  style="margin-right:0.5em;" name="disactivate_project" value="<?= $curr_project->id ?>">
								<span class="glyphicon glyphicon-ban-circle"></span> <?= (@$curr_project->is_activated ?  LABEL_DISACTIVATE_PROJECT :  LABEL_ACTIVATE_PROJECT)?>
							</button>

						<?php else: ?>
							<button type="submit" class="btn btn-primary" name="duplicate_project" value="1"><span class="glyphicon glyphicon-copy"></span> <?=  _('Dupliquer') ?></button>
							<p><?= _('Projet verrouillé (année scolaire ' . @$curr_project->school_year  . ').')?></p>
						<?php endif ?>
					<?php else: ?>

						<button type="submit" class="btn btn-success" name="save_project" value="1"><span class="glyphicon glyphicon-save"></span> <?=  LABEL_SAVE_PROJECT ?></button>
					<?php endif ?>
				</div>
			</div>
		</div>
	</form>
</div>

<script src="/assets/js/jquery-1.10.2.min.js"></script>
<script src="/assets/js/typeahead.bundle.js"></script>
<script>
/**
*
*  Typeahead
*
**/


$('.typeahead_cursors').typeahead({
	hint: true,
	highlight: true,
	minLength: 2
},
{
	limit: 6,
	async: true,
	displayKey: 'cursor',
	source: function (query, processSync, processAsync) {

		return $.ajax({
			url: "/typeahead",
			type: 'GET',
			data: {cursor: query},
			dataType: 'json',

			success: function (json) {
				return processAsync(json);
			}
		});
	}
});

$('.typeahead_criterion').typeahead({
	hint: true,
	highlight: true,
	minLength: 2
},
{
	limit: 6,
	async: true,
	displayKey: 'criterion',
	source: function (query, processSync, processAsync) {

		return $.ajax({
			url: "/typeahead",
			type: 'GET',
			data: {criterion: query},
			dataType: 'json',

			success: function (json) {
				return processAsync(json);
			}
		});
	}
});

</script>

<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/calendar/js/bootstrap-datepicker.min.js"></script>
<script>
$('.datePicker').datepicker({
	format: "yyyy-m-dd",
	maxViewMode: 2,
	todayBtn: true,
	language: "fr",
	calendarWeeks: true,
	autoclose: true,
	todayHighlight: true
});
</script>

<script src="STOP/assets/js/bootstrap-datepicker.min.js"></script>
<script>
/*
$(document).ready(function() {
$('#datePicker')
.datepicker({
format: 'yyyy-mm-dd'
})
.on('changeDate', function(e) {
// Revalidate the date field
$('#eventForm').formValidation('revalidateField', 'date');
});
});*/
</script>

<script src="/assets/js/tinymce/tinymce.min.js"></script>
<script>

tinymce.init({
	selector: '.tinymce',
	plugins: [
		'advlist autolink lists link image preview anchor',
		'searchreplace visualblocks code',
		'insertdatetime media table paste code'
	],
	toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
	content_css: [
		'//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
		'//www.tinymce.com/css/codepen.min.css'
	]
});

</script>
