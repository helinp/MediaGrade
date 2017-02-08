<div id="content" class="col-xs-12 col-md-10 ">

    <div class="row chapeau">
        <div class="col-xs-12  col-md-12">
            <h1><?= _('Message d\'accueil')?></h1>
        </div>
    </div>
    <h3><?= _("Message d'accueil") ?></h3>
        <form action="/admin/welcome_message/update" method="post" id="form">
            <textarea name="welcome_message" class="form-control" rows="12"><?= $welcome_message ?></textarea>
            <p class="help-block">HTML tags: <code style="color:gray;"><?= htmlspecialchars(ALLOWED_HTML_TAGS) ?></code> <br />Custom: <code style="color:gray;">%user_name% %user_lastname%</code></p>
            <button type="submit" class="btn btn-primary">
            <?= _('Valider') ?></button>
        </form>