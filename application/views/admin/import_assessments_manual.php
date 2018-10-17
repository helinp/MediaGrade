<div id="content" class="col-xs-10 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau hidden-print">
		<div class="col-xs-6  col-md-6">
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<h3><?= _('Sélection des fichiers') ?></h3>
			<div class="alert alert-info" style="margin-top:1em;" role="alert">
				<?= _('Importez ici les évaluations papier une à une.') ?>
			</div>

		</div>
	</div>
	<div class="row">
		<div class="col-xs-4">
			<?php echo form_open_multipart('/admin/import/upload');?>
				<div class="form-group">
					<label for="project_id"><?= _('Projet') ?></label>
					<select name="project_id" class="form-control">
						<?php foreach ($projects as $project): ?>
						<option value="<?= $project->project_id ?>" data-class="<?= $project->class ?>"><?= $project->term_name ?> | <?= $project->course_name ?> | <?= $project->project_name?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group">
					<label for="user_id"><?= _('Élève') ?></label>
					<select name="user_id" class="form-control">
						<?php foreach ($students as $student): ?>
						<option value="<?= $student->id ?>"><?= $student->class_name ?> | <?= $student->last_name ?> <?= $student->first_name ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group">
					<label for="project_id"><?= _('Fichier') ?></label>
					<input type="file" name="userfile" />
					<?php if (isset($error)): ?>
						<span><?= strip_tags($error) ?></span>
					<?php endif ?>
				</div>

				<div class="form-group">
					<input type="submit" class="btn btn-primary" value="Traiter le(s) fichier(s) PDF" name="action">
				</div>
		</form>
		<?php if(isset($not_found) && ! empty($not_found)): ?>
		<div class="alert alert-warning" style="margin-top:1em;" role="alert">
			<h4><?= _('Les pages suivantes n\'ont pas été importées:') ?></h4>
			<?php foreach ($not_found as $entry): ?>
					<?= $entry ?><br/>
			<?php endforeach ?>
		</div>
	<?php endif ?>
		</div>
	</div>
</div> <!-- ./ content -->
