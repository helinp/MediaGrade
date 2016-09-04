<!-- https://github.com/twitter/typeahead.js/ -->
<script src="/assets/js/typeahead.bundle.js"></script>
<script src="/assets/js/scripts.js"></script>


<div class="row chapeau chapeau-modal">
		<div class="col-xs-12  col-md-12">
				<h2 class=" text-left"> <?= _('Gestion du projet')?><small> / <?= (isset($curr_project->project_name) ? $curr_project->project_name : _('Nouveau projet') )?></small></h2>
		</div>
</div>


<form action="/admin/project_management/<?= @$curr_project->id ?>" method="post" enctype="multipart/form-data" id="form">
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
				<option<?php if(@$curr_project->assessment_type ===  LABEL_ASSESSMENT_TYPE_1) echo(" selected"); ?>><?= LABEL_ASSESSMENT_TYPE_1?></option>
				<option<?php if(@$curr_project->assessment_type ===  LABEL_ASSESSMENT_TYPE_2) echo(" selected"); ?>><?= LABEL_ASSESSMENT_TYPE_2?></option>
				<option<?php if(@$curr_project->assessment_type ===  LABEL_ASSESSMENT_TYPE_3) echo(" selected"); ?>><?= LABEL_ASSESSMENT_TYPE_3?></option>
				<option<?php if(@$curr_project->assessment_type ===  LABEL_ASSESSMENT_TYPE_4) echo(" selected"); ?>><?= LABEL_ASSESSMENT_TYPE_4?></option>
			</select>
		</div>
		<div class = "col-xs-3">
			<label for="title"><?= LABEL_PERIOD ?></label>
			<select class="form-control" name="term">
				<?php foreach($terms as $term): ?>
				<option<?php if(@$curr_project->term === $term) echo(' selected'); ?>><?= $term ?></option>
				<?php endforeach ?>
			</select>
		</div>
	</div>
	<div class="row">
		<div class = "col-md-6">
			<div class="form-group">
				<label class="control-label"><?=  LABEL_DEADLINE ?></label>
				<div class="date">
					<div class="input-group input-append date" id="datePicker">
						<input type="text" class="form-control" name="deadline" required value="<?= @$curr_project->deadline?>" />
						<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
				</div>
			</div>
		</div>
		<div class = "col-xs-4">
			<div class="form-group">
				<label for="title"><?=  LABEL_CLASS ?></label>
				<select class="form-control" name="class" style=" white-space: nowrap;
					overflow: hidden;
					text-overflow: ellipsis;" required>
					<?php foreach($classes as $class): ?>
					<option  <?php if(@$curr_project->class === $class) echo(" selected"); ?>><?= $class ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class = "col-xs-3">
		</div>
	</div>
	<div class="row">
		<div class = "col-xs-12">
			<div class="form-group">
				<label for="title"><?=  LABEL_SKILLS_SEEN ?></label>
				<select id="select_skills" multiple class="form-control" name="skill_ids[]" required size="10">
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
	<hr/>
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
	<hr />
	<!-- MISE EN SITUATION / CONSIGNES-->
	<label><?= _('Mise en situation') ?></label>
	<textarea name="context_txt" class="form-control" rows="3"><?= @$curr_project->instructions_txt['context'] ?></textarea>
	<p class="help-block">HTML tags: <code style="color:gray;"><?= htmlspecialchars(ALLOWED_HTML_TAGS) ?></code> <br />Custom: <code style="color:gray;">%user_name% %user_lastname%</code></p>

	<label><?= _('Consignes') ?></label>
	<textarea name="instructions_txt" class="form-control tinymce" rows="12"><?= @$curr_project->instructions_txt['instructions'] ?></textarea>
	<p class="help-block">HTML tags: <code style="color:gray;"><?= htmlspecialchars(ALLOWED_HTML_TAGS) ?></code> <br />Custom: <code style="color:gray;">%user_name% %user_lastname%</code></p>
