<div id="content" class="col-xs-10 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau hidden-print">
		<div class="col-xs-6  col-md-6">
		</div>
		<div class="col-xs-6 col-md-6">
			<form id="filter" action="" method="get" class="form-inline" style="margin-top:1.5em">
				<label><?= _('Période: ') ?></label>
				<select class="form-control input-sm" name="term" onchange="this.form.submit()">
					<option value=""><?= _('Toutes')?></option>
					<?php foreach($terms as $term): ?>
						<?= '<option value="' . $term->id. '"' . (@$_GET['term'] === $term->id ? 'selected' : '') . '>' . $term->name . '</option>' . "\n" ?>
					<?php endforeach?>
				</select>
				<label><?= _('Année scolaire') ?>: </label>
				<select class="form-control input-sm" name="school_year" onchange="this.form.submit()">
					<?php foreach($school_years as $school_year): ?>
						<?= '<option value="' . $school_year->school_year . '"' . (@$_GET['school_year'] === $school_year->school_year ? 'selected' : '') . '>' . $school_year->school_year . '</option>' . "\n" ?>
					<?php endforeach?>
				</select>
			</form>
		</div>
	</div>


	<!-- BY CLASS -->
	<h3><?= _('Classes') ?></h3>
	<div class="row">
		<form method="POST" action="/admin/export/pdf_student_report">
			<?php foreach($students_list as $id => $class): ?>
				<div class="col-xs-12 col-md-6">
					<div class="form-group">
						<h4><?= $this->Classes_model->getClass($id)->name ?></h4>
						<select class="form-control" name="students_id[]" size="10" multiple>
							<?php foreach($class as $student): ?>
								<option value="<?= $student->id?>"><?= $student->first_name . ' ' . $student->last_name?></option>
							<?php endforeach ?>
						</select>
					</div>

				</div>
			<?php endforeach ?>
		</div>
		<div class="row">
			<div class="col-md-3">
				<input type="submit" class="btn btn-primary" value="<?= _('Télécharger les rapports')?>">
			</div>
		</div>
	</form>
</div>
