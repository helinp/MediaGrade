<div class="row chapeau chapeau-modal">
		<div class="col-xs-12  col-md-12">
				<h2 class=" text-left"> <?= _('Consignes')?></h2>
		</div>
</div>

<div class="row">
    <div class="col-xs-12  col-md-12">
        <?php if ($instructions_txt): ?>

        <h3><?= _('Consignes brèves')?></h3>
        <h4><?= _('Mise en situation')?></h4>
        <p><?= $instructions_txt['context'] ?></p>

        <h4><?= _('Consignes')?></h4>
        <?= $instructions_txt['instructions'] ?>
        <?php endif ?>
        <?php if ($instructions_pdf): ?>
        <h3><?= _('Consignes téléversées')?></h3>
        <?= makeHtmlObjectForPdf($instructions_pdf) ?>
        <?php endif ?>
        <?php if (!$instructions_txt && !$instructions_pdf) echo _('Aucune consigne n\'a été téléversée')?>
    </div>
</div>
