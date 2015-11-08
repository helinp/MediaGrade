<main class="col-md-12">
    <div class="row">
        <p class="lead text-danger">
            <?=$lang['SORRY']?>
        </p>
        <p class="text-danger">
            <?= htmlspecialchars($message) ?>
        </p>

        <a href="javascript:history.go(-1);"><?=$lang['BACK']?></a>
    </div>
</main>