<hr />
<h3><?= _('Remises')?></h3>
	<div class="row">
		<div class = "col-xs-4">
			<div class="form-group">
				<label class="control-label"><?=  LABEL_EXPECTED_FILE ?></label>
				<select class="form-control" name="extension">
					<option value="" <?= (empty($curr_project->extension) ? " selected" : "")?>><?= _('Pas de remise fichier')?></option>
					<option disabled>---</option>
					<option<?= (@$curr_project->extension == 'gif' ? ' selected' : '')?>>gif</option>
					<option<?= (@$curr_project->extension == 'jpg' ? ' selected' : '')?>>jpg</option>
					<option<?= (@$curr_project->extension == 'mov' ? ' selected' : '')?>>mov</option>
					<option<?= (@$curr_project->extension == 'avi' ? ' selected' : '')?>>avi</option>
					<option<?= (@$curr_project->extension == 'mp4' ? ' selected' : '')?>>mp4</option>
					<option<?= (@$curr_project->extension == 'wav' ? ' selected ': '')?>>wav</option>
				</select>
			</div>
		</div>
		<div class = "col-xs-4">
			<div class="form-group">
				<label class="control-label"><?= LABEL_HOW_MANY_FILES ?></label>
				<select class="form-control" name="number_of_files">
					<?php $n = 1; while($n <= 8): ?>
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
	<hr/>
	<div class="row">
		<div class="col-md-12">
			<h3><?=  LABEL_ASSESSMENT_GRID ?></h3>
			<table id="rows" class="table">
				<thead>
					<tr>
						<th><?= _('Groupe')?></th>
						<th><?= LABEL_CRITERION?></th>
						<th><?= LABEL_CURSOR?></th>
						<th><?= LABEL_COEFFICIENT?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($assessment_table as $row): ?>
					<tr>
						<td>
							<select class="form-control input-sm" name="skills_group[]" required>
								<?php foreach($skills_groups as $skills_group): ?>
								<option  <?php if($skills_group->name === $row->skills_group) echo("selected") ?>><?= $skills_group->name ?></option>
								<?php endforeach ?>
							</select>
						</td>
						<td><input class="typeahead_criterion form-control input-sm" placeholder="Critère" value="<?= $row->criterion ?>" name="criterion[]" required /></td>
						<td>
							<textarea class="typeahead_cursors form-control input-sm" cols="50" rows="3"
								placeholder="<?=  LABEL_NEW_CRITERION ?>" name="cursor[]" required><?= $row->cursor ?></textarea>
						</td>
						<td> <input class="form-control input-sm" value="<?= $row->max_vote ?>" name="max_vote[]" required size="2" /></td>
						<input type="hidden" name="assessment_id[]" value="<?= $row->id ?>" />
						<td> <button type="button" class="btn btn-info btn-xs btn-danger pull-right" onClick="deleteRow('rows', this)"><span class="glyphicon glyphicon-remove"></span> <?= LABEL_DELETE ?></button></td>
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
	<hr />
	<div class="row">
		<div class = "col-md-12">
			<h3><?=  LABEL_SELF_ASSESSMENT ?></h3>
			<div class="col-md-6">
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
						<textarea class="form-control input-sm " rows="3" placeholder="Nouveau critère (from user)" name="new_self_assessment[]"></textarea>
					</div>
				</div>
				<div class="form-group">
					<button type="button" class="btn btn-xs add_auto-assessment"><span class="glyphicon glyphicon-plus"></span> <?= LABEL_ADD_QUESTION ?></button>
				</div>
			</div>
		</div>
	</div>
	<hr />
	<div class = "row">
		<div class = "col-md-12">
			<div class="form-group">
			<?php if($curr_project->school_year === get_school_year()): ?>
				<button type="submit" class="btn btn-primary"><?=  LABEL_SAVE_PROJECT ?></button>
				<input type="hidden" name="project_id" value="<?= (isset($curr_project->id) ? $curr_project->id : '-1'); ?>">
				<?php if(isset($curr_project->id)): ?>
				<button type="submit" class="btn btn-danger pull-right" name="delete_project" value="<?= $curr_project->id ?>"><span class="glyphicon glyphicon-remove"></span><?=  LABEL_DEL_PROJECT ?></button>
				<button type="submit" class="btn <?= (@$curr_project->is_activated ? 'btn-warning' : 'btn-success'); ?>  pull-right"  style="margin-right:0.5em;" name="disactivate_project" value="<?= $curr_project->id ?>">
				<span class="glyphicon glyphicon-ban-circle"></span> <?= (@$curr_project->is_activated ?  LABEL_DISACTIVATE_PROJECT :  LABEL_ACTIVATE_PROJECT)?>
				</button>
				<?php endif ?>
			<?php else: ?>
				<p><?= _('Projet verrouillé (année scolaire ' . get_school_year() . ').')?></p>
			<?php endif ?>
			</div>
		</div>
	</div>
</form>

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



<script src="/assets/js/bootstrap-datepicker.min.js"></script>
<script>
	$(document).ready(function() {
	    $('#datePicker')
	        .datepicker({
	            format: 'yyyy-mm-dd'
	        })
	        .on('changeDate', function(e) {
	            // Revalidate the date field
	            $('#eventForm').formValidation('revalidateField', 'date');
	        });
	});
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
