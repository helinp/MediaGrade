<div id="content" class="col-xs-12 col-md-10 ">
    <div class="row chapeau">
        <div class="col-xs-9 col-md-9">
            <h1> <?= _('Tableau de bord') ?></h1>
        </div>
        <div class="col-xs-3  col-md-3">
              <form id="filter" method="get" class="form-inline" style="margin-top:1.5em">
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
        <div class="col-lg-4 col-md-4 col-xs-12 ">
            <div class="panel panel-danger">
                <div class="panel-heading text-center" style="background-color:#d9534f;color:white"><?= _('À remettre')?> <span class="badge"><?= count($not_submitted) ?></span></div>
                <div class="panel-body text-left">
                    <table class="table table-striped small">
                        <?php foreach($not_submitted as $row): ?>
                            <tr>
                                <td><?= $row->term ?></td>
                                <td><?= $row->project_name ?></td>
                                <td><?= $row->deadline; ?></td>
                                <td><a data-toggle="modal" data-target="#projectModal" href="/student/project/instructions/<?= $row->project_id?>"><span class="glyphicon glyphicon-file" data-toggle="tooltip" data-placement="top" title="Consignes"> </span></a></td>
                                <td><a data-toggle="modal" data-target="#projectModal" href="/student/project/submit/<?= $row->project_id?>"><span data-toggle="tooltip" data-placement="top" title="Remise" class="glyphicon glyphicon-save"> </span></a></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
		<div class="col-lg-4 col-md-4 col-xs-12 ">
			<div class="panel panel-success">
				<div class="panel-heading text-center"  style="background-color:#5cb85c;color:white"><?= _('Derniers résultats')?> <span class="badge"><?= count($graded) ?></span></div>
				<div class="panel-body text-left">
					<table class="table table-striped small">
						<?php foreach($graded as $row): ?>
							<tr>
								<td><?= $row->term ?></td>
								<td><?= $row->project_name ?></td>
								<td<?= ($row->average->total_user < $row->average->total_max / 2 ?  ' class="text-danger dotted_underline" ' : '') ?>><?= $row->average->total_user . ' / ' . $row->average->total_max ?></td>
								<td><a data-toggle="modal" data-target="#projectModal" href="/student/project/results/<?= $row->project_id?>"><span data-toggle="tooltip" data-placement="top" title="Détails" class="glyphicon glyphicon-zoom-in"> </span></a></td>
							</tr>
						<?php endforeach ?>
					</table>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-xs-12 ">
			<div class="panel panel-info">
				<div class="panel-heading text-center"  style="background-color:#2EB2FA;color:white"><?= _('Moyenne générale')?></div>
				<div class="panel-body text-left">
					<big style="font-size: 4em;text-align: center;display:block;"><?= $total_year_result?>%</big>
					<table class="table small">
						<tr>
							<?php foreach ($terms_results as $term => $overall_result): ?>
							<td style="text-align: center">
								<?= $term ?><br><?= ($overall_result ? $overall_result . '%' : '-')  ?>
							</td>
							<?php endforeach; ?>
						</tr>
					</table>
				</div>
			</div>
		</div>
    </div>

	<div class="row" style="margin-top:1em">
			<div class="col-lg-12 col-md-12 col-xs-12 ">
				<div class="panel panel-primary">
					<div class="panel-heading text-center" ><?= _('Évolution de mes compétences')?> (<?= _('Année scolaire') ?> <?= $school_year ?>)</div>
					<div class="panel-body text-left">

						<script src="https://code.highcharts.com/highcharts.js"></script>
						<script src="https://code.highcharts.com/modules/exporting.js"></script>
						<script src="https://code.highcharts.com/highcharts-more.js"></script>

						<div id="skills_evolution" style="margin-top:1em;" ></div>

						<script>
						Highcharts.chart('skills_evolution', {
						    title: {
						        text: ''
						    },
						    xAxis: {
						        categories: [<?= $graph_projects_list ?>]
						    },
							yAxis: {
								title: {
									text: '<?= _('Pourcentage') ?>'
								},
								min: 0, max: 100,

								plotBands: [{
									from: 95,
									to: 102,
									color: 'rgba(0, 0, 255, 0.05)',
									label: {
										text: 'Super-Héro',
										style: {
											color: '#808080'
										}
									}
								}, {
									from: 82,
									to: 95,
									color: 'rgba(0, 0, 0, 0)',
									label: {
										text: 'Professionnel',
										style: {
											color: '#808080'
										}
									}
								}, {
									from: 62,
									to: 82,
									color: 'rgba(0, 0, 255, 0.05)',
									label: {
										text: 'Amateur confirmé',
										style: {
											color: '#808080'
										}
									}
								}, {
									from: 50,
									to: 62,
									color: 'rgba(0, 0, 0, 0)',
									label: {
										text: 'Amateur',
										style: {
											color: '#808080'
										}
									}
								}, {
									from: 11,
									to: 50,
									color: 'rgba(0, 0, 255, 0.05)',
									label: {
										text: 'Tantine à la mer',
										style: {
											color: '#808080'
										}
									}
								}]},
						    series: [<?= $graph_results ?>, {
						        type: 'spline',
						        name: 'Total pondéré',
						        data: [<?= implode(', ', $projects_overall_results) ?>],
								connectNulls: true,
						        marker: {
						            lineWidth: 2,
						            lineColor: Highcharts.getOptions().colors[3],
						            fillColor: 'white'
						        }
						    }]
						});
						</script>


					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-xs-12 ">
				<div class="panel panel-info">
					<div class="panel-heading text-center"  style="background-color:#2EB2FA;color:white"><?= _('Moyenne des pôles de compétences')?></div>
					<div class="panel-body text-left">
						<div id="skills_groups_graph"  style="margin-top:1em;"></div>
						<script>
						Highcharts.chart('skills_groups_graph', {
							chart: {
								type: 'column'
							},
							title: {
								text: ''
							},
							xAxis: {
								categories: ["<?= implode("\", \"", array_keys($skills_results)) ?>"],
								crosshair: true
							},
							yAxis: {
								min: 0,
								title: {
									text: 'Pourcentage'
								},
								min: 0, max: 100,

								plotBands: [{
									from: 95,
									to: 102,
									color: 'rgba(0, 0, 255, 0.05)',
									label: {
										text: 'Super-Héro',
										style: {
											color: '#808080'
										}
									}
								}, {
									from: 82,
									to: 95,
									color: 'rgba(0, 0, 0, 0)',
									label: {
										text: 'Professionnel',
										style: {
											color: '#808080'
										}
									}
								}, {
									from: 62,
									to: 82,
									color: 'rgba(0, 0, 255, 0.05)',
									label: {
										text: 'Amateur confirmé',
										style: {
											color: '#808080'
										}
									}
								}, {
									from: 50,
									to: 62,
									color: 'rgba(0, 0, 0, 0)',
									label: {
										text: 'Amateur',
										style: {
											color: '#808080'
										}
									}
								}, {
									from: 11,
									to: 50,
									color: 'rgba(0, 0, 255, 0.05)',
									label: {
										text: 'Tantine à la mer',
										style: {
											color: '#808080'
										}
									}
								}]
							},

							plotOptions: {
								column: {
									pointPadding: 0.2,
									borderWidth: 0
								}
							},
							series: [{
								showInLegend: false,
								data: [<?= implode(", ", $skills_results) ?>]
							}]
						});
						</script>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-xs-12 ">
				<div class="panel panel-info">
					<div class="panel-heading text-center"  style="background-color:#2EB2FA;color:white"><?= _('Résultats par critère')?></div>
					<div class="panel-body">
						<div id="polar_chart" style="margin-top:1em;"></div>
						<script>
							$(function () {
							    Highcharts.chart('polar_chart', {

							        chart: {
							            polar: true,
							            type: 'line'
							        },

							        title: {
							            text: '',
							            x: -80
							        },

							        pane: {
							            size: '80%'
							        },

							        xAxis: {
							            categories: ["<?= implode("\", \"", array_column((array) $criterion_results, 'conca')) ?>"],
							            tickmarkPlacement: 'on',
							            lineWidth: 0
							        },

							        yAxis: {
							            gridLineInterpolation: 'polygon',
							            lineWidth: 0,
							            min: 0,
										max: 100,
 										tickInterval: 20
							        },

							        tooltip: {
							            shared: true,
							            pointFormat: '<span style="color:{series.color}"><b>{point.y:,.0f}%</b><br/>'
							        },

									legend: {
										enabled: false
									},

							        series: [{
							            name: 'Moyenne',
							            data: [<?= implode(', ', array_column((array) $criterion_results, 'average')) ?>],
							            pointPlacement: 'on'
							        }]

							    });
							});
						</script>

					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-xs-12 ">
				<div class="panel panel-info">
					<div class="panel-heading text-center"  style="background-color:#5cb85c;color:white"><?= _('Mes points forts')?></div>
					<div class="panel-body text-left">
						<table class="table small">
						<?php $temp = ''; ?>
						<?php foreach ($best_results as $detailled_result): ?>
								<?php if($detailled_result['criterion'] === $temp) {} else {$temp = $detailled_result['criterion'];echo('<tr><th colspan="3" style="font-weight:400;padding-bottom:4px;border-top:none;border-bottom: 1px lightgray solid">' . $temp . '</th></tr>');} ?>
								<tr<?= ($detailled_result['average'] < 50 ? ' class="danger-left"' : '')?>>
									<td style="border-top: 1px #ddd dotted">
										J'ai <?= $detailled_result['cursor'] ?>
									</td>
									<td style="border-top: 1px #ddd dotted">
										<?= $detailled_result['average']  ?>%
									</td>
									<td style="border-top: 1px #ddd dotted">
										(<?= $detailled_result['count']  ?>)
									</td>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-xs-12 ">
				<div class="panel panel-info">
					<div class="panel-heading text-center"  style="background-color:#d9534f;color:white"><?= _('Ce que je dois travailler')?></div>
					<div class="panel-body text-left">
						<table class="table small">
						<?php $temp = ''; ?>
						<?php foreach ($worst_results as $detailled_result): ?>

								<?php if($detailled_result['criterion'] === $temp) {} else {$temp = $detailled_result['criterion'];echo('<tr><th colspan="3" style="font-weight:400;padding-bottom:4px;border-top:none;border-bottom: 1px lightgray solid">' . $temp . '</th></tr>');} ?>
								<tr<?= ($detailled_result['average'] < 50 ? ' class="danger-left"' : '')?>>
									<td style="border-top: 1px #ddd dotted">
										J'ai <?= $detailled_result['cursor'] ?>
									</td>
									<td style="border-top: 1px #ddd dotted">
										<?= $detailled_result['average']  ?>%
									</td>
									<td style="border-top: 1px #ddd dotted">
										(<?= $detailled_result['count']  ?>)
									</td>
								</tr>
							<?php endforeach; ?>
						</table>
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

<!-- Tool tips-->
<script type="text/javascript">
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
});
</script>
