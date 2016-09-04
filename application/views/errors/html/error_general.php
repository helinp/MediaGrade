<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php require('application/views/templates/header.php'); ?>
	<div id="content" class="col-xs-12 col-md-12 ">
		    <div class="row">
				<div class="col-md-4 col-md-offset-3 text-center">
			        <p class="lead text-danger">
			            <?= _('Désolé!') ?>
			        </p>
			        <p class="text-danger">
			            <?= $message ?>
			        </p>

			        <a href="javascript:history.go(-1);"><?= LABEL_BACK ?></a>
				</div>
		    </div>

	</div>
<?php require('application/views/templates/footer.php'); ?>
