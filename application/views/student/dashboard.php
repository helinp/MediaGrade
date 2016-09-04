<div id="content" class="col-xs-12 col-md-10 ">
    <div class="row chapeau">
        <div class="col-xs-9  col-md-9">
            <h1> <?= _('Tableau de bord') ?></h1>
        </div>
        <div class="col-xs-3  col-md-3">
              <form id="filter" action="" method="get" class="form-inline" style="margin-top:1.5em">
                <label><?= _('Année scolaire') ?>: </label>
                <div class="input-group">
                    <select class="form-control input-sm" name="school_year" onchange="this.form.submit()">
                        <?php foreach($school_years as $row): ?>
                            <?= '<option value="' . $row->school_year . '"' . ( @$_GET['school_year'] === $row->school_year || ( !isset($_GET['school_year']) && $row->school_year === get_school_year() ) ? ' selected' : '') . '>' . $row->school_year . '</option>' . "\n" ?>
                        <?php endforeach?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <?= $content ?>

    <!-- PANELS -->
    <div class="row" style="margin-top:1em">
        <div class="col-lg-5 col-md-6 col-xs-12 ">
            <div class="panel panel-danger">
                <div class="panel-heading text-center" style="background-color:#d9534f;color:white"><?= _('À remettre')?> <span class="badge"><?= count($not_submitted) ?></span></div>
                <div class="panel-body text-left">
                    <table class="table table-striped small">
                        <?php foreach($not_submitted as $row): ?>
                            <tr>
                                <td><?= $row->term ?></td>
                                <td><?= $row->project_name ?></td>
                                <td><?= $row->deadline; ?></td>
                                <td><a data-toggle="modal" data-target="#projectModal" href="/projects/instructions/<?= $row->project_id?>">Consignes</a></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-md-6 col-xs-12 ">
            <div class="panel panel-success">
                <div class="panel-heading text-center"  style="background-color:#5cb85c;color:white"><?= _('Derniers résultats')?> <span class="badge"><?= count($graded) ?></span></div>
                <div class="panel-body text-left">
                    <table class="table table-striped small">
                        <?php foreach($graded as $row): ?>
                            <tr>
                                <td><?= $row->term ?></td>
                                <td><?= $row->project_name ?></a></td>
                                <td<?= ($row->average->total_user < $row->average->total_max / 2 ?  ' class="text-danger dotted_underline" ' : '') ?>><?= $row->average->total_user . ' / ' . $row->average->total_max ?></td>
                                <td><a data-toggle="modal" data-target="#projectModal" href="/projects/results/<?= $row->project_id?>"><?= _('Détails') ?></a></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style = "margin-top:1em">
        <div class="col-lg-10 col-md-12 col-xs-12 ">
            <div class="panel panel-primary">
                <div class="panel-heading text-center" ><?= _('Évolution de mes compétences')?></div>
                <div class="panel-body text-left">

                    <script src="/assets/js/highcharts.js"></script>
                    <script src="/assets/js/exporting.js"></script>

                    <script>
                    $(function () {
                        $('#chart1').highcharts({
                            title: {
                                text: '<?= _('Évolution de mes compétences') ?>',
                                x: -20 //center
                            },
                            subtitle: {
                                text: 'Année scolaire <?= $school_year ?>',
                                x: -20
                            },
                            xAxis: {
                                title: {
                                    text: 'Projets'
                                },
                                categories: [<?= $graph_projects_list ?>]
                            },

                            yAxis: {
                                title: {
                                    text: '<?= _('Pourcentage') ?>'
                                },
                                min: 0, max: 100,
                                plotLines: [{
                                    value: 0,
                                    width: 1,
                                    color: '#808080'
                                }]
                            },
                            plotOptions: {
                                series: {
                                    connectNulls: false
                                }
                            },
                            tooltip: {
                                valueSuffix: '%'
                            },
                            legend: {
                                layout: 'vertical',
                                align: 'right',
                                verticalAlign: 'middle',
                                borderWidth: 0
                            },
                            series: [{
                                <?= $graph_results ?>
                            }]
                        });
                    });
                    </script>
                    <div class="row">
                        <div id="chart1" style="margin-top:1em;" class="col-md-12"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal sudo"  id="projectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="padding:1em;">
        </div>
    </div>
</div>

</script>

<!-- Updates modal-->
<script>
$(document).on('hidden.bs.modal', function (e) {
    $(e.target).removeData('bs.modal');
});
</script>
