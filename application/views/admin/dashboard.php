<div id="content" class="col-xs-12 col-md-10 ">
    <div class="row chapeau">
        <div class="col-xs-6  col-md-6">
            <h1> <?= _('Tableau de bord') ?></h1>
        </div>
        <div class="col-xs-6  col-md-6">
            <form id="filter" action="" method="get" class="form-inline" style="margin-top:1.5em">
                <label><?= _('Classe: ') ?></label>
                <select class="form-control input-sm" name="classe" onchange="this.form.submit()">
                    <option value=""><?= _('Toutes')?></option>
                    <?php foreach($classes as $classe): ?>
                        <?= '<option value="' . $classe . '"' . (@$_GET['classe'] === $classe ? 'selected' : '') . '>' . $classe . '</option>' . "\n" ?>
                    <?php endforeach?>
                </select>
                <label><?= _('Année scolaire') ?>: </label>
                <select class="form-control input-sm" name="school_year" onchange="this.form.submit()">
                    <option value=""<?= (@empty($_GET['school_year']) ? ' selected' : '') ?>><?= _('Toutes')?></option>
                    <?php foreach($school_years as $school_year): ?>
                        <?= '<option value="' . $school_year->school_year . '"' . (@$_GET['school_year'] === $school_year->school_year ? ' selected' : '') . '>' . $school_year->school_year . '</option>' . "\n" ?>
                    <?php endforeach?>
                </select>
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
                            <td><?= $row->last_name . ' ' . $row->name ?></td>
                            <td><?= $row->project_name ?></td>
							<td><a title="Corriger"  data-toggle="modal" data-target="#projectModal" href="/admin/grade/<?= $row->class?>/<?= $row->project_id?>/<?= $row->user_id?>"><span class="glyphicon glyphicon-pencil"> </span></a></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                    <?php else: ?>
                        <p style="text-center"><?= _('Rien à corriger :)')?></p>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-xs-12 ">
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
                            <td><?= date_format(date_create($row->deadline),"d/m/Y"); ?></td>
							<td><a title="Modifier le projet" data-toggle="modal" data-target="#projectModal" href="/admin/project_management/<?= $row->project_id ?>"><span class="glyphicon glyphicon-file"> </span></a></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                <?php else: ?>
                    <p style="text-center"><?= _('Aucun project actif')?></p>
                <?php endif ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-xs-12 ">
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



        <div class="col-lg-6 col-md-6 col-xs-12 ">
            <div class="panel panel-primary">
                <div class="panel-heading text-center"><?= _('Courbes de Gauss')?>  </div>
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
                                                                    },
                                                                    legend: {
                                                                        enabled: true
                                                                    },

                                                                    credits: {
                                                                        enabled: false
                                                                    },
                                                                    plotOptions: {
                                                                        areaspline: {
                                                                            fillOpacity: 0.5,
                                                                            marker: {
                                                                                enabled: false
                                                                            }
                                                                        },
                                                                        spline: {
                                                                            marker: {
                                                                                enabled: false
                                                                            }
                                                                        }
                                                                    },

                                                                    series: [
                                                                    <?php foreach ($gauss as $gaus): ?>
                                                                    {
                                                                       name: '<?= $gaus['skills_group'] ?>',
                                                                       data: [<?= implode(', ', $gaus['percentage']) ?>]
                                                                    },
                                                                    <?php endforeach ?>
                                                                    {
                                                                       name: '<?= _('Moyenne') ?>',
                                                                       data: [<?= implode(', ', $gauss_overall['percentage']) ?>],
                                                                        type: 'areaspline',
                                                                        lineWidth: 0,
                                                                        color: Highcharts.getOptions().colors[0],
                                                                        fillOpacity: 0.3,
                                                                        zIndex: 0
                                                                    }
                                                                    ]
                                                                });
                                                            });
                                        });
                    </script>
                </div>
            </div>
        </div>
		<div class="col-lg-3 col-md-3 col-xs-12 ">
			<div class="panel panel-success">
				<div class="panel-heading text-center"  style="background-color:#5cb85c;color:white"><?= _('Meilleures moyennes')?></div>
				<div class="panel-body text-left">
					<table class="table table-striped small">
						<?php foreach($ranking_top as $row): ?>
							<tr>
								<td><?= $row->class ?></td>
								<td><a data-toggle="modal" data-target="#projectModal" href="/admin/student_details?modal=true&school_year=<?= $_GET['school_year'] ?>&class=<?= $row->class ?>&student=<?= $row->user_id?>"><?= $row->name . ' ' . $row->last_name ?></a></td>
								<td<?= ($row->average < 50 ? ' class="text-danger" ' : '')?><?= ($row->average < 50 ? ' class="text-danger" ' : '')?>><?= $row->average ?> %</td>
							</tr>
						<?php endforeach ?>
					</table>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-xs-12 ">
			<div class="panel panel-success">
				<div class="panel-heading text-center"  style="background-color:#d9534f;color:white"><?= _('Moins bonnes moyennes')?></div>
				<div class="panel-body text-left">
					<table class="table table-striped small">
						<?php foreach($ranking_bottom as $row): ?>
							<tr>
								<td><?= $row->class ?></td>
								<td><a data-toggle="modal" data-target="#projectModal" href="/admin/student_details?modal=true&school_year=<?= $_GET['school_year'] ?>&class=<?= $row->class ?>&student=<?= $row->user_id?>"><?= $row->name . ' ' . $row->last_name ?></a></td>
								<td<?= ($row->average < 50 ? ' class="text-danger" ' : '')?>><?= $row->average ?> %</td>
							</tr>
						<?php endforeach ?>
					</table>
				</div>
			</div>
		</div>
    </div> <!-- row -->
    <div class="row">
		<div class="col-lg-6 col-md-6 col-xs-12 ">
            <div class="panel panel-primary">
                <div class="panel-heading text-center"><?= _('Répartition des notions travaillées')?>  </div>
                <div class="panel-body text-left">

                    <div id="material-pie" style="margin: 0 auto"></div>
                    <script>
					$(function () {
					    Highcharts.chart('material-pie', {
					        chart: {
					            plotBackgroundColor: null,
					            plotBorderWidth: null,
					            plotShadow: false,
					            type: 'pie'
					        },
					        title: {
					            text: ''
					        },
					        tooltip: {
					            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					        },
					        plotOptions: {
					            pie: {
					                allowPointSelect: true,
					                cursor: 'pointer',
					                dataLabels: {
					                    enabled: true,
					                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
					                    style: {
					                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
					                    }
					                }
					            }
					        },
					        series: [{
					            name: '<?= _('Pourcentage')?>',
					            colorByPoint: true,
					            data: [
								<?php $temp =''; foreach($materials_stats as $material => $value){
									 $temp .="{
						                name: '". js_special_chars($material) . "',
						                y:$value
								    },";} ?>
								<?php echo(rtrim($temp, ",")); ?>
					            ]
					        }]
					    });
					});

                    </script>
                </div>
            </div>
        </div>
		<div class="col-lg-6 col-md-6 col-xs-12 ">
            <div class="panel panel-primary">
                <div class="panel-heading text-center"><?= _('Répartition des compétences travaillées')?>  </div>
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
                                name: '<?= _('Nombre de fois travaillées') ?> ',
                                colorByPoint: true,
                                data: [<?= implode(', ', $skills_usage) ?>]
                                }]
                        });
                    });

                    </script>
                </div>
            </div>
        </div>


    </div>   <!-- row -->
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

	</div>
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
