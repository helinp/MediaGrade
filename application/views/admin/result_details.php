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
                <th><?= _('Indicateurs (l\'élève a:)') ?></th>


                <?php foreach(array_values($students)[0] as $student): ?>
                <th class="rotate"><div><span><small><?= $student->last_name . ' ' . substr($student->name, 0, 1) . '.'?></small></span></div></th>
                <?php endforeach ?>
                <th class="rotate"><div><span><small><?= _('Maximum') ?></small></span></div></th>
                <th class="rotate"><div><span><small><?php if(count($results[0]->results) > 1) echo _('Moyenne') ?></small></span></div></th>
            </tr>
        </thead>


        <tbody>
            <?php foreach($results as $row): ?>
            <tr>
                <td><?= $row->skills_group ?></td>
                <td><?= $row->criterion ?></td>
                <td><?= $row->cursor ?></td>

                <?php foreach($row->results as $result): ?>
                <td<?php if ($result < ($row->max_vote / 2) && is_numeric($result)) echo(' class="text-danger dotted_underline" ') ?>><?= $result ?></td>
                <?php endforeach ?>
                <td><strong><?= $row->max_vote ?></strong></td>
                <td>
                  <?php if(count($row->results) > 1):?>
                    <strong><?= (count(array_filter($row->results,'is_numeric')) ? custom_round(array_sum($row->results) / count(array_filter($row->results,'is_numeric'))) : _('NA'))?></strong></td>
                  <?php endif ?>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
