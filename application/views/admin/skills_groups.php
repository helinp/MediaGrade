<div id="content" class="col-xs-12 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau">
		<div class="col-xs-12  col-md-12">
			<h1> <?= _('Pôles de compétences')?></h1>
		</div>
	</div>
	<div class="alert alert-danger" style="margin-top:1em;" role="alert"><?= LABEL_ADMIN_DANGER ?></div>
	<form action="/admin/skills/groups/add_skills_group" method="post">
		<h3><?=_('Ajouter')?></h3>
		<div class="form-group ">
			<div class="input-group col-md-4">
				<input name="skills_group" type="text" placeholder="<?=_('Nom du nouveau pôle')?>" class="form-control input-md" required>
				<span class="input-group-btn">
					<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-plus"></span></button>
				</span>
			</div>
		</div>
	</form>
	<form action="/admin/skills/groups/del_skills_group" method="post">
		<h3><?=_('Pôles actuels')?></h3>
		<div class="form-group">
			<div class="input-group col-md-4">
				<select  name="skills_groups" class="form-control" size="5">
					<?php foreach ($skills_groups as $skills_group): ?>
						<option value="<?= $skills_group->id ?>"><?= $skills_group->name ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<form action="/admin/skills/groups/add_skills_group" method="post">
			<h3><?=_('Supprimer')?></h3>
			<div class="form-group ">
				<div class="input-group col-md-4">
					<input name="skills_group" type="text" placeholder="<?=_('Nom du pôle à supprimer')?>" class="form-control input-md" required>
					<span class="input-group-btn">
						<button class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-trash"></span></button>
					</span>
				</div>
			</div>
		</form>

		<!-- WORK IN PROGRESS

		<h3><?=_('Compétences reprises dans ce groupe')?></h3>

		<div class="form-group">
		<div class="input-group col-md-4">
		<select id="selectmultiple" name="selectmultiple" class="form-control" multiple="multiple">
		<option value="1">Option one</option>
		<option value="2">Option two</option>
	</select>
	<span class="help-block"><small><?=_('Sélectionnez des compétences à insérer dans le groupe.')?></small></span>
</div>
</div>
<h3><?=_("Compétences ne faisant partie d'aucun groupe")?></h3>

<div class="form-group">
<div class="input-group col-md-4">
<select id="selectmultiple" name="selectmultiple" class="form-control" multiple="multiple">
<option value="1">Option one</option>
<option value="2">Option two</option>
</select>
<span class="help-block"><small><?=_('À titre indicatif')?></small></span>
</div>
</div>-->
</div>
