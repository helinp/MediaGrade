

<main class="col-md-10" id="content">
    <h2 class=" text-left"> <?= _('Système')?></h2>
    <hr style="margin-top:0;" />

        <form action="/admin/settings/mail_test" method="post">
            <h3><?= LABEL_MAIL_TEST ?></h3>
            <label class="control-label"><?= LABEL_SUBJECT ?></label>
            <div class="form-group">
                <input name="subject" value="<?= LABEL_ITS_A_TEST ?>">
            </div>
            <label class="control-label"><?= LABEL_MESSAGE ?></label>
            <div class="form-group">
                <textarea rows="5" type="text" name="body"><?= LABEL_ITS_A_TEST ?></textarea><br />
                <span class="helper"><small><?= _('Le mail sera envoyé à: ') . $_SESSION['email']?></small></span>
            </div>

            <input type="submit" class="btn btn-primary" name="send_mail_test" value="<?= _("Tester l'envoi de mail") ?>" />
        </form>
        <h3><?= _("Message d'accueil") ?></h3>
        <form action="/admin/settings/welcome_message" method="post" id="form">
            <textarea name="welcome_message" class="form-control" rows="12"><?= $welcome_message ?></textarea>
            <p class="help-block">HTML tags: <code style="color:gray;"><?= htmlspecialchars(ALLOWED_HTML_TAGS) ?></code> <br />Custom: <code style="color:gray;">%user_name% %user_lastname%</code></p>
            <button type="submit" class="btn btn-primary">
            <?= _('Valider') ?></button>
        </form>

</main>

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
