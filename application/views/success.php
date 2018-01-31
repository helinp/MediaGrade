<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php require('application/views/templates/header.php'); ?>
<?php require('application/views/templates/aside.php'); ?>
<div id="content" class="col-xs-10 col-md-10 text-center">
    <h2 class="lead text-success">
        <?= _('Ok!') ?>
    </h2>
    <p class="text-success">
        <?= htmlspecialchars($message) ?>
    </p>

    <a href="javascript:history.go(-1);"><?= LABEL_BACK ?></a>

</div>
<?php require('application/views/templates/footer.php'); ?>
