<div id="content" class="col-xs-10 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau">

	</div>
	<div class="alert alert-danger" style="margin-top:1em;" role="alert"><?= LABEL_ADMIN_DANGER ?></div>
	<form action="/admin/skills/index/add_skill" method="post" role="form">
		<table id="rows" class="table">
			<col width="5%">
			<col width="10%">
			<col width="80%">
			<col width="5%">
			<thead>
				<tr>
					<th>ID</th>
					<th>Pôle</th>
					<th><?= _('Compétence')?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><input name="skill_id" class="form-control input-sm"></td>
					<td>
						<select name="skills_group" class="form-control input-sm">
							<option>--</option>
							<?php foreach($skills_groups as $skills_group): ?>
								<option value="<?= $skills_group->id ?>"><?= $skills_group->name ?></option>
							<?php endforeach ?>
						</select>
					</td>
					<td><textarea name="skill" class="form-control input-sm" cols="50" rows="3" fixed></textarea></td>
					<td><button type="submit" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus"></span></button></td>
				</tr>
			</tbody>
		</table>
	</form>

	<form action="/admin/skills/index/del_skill" method="post" role="form">
		<table id="rows" class="table table-striped">
			<col width="5%">
			<col width="10%">
			<col width="80%">
			<col width="5%">
			<tbody>
				<?php foreach($skills as $skill): ?>
					<tr>
						<td><?= $skill->skill_id ?></td>
						<td><?= (isset($skills_groups_array[$skill->skills_group]) ? $skills_groups_array[$skill->skills_group] : '--') ?>
						</td>
						<td><?= $skill->skill ?></td>
						<td>
							<button type="submit" class="btn btn-danger btn-xs" name="skill_id" value="<?=  $skill->skill_id  ?>"><span class="glyphicon glyphicon-trash"></span></button>
						</td>
					</tr>
				<?php endforeach?>
			</tbody>
		</table>
	</form>


</div>
