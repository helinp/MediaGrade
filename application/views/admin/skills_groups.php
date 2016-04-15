<main class="col-md-8 text-left" id="content">
    <h2 class=" text-left"> <?= _('Groupes de compétences')?></h2>
    <hr style="margin-top:0;" />
    <form action="/admin/skills_groups/add_skills_group" method="post">
        <h3><?=_('Ajouter un groupe de compétences')?></h3>
        <div class="form-group ">
            <div class="input-group col-md-4">
                <input name="skills_group" type="text" placeholder="<?=_('Nom du nouveau groupe')?>" class="form-control input-md" required>
                    <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-plus"></span></button>
                </span>
            </div>
        </div>
    </form>
    <form action="/admin/skills_groups/del_skills_group" method="post">
    <h3><?=_('Groupes actuels')?></h3>
    <div class="form-group">
        <div class="input-group col-md-4">
            <select  name="skills_groups" class="form-control" size="5">
            <?php foreach ($skills_groups as $skills_group): ?>
                <option value="<?= $skills_group->id ?>"><?= $skills_group->name ?></option>
            <?php endforeach; ?>
            </select>
        </div>
    </div>
    <form action="/admin/skills_groups/add_skills_group" method="post">
        <h3><?=_('Supprimer un groupe de compétences')?></h3>
        <div class="form-group ">
            <div class="input-group col-md-4">
                <input name="skills_group" type="text" placeholder="<?=_('Nom du groupe à supprimer')?>" class="form-control input-md" required>
                    <span class="input-group-btn">
                    <button class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-trash"></span></button>
                </span>
            </div>
        </div>
    </form>

<!-- WORK IN PROGRESS

    <h3><?=_('Compétences reprises dans ce groupe')?></h3>

    <div class="form-group">
        <div class="input-group col-md-4">
            <select id="selectmultiple" name="selectmultiple" class="form-control" multiple="multiple">
              <option value="1">Option one</option>
              <option value="2">Option two</option>
            </select>
             <span class="help-block"><small><?=_('Sélectionnez des compétences à insérer dans le groupe.')?></small></span>
        </div>
    </div>
    <h3><?=_("Compétences ne faisant partie d'aucun groupe")?></h3>

    <div class="form-group">
        <div class="input-group col-md-4">
            <select id="selectmultiple" name="selectmultiple" class="form-control" multiple="multiple">
              <option value="1">Option one</option>
              <option value="2">Option two</option>
            </select>
             <span class="help-block"><small><?=_('À titre indicatif')?></small></span>
        </div>
    </div>-->
</main>
