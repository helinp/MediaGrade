<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php require('application/views/templates/header.php'); ?>
	<div id="container">
		<main class="col-md-12">
		    <div class="row">
		        <p class="lead text-danger">
		            <?= LABEL_SORRY ?>
		        </p>
		        <p class="text-danger">
		            <?= $message ?>
		        </p>

		        <a href="javascript:history.go(-1);"><?= LABEL_BACK ?></a>
		    </div>
		</main>
	</div>
<?php require('application/views/templates/footer.php'); ?>
