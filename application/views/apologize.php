        <main class="col-md-12" style="margin:10% 0">
            <div class="row">
                <p class="lead text-danger">
                    <?= _('Désolé...')?>
                </p>
                <p class="text-danger">
                    <?= htmlspecialchars($message) ?>
                </p>

                <a href="javascript:history.go(-1);"><?= LABEL_BACK ?></a>
            </div>
        </main>
