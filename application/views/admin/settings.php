<div id="content" class="col-xs-10 col-md-10 ">

    <div class="row chapeau">
        <div class="col-xs-12  col-md-12">
            <h1><?= _('Système')?>
            </h1>
        </div>
    </div>

        <form action="/admin/settings/mail_test" method="post">
            <h3><?= LABEL_MAIL_TEST ?></h3>
            <label class="control-label"><?= LABEL_SUBJECT ?></label>
            <div class="form-group">
                <input name="subject" value="<?= LABEL_ITS_A_TEST ?>" required>
            </div>
            <label class="control-label"><?= LABEL_MESSAGE ?></label>
            <div class="form-group">
                <textarea rows="5" type="text" name="body" required><?= LABEL_ITS_A_TEST ?></textarea><br />
                <span class="helper"><small><?= _('Le mail sera envoyé à: ') . $_SESSION['email']?></small></span>
            </div>

            <input type="submit" class="btn btn-primary" name="send_mail_test" value="<?= _("Tester l'envoi de mail") ?>" />
        </form>

        <h3><?= _('Écriture des dossiers') ?></h3>
        <div class="col-xs-6">

            <table class="table">
                <?php $not_writable = FALSE; ?>
                <?php foreach ($folder_perms as $folder => $perm): ?>
                <?php if($perm != '0777' AND $perm != '0775') {$not_writable = TRUE;} ?>
                <tr>
                    <td><?= $folder ?></td>
                    <td><?= $perm ?></td>
                    <td><span class="glyphicon <?= ($not_writable === FALSE ? ($perm === '0775' ? 'glyphicon-ok text-success' : 'glyphicon-warning-sign text-warning') : 'glyphicon-remove text-danger') ?> "<?= $perm ?></td>
                </tr>
                <?php endforeach ?>
            </table>
        </div>
        <?php if($not_writable === TRUE): ?>
            <div class="alert alert-danger" role="alert"><?= _('Remise des projets impossible, veuillez régler les permissions des dossiers sur 0755.') ?></div>
        <?php endif ?>

        <h3><?= _('Espace disque') ?></h3>
        <div class="col-xs-6">

            <table class="table">
                <tr>
                    <td><?= _('Libre')?>:</td>
                    <td><?= $disk_space['free'] ?></td>
                </tr>
                <tr>
                    <td><?= _('Utilisé')?>:</td>
                    <td><?= $disk_space['used'] ?></td>
                </tr>
                    <td><?= _('Total')?>:</td>
                    <td><?= $disk_space['total'] ?></td>
                </tr>
            </table>
</div>

    <script src="/assets/js/tinymce/tinymce.min.js"></script>
    <script>

        tinymce.init({
          selector: 'textarea',
          plugins: [
            'advlist autolink lists link image preview anchor',
            'searchreplace visualblocks code',
            'insertdatetime media table paste code'
          ],
          toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
          content_css: [
            '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
            '//www.tinymce.com/css/codepen.min.css'
          ]
        });

    </script>
