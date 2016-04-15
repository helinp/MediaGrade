<main class="col-md-10" id="content">
    <?php foreach($grade_table as $class): ?>
    <div class="row">
        <?php foreach($class as $user_projects): ?>
        <div class="col-md-3 thumb">
            <div class="well well-bkg">
                <dl class="rating-list">

                    <dt><?= strtoupper($user_projects['user']->last_name) . " ". substr($user_projects['user']->name, 0, 1) . "." ?></dt>
                    <?php foreach($user_projects['projects'] as $project): ?>
                    <dd>
                        <?php if ($project->is_graded): ?>
                        <span style="color:green" class="glyphicon glyphicon-check"></span>
                        <?php elseif ($project->is_submitted): ?>
                        <span style="color:red" class="glyphicon glyphicon-edit"></span>
                        <?php else: ?>
                        <span style="color:gray" class="glyphicon glyphicon-inbox"></span>
                        <?php endif ?>

                        <a href="/admin/grade/<?= $user_projects['user']->class ?>/<?= $project->project_id ?>/<?= $user_projects['user']->id?>" class="text-muted" >
                            <small>P<?= $project->periode?> - </small>  <?= $project->project_name?>
                        </a>
                    </dd>
                    <?php endforeach ?>
                </dl>
            </div>
        </div>
        <?php endforeach ?>


    </div>
    <?php endforeach ?>

</main>
