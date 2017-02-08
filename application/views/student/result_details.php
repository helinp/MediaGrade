    <div class="row chapeau chapeau-modal">
        <div class="col-xs-12  col-md-12">
            <h2> <?= _('Résultats détaillés') ?> <small>/ <?= $project_name?></small></h2>
        </div>
    </div>
    <table class="table table-hover table-striped" style="margin-top:5em">
        <thead>
            <tr>
                <th><?= _('Compétences') ?></th>
                <th><?= _('Critères') ?></th>
                <th><?= _('Tu as: ') ?></th>
                <th class="rotate"><div><span><small><?= _('Résultat') ?></small></span></div></th>
                <th class="rotate"><div><span><small><?= _('Maximum') ?></small></span></div></th>
            </tr>
        </thead>


        <tbody>
            <?php foreach($results as $row): ?>
            <tr>
                <td><?= $row->skills_group ?></td>
                <td><?= $row->criterion ?></td>
                <td><?= $row->cursor ?></td>
                <td<?php if ($row->user_vote < ($row->max_vote / 2) && is_numeric($row->user_vote)) echo(' class="text-danger dotted_underline" ') ?>><?= $row->user_vote ?></td>
                <td><strong><?= $row->max_vote ?></strong></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
