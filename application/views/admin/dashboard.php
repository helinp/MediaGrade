<div id="content" class="col-xs-12 col-md-10 ">
    <div class="row chapeau">
        <div class="col-xs-7  col-md-7">
            <h1> <?= _('Tableau de bord') ?>
            </h1>

        </div>
        <div class="col-xs-2  col-md-2">
            <form id="filter" action="" method="get" class="form-inline" style="margin-top:1.5em">
                <label><?= _('Classe: ') ?></label>
                <div class="input-group">
                    <select class="form-control input-sm" name="classe" onchange="this.form.submit()">
                            <option value=""><?= _('Toutes')?></option>
                    <?php foreach($classes as $classe): ?>
                        <?= '<option value="' . $classe . '"' . (@$_GET['classe'] === $classe ? 'selected' : '') . '>' . $classe . '</option>' . "\n" ?>
                    <?php endforeach?>
                    </select>
                </div>
        </div>
        <div class="col-xs-3  col-md-3">
              <div class="form-inline" style="margin-top:1.5em">
                <label><?= _('Année scolaire') ?>: </label>
                <div class="input-group">
                    <select class="form-control input-sm" name="school_year" onchange="this.form.submit()">
                        <option value=""<?= (@empty($_GET['school_year']) ? ' selected' : '') ?>><?= _('Toutes')?></option>
                        <?php foreach($school_years as $school_year): ?>
                            <?= '<option value="' . $school_year->school_year . '"' . (@$_GET['school_year'] === $school_year->school_year ? ' selected' : '') . '>' . $school_year->school_year . '</option>' . "\n" ?>
                        <?php endforeach?>
                    </select>
                </div>
            </div>
            </form>
        </div>
    </div>
    <div class="row" style = "margin-top:1em">
        <div class="col-lg-4 col-md-4 col-xs-12 ">
            <div class="panel panel-danger">
                <div class="panel-heading text-center"  style="background-color:#d9534f;color:white"><?= _('À corriger')?> <span class="badge"><?= count($not_graded_projects) ?></span>
                </div>
                <div class="panel-body text-left">
                    <?php if($not_graded_projects): ?>
                    <table class="table table-striped small">
                        <?php foreach($not_graded_projects as $row): ?>
                        <tr>
                            <td><?= $row->class ?></td>
                            <td><?= $row->term ?></td>
                            <td><a data-toggle="modal" data-target="#projectModal" href="/admin/grade/<?= $row->class?>/<?= $row->project_id?>/<?= $row->user_id?>"><?= $row->last_name . ' ' . $row->name ?></a></td>
                            <td><?= $row->project_name ?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                    <?php else: ?>
                        <p style="text-center"><?= _('Rien à corriger :)')?></p>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-12 ">
            <div class="panel panel-success">
                <div class="panel-heading text-center" style="background-color:#5cb85c;color:white"><?= _('Projets en cours')?>  <span class="badge"><?= count($active_projects) ?></span></div>
                <div class="panel-body text-left">
                <?php if($active_projects): ?>
                    <table class="table table-striped small">
                        <?php foreach($active_projects as $row): ?>
                        <tr>
                            <td><?= $row->class ?></td>
                            <td><?= $row->term ?></td>
                            <td><?= $row->project_name ?></td>
                            <td><?= $row->deadline ?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                <?php else: ?>
                    <p style="text-center"><?= _('Aucun project actif')?></p>
                <?php endif ?>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-md-5 col-xs-12 ">
            <div class="panel panel-primary ">
                <div class="panel-heading text-center"><?= _('Dernières remises')?>
                </div>
                <div class="panel-body text-left">
                <?php if($last_submitted): ?>
                    <table class="table table-striped small">
                        <?php foreach($last_submitted as $row): ?>
                        <tr>
                            <td><?= $row->class ?></td>
                            <td><?= $row->last_name . ' ' . $row->name ?></td>
                            <td><?= $row->project_name ?></td>
                            <td><?= $row->time ?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                <?php else: ?>
                    <p style="text-center"><?= _('Aucune remise cette année')?></p>
                <?php endif ?>
                </div>
            </div>
        </div>
    </div>
    <!-- row -->
    <div class="row">



        <div class="col-lg-5 col-md- col-xs-12 ">
            <div class="panel panel-success">
                <div class="panel-heading text-center" style="background-color:#5cb85c;color:white"><?= _('Courbe de Gauss (toutes compétences)')?>  </div>
                <div class="panel-body text-left">
                    <script src="../assets/js/highcharts.js"></script>
                    <script src="../assets/js/exporting.js"></script>

                    <div id="gauss" style="margin: 0 auto"></div>
                    <script>

                                        $(function () {
                                                        $(function () {
                                                                $('#gauss').highcharts({
                                                                    chart: {
                                                                        type: 'spline',
                                                                    },
                                                                    title: {
                                                                        text: '',
                                                                        x: -20 //center
                                                                    },
                                                                    subtitle: {
                                                                        text: '',
                                                                        x: -20
                                                                    },
                                                                     xAxis: {
                                                                         title: {
                                                                            text: '<?= _('Pourcentage') ?>'
                                                                        },

                                                                    },

                                                                    yAxis: {
                                                                        title: {
                                                                            text: '<?= _('Fréquence') ?>'
                                                                        },
                                                                        plotLines: [{
                                                                            value: 0,
                                                                            width: 1,
                                                                            color: '#808080'
                                                                        }]
                                                                    },
                                                                    legend: {
                                                                        enabled: false
                                                                    },
                                                                    plotOptions: {
                                                                       series: {
                                                                           connectNulls: true
                                                                       },
                                                                       spline: {
                                                                            marker: {
                                                                                enable: false
                                                                            }
                                                                        }
                                                                    },

                                                                    series: [{
                                                                       data: [<?= implode(', ', $gauss) ?>]
                                                                    }]
                                                                });
                                                            });
                                        });


                    </script>





                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md- col-xs-12 ">
            <div class="panel panel-success">
                <div class="panel-heading text-center" style="background-color:#5cb85c;color:white"><?= _('Répartition des compétences travaillées')?>  </div>
                <div class="panel-body text-left">

                    <div id="skills_usage" style="margin: 0 auto"></div>
                    <script>
                    $(function () {
                        $('#skills_usage').highcharts({
                            chart: {
                                plotBackgroundColor: null,
                                plotBorderWidth: null,
                                plotShadow: false,
                                type: 'column'
                            },
                            title: {
                                text: ''
                            },
                            xAxis: {
                                categories: ['<?= implode("', '", array_keys($skills_usage)) ?>'],
                                crosshair: true
                            },
                            yAxis: {
                                min: 0,
                                title: {
                                    text: '<?= _('Occurences') ?>'
                                }
                            },
                            tooltip: {
                                pointFormat: '<?= _('Travaillée') ?>: <b>{point.y} <?= _('fois') ?></b>'
                            },
                            plotOptions: {

                            },
                            series: [{
                                name: '<?= _('Nombre de fois travaillée') ?> ',
                                colorByPoint: true,
                                data: [<?= implode(', ', $skills_usage) ?>]
                                }]
                        });
                    });

                    </script>





                </div>
            </div>
        </div>
    </div> <!-- row -->
    <div class="row">
        <div class="col-lg-3 col-md-3 col-xs-12 ">
            <div class="panel panel-success">
                <div class="panel-heading text-center"><?= _('Utilisation de l\'espace disque serveur')?></div>
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


    </div>   <!-- row -->
</div>

<!-- Modal -->
<div class="modal sudo"  id="projectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="padding:1em;">
        </div>
    </div>
</div>

<!-- Updates modal-->
<script>
$(document).on('hidden.bs.modal', function (e) {
    $(e.target).removeData('bs.modal');
});
</script>
