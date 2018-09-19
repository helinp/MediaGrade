<div id="content" class="col-xs-10 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau">
	</div>
	<div class="alert alert-danger" style="margin-top:1em;" role="alert"><?= LABEL_ADMIN_DANGER ?></div>


	<h3>Ajouter un cours</h3>
	<?php echo form_open('/admin/classes/courses/add', array("class"=>"form-inline")); ?>
	<div class="form-group">
		<div class="input-group">
			<select class="form-control" name="class_id" required>
				<option value="" disabled selected>Classe</option>
				<?php foreach ($classes as $class):?>
					<option value="<?= $class->id?>"><?= $class->description ?></option>
				<?php endforeach ?>
			</select>
		</div>
		<div class="input-group">
			<select class="form-control" name="teacher_id" required>
				<option value="" disabled selected>Professeur</option>
				<?php foreach ($teachers as $teacher):?>
					<option value="<?= $teacher->id?>"<?= ($teacher->id === $this->session->id ? ' selected' : '') ?>><?= mb_strtoupper($teacher->last_name) ?> <?= $teacher->first_name ?></option>
				<?php endforeach ?>
			</select>
		</div>
		<div class="input-group">
			<input type="text" name="name" placeholder="Nom bref" class="form-control" id="name" required />
		</div>
		<div class="input-group">
			<input type="text" name="description" placeholder="Description" class="form-control" id="description" />
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success">Ajouter</button>
	</div>
	<?php echo form_close(); ?>


	<h3>Liste des cours (<?php echo(count($courses)) ?>)</h3>
	<table class="table table-striped table-bordered">
		<tr>
			<th>Classe</th>
			<th>Professeur</th>
			<th>Cours</th>
			<th>Description</th>
			<th>Actions</th>
		</tr>
		<?php foreach($courses as $c){ ?>
			<tr>
				<td><?php echo $c->class_description; ?></td>
				<td><?php echo mb_strtoupper($c->teacher_last_name); ?></td>
				<td><?php echo $c->name; ?></td>
				<td><?php echo $c->description; ?></td>
				<td>
					<a href="<?php echo site_url('/admin/classes/courses/edit/'.$c->id); ?>" class="btn btn-info btn-xs">Editer</a>
					<a href="<?php echo site_url('/admin/classes/courses/delete/'.$c->id); ?>" class="btn btn-danger btn-xs">Supprimer</a>
				</td>
			</tr>
		<?php } ?>
	</table>
</div>
