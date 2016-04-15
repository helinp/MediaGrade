<main class="col-md-10">
    <div class="col-md-10">
        <h2 class=" text-left" style="margin-bottom:-2em"><?= $class ?> <small> / <?= _('Résultats')?></small></h2>

        <?php if(isset($table_header)):?>
        <table class="table table-hover table-striped" style="margin-top:5em">
            <thead>
                <tr>
                    <th>
                        <small><?= _('Groupes de cpt')?></small>
                    </th>
                    <?php foreach ($table_header as $row): ?>
                    <?php foreach ($row['skills_groups'] as $skill_group): ?>
                    <th class="rotate">
                        <div><span><small style="margin-left:-2.3em;">
                            <?= character_limiter($row['project_name'], 13) ?>
                        </small></span></div>

                        <div><span class="text-muted"><small>
                            <span class="glyphicon glyphicon-arrow-right"></span>
                            <?= substr($skill_group['skills_group'], 0, 10)?>
                        </small></span></div>

                    </th>
                    <?php endforeach ?>
                    <?php endforeach ?>
                    <th class="rotate"><div><span><small><?= _('MOYENNE')?></small></span></div></th>
                </tr>
                <tr>
                    <th>
                        <small><?= _('Maximum') ?></small>
                    </th>

                    <?php foreach ($table_header as $row): ?>
                    <?php foreach ($row['skills_groups'] as $skill_group): ?>
                        <th>
                        <small><?= $skill_group['max_vote']?></small>
                        </th>
                    <?php endforeach ?>
                    <?php endforeach ?>

                    <th>
                        <small>%</small> <!-- Average -->
                    </th>
                </tr>
            </thead>
            <tbody>
                <td></td>
                <?php foreach ($table_body as $student): ?>
                <tr>
                    <td><?= $student['last_name'] . ' ' . $student['name']?></td>
                    <?php foreach ($student['results'] as $row): ?>
                    <td<?php if ($row->user_vote < ($row->max_vote / 2) && is_numeric($row->user_vote)) echo(' class="text-danger" ') ?>><?= custom_round($row->user_vote)?> </td>
                    <?php endforeach ?>
                    <td<?php if ($student['average'] < 50 && is_numeric($student['average'])) echo(' class="text-danger" ') ?>><?= $student['average']?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class = "row">
        <div class="col-md-10">
            <?= LABEL_NO_AVAILABLE_RESULTS ?>
        </div>
    </div>
    <?php endif ?>
</main>
