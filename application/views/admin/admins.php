<div id="content" class="col-xs-10 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau">
	</div>
	<div class="alert alert-danger" style="margin-top:1em;" role="alert"><?= LABEL_ADMIN_DANGER ?></div>
	<div class="alert alert-info" style="margin-top:1em;" role="info"><?= _('<b>Conseil:</b> Le nom d\'utilisateur devrait toujours se présenter sous la forme <i>nom.prenom</i> et ne comporter ni d\'accent ni de caractères spéciaux.') ?></div>
	<h3><?= _('Ajouter un professeur')?></h3>
	<form action="/admin/users/add_user" method="post">
		<table id="rows" class="table">
			<col width="15%">
			<col width="15%">
			<col width="15%">
			<col width="15%">
			<col width="15%">
			<col width="5%">
			<col width="10%">
			<thead>
				<tr>
					<th><?= LABEL_LAST_NAME ?></th>
					<th><?= LABEL_NAME ?></th>
					<th><?= LABEL_EMAIL ?></th>
					<th><?= LABEL_USERNAME ?></th>
					<th><?= LABEL_PASSWORD ?></th>
					<th><?= _('Actif?')?></th>
					<th><?= _('Actions')?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><input name="last_name" class="form-control input-sm" required></td>
					<td><input name="name" class="form-control input-sm" required></td>
					<td><input name="email" class="form-control input-sm"></td>
					<td><input name="username" class="form-control input-sm" required></td>
					<td><input name="password" class="form-control input-sm" type="password" required></td>
					<td><input name="active" class="form-control input-sm" value= "1" type="checkbox"  >
					<input name="role" value="student" type="hidden"></td>
					<td><button type="submit" name="add_user" class="btn btn-primary btn-xs" value="add_user"><span class="glyphicon glyphicon-plus"></span></button></td>
				</tr>
			</tbody>
		</table>
	</form>

	<h3><?= _('Liste des professeurs')?></h3>

		<table class="table table-striped">
			<col width="15%">
			<col width="15%">
			<col width="15%">
			<col width="15%">
			<col width="15%">
			<col width="5%">
			<col width="10%">

			<tbody>
				<?php foreach($users as $user): ?>

					<tr>
						<form action="/admin/users/<?= $this->uri->segment(3)?>/update_user" method="post" style="display:inline;">
							<td><input name="last_name" class="form-control input-sm" value= "<?= $user->last_name ?>"></td>
							<td><input name="name" class="form-control input-sm" value= "<?= $user->first_name ?>"></td>
							<td><input name="email" class="form-control input-sm" value= "<?= $user->email ?>"></td>
							<td><input name="username" class="form-control input-sm" value= "<?= $user->username ?>"></td>
							<td><input name="password" class="form-control input-sm" value= "" type="password"></td>
							<td><input name="active" class="form-control input-sm" value="1" <?= ($user->active ? 'checked' : '') ?> type="checkbox"></td>
							<td>
								<button type="submit" class="btn btn-primary btn-xs" name="id" value="<?= $user->id ?>">
									<span class="glyphicon glyphicon-pencil"></span>
								</button>
						</form>
						<form action="/admin/users/<?= $this->uri->segment(3)?>/delete_user" method="post" style="display:inline;">
								<button type="submit" class="btn btn-danger btn-xs" name="id" value="<?= $user->id ?>">
									<span class="glyphicon glyphicon-trash"></span>
								</button>
							</td>
						</form>
					</tr>

				<?php endforeach?>
			</tbody>
		</table>



</div>
