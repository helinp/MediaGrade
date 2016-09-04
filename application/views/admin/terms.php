<div id="content" class="col-xs-12 col-md-10 ">

    <div class="row chapeau">
        <div class="col-xs-12  col-md-12">
            <h1><?= _('Périodes')?></h1>
        </div>
    </div>
    <div class="alert alert-danger" style="margin-top:1em;" role="alert"><?= LABEL_ADMIN_DANGER ?></div>
    <form action="/admin/terms/add_term" method="post">
        <h3><?=_('Ajouter une période')?></h3>
        <div class="form-group ">
            <div class="input-group col-md-4">
                <input name="term" type="text" placeholder="<?=_('Nom du nouveau groupe')?>" class="form-control input-md" required>
                    <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-plus"></span></button>
                </span>
            </div>
        </div>
    </form>
    <form action="/admin/terms/del_term" method="post">
    <h3><?=_('Périodes actuelles')?></h3>
    <div class="form-group">
        <div class="input-group col-md-4">
            <select  name="term" class="form-control" size="5">
            <?php foreach ($terms as $term): ?>
                <option value="<?= $term ?>"><?= $term ?></option>
            <?php endforeach; ?>
            </select>
        </div>
    </div>
    <form action="/admin/terms/add_term" method="post">
        <h3><?=_('Supprimer une période')?></h3>
        <p class="text-danger"><?=_('Attention, supprimer une période déjà utilisée entraînera des erreurs dans la base de données.')?></p>
        <div class="form-group ">
            <div class="input-group col-md-4">
                <input name="term" type="text" placeholder="<?=_('Nom de la période à supprimer')?>" class="form-control input-md" required>
                    <span class="input-group-btn">
                    <button class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-trash"></span></button>
                </span>
            </div>
        </div>
    </form>

</div>
