<div id="content" class="col-xs-10 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau hidden-print">
		<div class="col-xs-6  col-md-6">
		</div>
	</div>

<?php echo form_open_multipart('/admin/import/upload');?>
		<div class="form-group">
			<h3><?= _('Sélection des fichiers') ?></h3>
	<div class="alert alert-info" style="margin-top:1em;" role="alert"><?= _('Les fichiers importables devront être au format PDF ou image. <br/> Seules les évaluation avec un QR code (format: nom;prénom;aaaa-mm-jj;Nom projet;classe)<br /> placé en haut à droite peuvent être traitées automatiquement.') ?></div>
			<input type="file" name="userfile" />
			<?php if (isset($error)): ?>
				<span><?= strip_tags($error) ?></span>
			<?php endif ?>


		</button>

	</div>

	<div class="form-group">
		<input type="submit" class="btn btn-primary" value="Traiter le(s) fichier(s) PDF" name="action">
	</div>
</form>
Div erreurs / rapport
</div>
