<div class="row chapeau chapeau-modal">
	<div class="col-md-12">
		<h2> <?= _('Modifier') ?></h2>
	</div>
</div>
<div class="row">

	<?php echo form_open('/admin/classes/edit_class/'.$class->id,array("class"=>"form-horizontal")); ?>

	<div class="form-group">
		<label for="name" class="col-md-4 control-label">Nom court</label>
		<div class="col-md-6">
			<input type="text" name="name" value="<?php echo ($this->input->post('name') ? $this->input->post('name') : $class->name); ?>" class="form-control" id="name" />
		</div>

		<label for="name" class="col-md-4 control-label">Description</label>
		<div class="col-md-6">
			<input type="text" name="description" value="<?php echo ($this->input->post('description') ? $this->input->post('description') : $class->description); ?>" class="form-control" id="name" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-8">
			<button type="submit" class="btn btn-success">Sauver</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>
	<?php echo form_close(); ?>
	<div class="row">
