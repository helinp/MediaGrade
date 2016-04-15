<main class="col-md-12 text-left" style="">
    <h2 class=" text-left"> <?= _('Dashboard')?></h2>
    <hr style="margin-top:0;" />
    <div class="row">
        <div class="col-lg-4 col-md-4 col-xs-12 ">
            <div class="panel panel-danger">
                <div class="panel-heading text-center"  style="background-color:#d9534f;color:whgite"><?= _('À corriger')?><span class="badge">2</span></div>
                <div class="panel-body text-left">
                    <table class="table table-striped small">
                        <?php foreach($not_graded_projects as $row): ?>
                        <tr>
                            <td><?= 'P' . $row->periode ?></td>
                            <td><?= $row->last_name . ' ' . $row->name ?></td>
                            <td><?= $row->project_name ?></td>

                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-12 ">
            <div class="panel panel-success">
                <div class="panel-heading text-center" style="background-color:#5cb85c;color:white"><?= _('Projets actifs')?></div>
                <div class="panel-body text-left">
                    <table class="table table-striped small">
                        <?php foreach($active_projects as $row): ?>
                        <tr>
                            <td><?= 'P' . $row->periode ?></td>
                            <td><?= $row->project_name ?></td>
                            <td><?= $row->deadline ?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-md-5 col-xs-12 ">
            <div class="panel panel-primary ">
                <div class="panel-heading text-center"><?= _('Dernières remises')?></div>
                <div class="panel-body text-left">
                    <table class="table table-striped small">
                        <?php foreach($last_submitted as $row): ?>
                        <tr>
                            <td><?= $row->last_name . ' ' . $row->name ?></td>
                            <td><?= $row->project_name ?></td>
                            <td><?= $row->time ?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- row -->
    <div class="row">
        <div class="col-lg-4 col-md-4 col-xs-12 ">
            <div class="panel panel-warning">
                <div class="panel-heading text-center" style="background-color:#f0ad4e;color:white"><?= _('Derniers films recommandés')?></div>
                <div class="panel-body text-left">
                    <table class="table table-striped small">
                        <?php foreach($last_movies as $row): ?>
                        <tr>
                            <td><?= $row->last_name . ' ' . $row->name ?></td>
                            <td><?= $row->title ?></td>
                            <td><?= $row->vote . '/4' ?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-xs-12 ">
            <div class="panel panel-success">
                <div class="panel-heading text-center"><?= _('Espace disque')?></div>
                <div class="panel-body text-left">
                      <p><?= LABEL_FREE ?>: <em><?= $disk_space['free'] ?></em>.
                          <br /><?= LABEL_USED ?>: <em><?= $disk_space['used'] ?></em>.</p>

                      <div class="progress">
                          <div class="progress-bar" role="progressbar" aria-valuenow="<?= $disk_space['per_used'] ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $disk_space['per_used'] ?>%">
                            <?= $disk_space['per_used'] ?> %
                          </div>
                      </div>

                </div>
            </div>
        </div>
    </div>
    <!-- row -->
</main>
