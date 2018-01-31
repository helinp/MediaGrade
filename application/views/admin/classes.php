<div id="content" class="col-xs-12 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau">
	</div>
	<div class="alert alert-danger" style="margin-top:1em;" role="alert"><?= LABEL_ADMIN_DANGER ?></div>


	<h3>Ajouter une classe</h3>
	<?php echo form_open('/admin/classes/classes/add', array("class"=>"form-inline")); ?>
	<div class="form-group">
		<div class="input-group">
			<input type="text" name="name" placeholder="Nom de la classe" class="form-control" id="name" required />
		</div>
		<div class="input-group">
			<input type="text" name="description" placeholder="Description" class="form-control" id="description" />
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success">Ajouter</button>
	</div>
	<?php echo form_close(); ?>


	<h3>Liste des classes (<?php echo(count($classes)) ?>)</h3>
	<table class="table table-striped table-bordered">
		<tr>
			<th>Nom</th>
			<th>Description</th>
			<th>Actions</th>
		</tr>
		<?php foreach($classes as $c){ ?>
			<tr>
				<td><?php echo $c->name; ?></td>
				<td><?php echo $c->description; ?></td>
				<td>
					<a href="<?php echo site_url('/admin/classes/edit_class/'.$c->id); ?>" data-toggle="modal" data-target="#modal" title="Modifier" class="btn btn-info btn-xs">Editer</a>
					<a href="<?php echo site_url('/admin/classes/classes/delete/'.$c->id); ?>" class="btn btn-danger btn-xs">Supprimer</a>
				</td>
			</tr>
		<?php } ?>
	</table>
</div>

<!-- Modal -->
<div class="modal sudo"  id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
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
