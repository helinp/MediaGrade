<div id="content" class="col-xs-12 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau">
	</div>
	<div class="col-xs-6">
		<h3><?=_('Télécharger la base de données')?></h3>
		<div class="form-group ">
			<div class="input-group col-md-4">
				<a class="btn btn-primary" href="/admin/maintenance/backup_db/download" role="button">Télécharger</a>
			</div>
		</div>
		<h3><?=_('Mettre à jour Mediagrade')?></h3>
		<div class="form-group ">
			<div class="input-group col-md-4">

				<p><a class="btn btn-primary" href="/admin/maintenance/upgrade/" role="button">Contrôler les mises à jour</a></p>
				<p><span id="is_last_version">&nbsp;</span></p>
				<a id="upgrade_btn" class="btn btn-danger" href="/admin/maintenance/upgrade/" role="button">Installer la mise à jour</a>
			</div>
		</div>
	</div>
</div>
<script>/*
$.get(
"/admin/maintenance/check_update/",
function(data) {
if(data) {
$("span#is_last_version").text('Une mise à jour est disponible!');
} else {
$("#upgrade_btn").hide();
$("span#is_last_version").text('Vous avez la dernière version');
}
}
);**/
</script>
