        <div id="content" class="col-xs-12 col-md-10 ">
            <div class="row">
                <p class="lead text-danger">
                    <?= _('Désolé...')?>
                </p>
                <p class="text-danger">
                    <?= htmlspecialchars($message) ?>
                </p>

                <a href="javascript:history.go(-1);"><?= LABEL_BACK ?></a>
            </div>
        </div>
