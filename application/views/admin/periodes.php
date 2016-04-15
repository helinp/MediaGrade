<main class="col-md-8 text-left" id="content">
    <h2 class=" text-left"> <?= _('Périodes')?></h2>
    <hr style="margin-top:0;" />
    <form action="/admin/periodes/add_periode" method="post">
        <h3><?=_('Ajouter une période')?></h3>
        <div class="form-group ">
            <div class="input-group col-md-4">
                <input name="periode" type="text" placeholder="<?=_('Nom du nouveau groupe')?>" class="form-control input-md" required>
                    <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-plus"></span></button>
                </span>
            </div>
        </div>
    </form>
    <form action="/admin/periodes/del_periode" method="post">
    <h3><?=_('Périodes actuelles')?></h3>
    <div class="form-group">
        <div class="input-group col-md-4">
            <select  name="periode" class="form-control" size="5">
            <?php foreach ($periodes as $periode): ?>
                <option value="<?= $periode ?>"><?= $periode ?></option>
            <?php endforeach; ?>
            </select>
        </div>
    </div>
    <form action="/admin/periodes/add_periode" method="post">
        <h3><?=_('Supprimer une période')?></h3>
        <p class="text-danger"><?=_('Attention, supprimer une période déjà utilisée entraînera des erreurs dans la base de données.')?></p>
        <div class="form-group ">
            <div class="input-group col-md-4">
                <input name="periode" type="text" placeholder="<?=_('Nom de la période à supprimer')?>" class="form-control input-md" required>
                    <span class="input-group-btn">
                    <button class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-trash"></span></button>
                </span>
            </div>
        </div>
    </form>

</main>
