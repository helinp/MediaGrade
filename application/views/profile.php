<div id="content" class="col-xs-10 col-md-10 ">
	<div class="row chapeau">
	</div>
	<form action="profile/update" method="post" role="form">
		<table class="table profile">
			<colgroup>
				<col style="width:20%">
				<col style="width:70%">
				<col style="width:10%">

			</colgroup>
			<td colspan="3">
				<h3><?= _('Mes informations') ?></h3>
			</td>
			<tr>
				<td><b><?= LABEL_LAST_NAME ?>:</b> </td>
				<td>
					<?= $user_data->last_name?></td>
				<td></td>
			</tr>
			<tr>
				<td><b><?= LABEL_NAME ?></b> </td>
				<td>
					<?=$user_data->first_name?></td>
				<td></td>
			</tr>
			<tr>
				<td><b><?= LABEL_CLASS ?>:</b> </td>
				<td>
					<?= ( $this->Users_model->isAdmin() ? LABEL_TEACHER : $user_data->class) ?></td>
				<td></td>
			</tr>
			<tr>
				<td><b><?= LABEL_EMAIL ?>:</b> </td>
				<td>
					<input type="text" name="email" placeholder=" <?= LABEL_MY_MAIL ?>" value="<?= $user_data->email?>" />
				</td>
				<td></td>
			</tr>
			<tr class="top-line">
				<td>
					<button type="submit" name="change_email" value="1" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span>
						<?= LABEL_MODIFY ?>
					</button>
				</td>
				<td></td>
				<td></td>
			</tr>
	</form>
	<form action="profile/update" method="post" role="form">
		<tr>
			<td colspan="3">
				<h3><?= LABEL_CHANGE_PASS ?></h3>
			</td>
		</tr>
		<tr>
			<td><b><?= LABEL_ACTUAL_PASS ?>:</b> </td>
			<td>
				<input type="password" name="current_password" placeholder=" <?= LABEL_PASSWORD ?>" />
			</td>
			<td></td>
		</tr>
		<tr>
			<td><b><?= LABEL_NEW_PASS ?>:</b> </td>
			<td colspan="2">
				<input id="new_password" type="password" name="new_password" placeholder=" <?= LABEL_PASSWORD ?>" />
				<span class="small" id="passstrength"></span>
			</td>
		</tr>
		<tr>
			<td><b><?= LABEL_CONFIRM_PASS ?>:</b> </td>
			<td>
				<input type="password" name="new_password_confirmation" placeholder=" Confirmation" />
			</td>
			<td></td>
		</tr>
		<tr class="top-line">
			<td>
				<button type="submit" name="change_password" value="1" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span>
					<?= LABEL_MODIFY ?>
				</button>
			</td>
			<td></td>
			<td></td>
		</tr>
	</form>
	<form action="/profile/upload" method="post" role="form" enctype="multipart/form-data">
		<td colspan="3">
			<h3><?= _('Mon avatar') ?> <small>(<?= _('public') ?>)</small></h3>
		</td>
		</tr>
		<tr>
			<td colspan="3">L'image doit être au format <em>carré</em>, de 300px minimum, au format JPG ou PNG et peser moins de 5Mo.</td>

		</tr>
		<tr>
			<td colspan="3">
				<input type="file" name="avatar_file" accept="image/*" />
				<?php if (isset($error)): ?>
				<span><?= strip_tags($error) ?></span>
				<?php endif ?>
			</td>

		</tr>
		<tr class="top-line">
			<td>
				<button type="submit" name="upload_avatar" value="1" class="btn btn-primary btn-xs">
					<span class="glyphicon glyphicon-save"></span>
					<?=_ ( 'Téléverser')?>
				</button>
			</td>
			<td></td>
			<td></td>
		</tr>
	</form>

	<form action="/profile/update" method="post" role="form" enctype="multipart/form-data">
		<tr>
			<td colspan="3">
				<h3><?= _('Ma devise') ?> <small>(<?= _('public') ?>)</small></h3>
			</td>
		</tr>
		<tr class="top-line">
			<td><b><?= _('Ma devise') ?>:</b> </td>
			<td>
				<input type="text" name="motto" placeholder="<?= ($user_data->motto ? $user_data->motto : _('Think Different')) ?>" maxlength="45" style='width:30em' width="45" />
			</td>
			<td></td>
		</tr>
		<tr class="top-line">
			<td>
				<button type="submit" name="upload_avatar" value="1" class="btn btn-primary btn-xs">
					<span class="glyphicon glyphicon-pencil"></span>
					<?=_ ( 'Modifier')?>
				</button>
			</td>
			<td></td>
			<td></td>
		</tr>
		</table>
	</form>

	<form action="profile/update" method="post" role="form">
		<tr>
			<td colspan="3">
				<h3><?= _('Notifications par courriel') ?></h3>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<p>
					<?=_ ( 'Je souhaite:')?>
				</p>
				<div class="checkbox">
					<label>
						<?php if($this->session->role !== 'admin'): ?>
						<?=@ form_checkbox( 'submit_confirmation', 'true', $preferences_data[ 'submit_confirmation']) . _( 'recevoir un courriel de confirmation lorsque je remets une réalisation.')?>
							<?php else: ?>
							<?=@ form_checkbox( 'submit_confirmation', 'true', $preferences_data[ 'submit_confirmation']) . _( 'recevoir un courriel de confirmation lorsque un élève remet une réalisation.')?>
								<?php endif ?>
					</label>
				</div>
				<?php if($this->session->role !== 'admin'): ?>
				<div class="checkbox">
					<label>
						<?=@ form_checkbox( 'assessment_confirmation', 'true', $preferences_data[ 'assessment_confirmation']) . _( 'recevoir un courriel lorsque l\'une de mes réalisations est évaluée. ') ?>
                      </label>
                   </div>
                   <?php endif ?>
                </td>

             </tr>
             <tr class="top-line">
                <td>
                    <button type="submit" name="change_mail_preferences" value="1" class="btn btn-primary btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> <?= LABEL_MODIFY ?></button>
                </td>
				<td></td>
				<td></td>
             </tr>
          </table>
       </form>
    </div> <!-- CONTENT -->

    <script>

        $('#new_password ').keyup(function(e) {
             var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
             var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
             var enoughRegex = new RegExp("(?=.{8,}).*", "g");

             if (false == enoughRegex.test($(this).val())) {
                     $('#passstrength ').className = 'text-danger ';
                     $('#passstrength ').html('8 caractères minimum. ');
             } else if (strongRegex.test($(this).val())) {
                     $('#passstrength ').className = 'text-success ';
                     $('#passstrength ').html('<?=_ ( 'Mot de passe fort!')?>'); } else if (mediumRegex.test($(this).val())) { $('#passstrength').className = 'text-warning'; $('#passstrength').html('
							<?=_ ( 'Moyen mais accepté')?>'); } else { $('#passstrength').html("
								<?=_ ( 'Le mot de passe doit être composé d\'au moins une majuscule, une minuscule et d\'un chiffre.')?>"); } return true; });
									</script>
