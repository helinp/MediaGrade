<div id="content" class="col-xs-12 col-md-10 ">
<?php $this->view('templates/submenu'); ?>
<div class="row chapeau">
	<div class="col-xs-6  col-md-6">

	</div>
	<div class="col-xs-6  col-md-6">
		<form id="filter" action="" method="get" class="form-inline" style="margin-top:1.5em">
			<div class="pull-right">
			<label><?= _('Classe: ') ?></label>
			<select class="form-control input-sm" name="classe" onchange="this.form.submit()">
				<option value=""><?= _('Toutes')?></option>
				<?php foreach($classes as $classe): ?>
					<?= '<option value="' . $classe->id . '"' . (@$_GET['classe'] === $classe->id ? 'selected' : '') . '>' . $classe->description . '</option>' . "\n" ?>
				<?php endforeach?>
			</select>
			<label><?= _('Année scolaire') ?>: </label>
			<select class="form-control input-sm" name="school_year" onchange="this.form.submit()">
				<option value=""<?= (@empty($_GET['school_year']) ? ' selected' : '') ?>><?= _('Toutes')?></option>
				<?php foreach($school_years as $school_year): ?>
					<?= '<option value="' . $school_year->school_year . '"' . (@$_GET['school_year'] === $school_year->school_year ? ' selected' : '') . '>' . $school_year->school_year . '</option>' . "\n" ?>
				<?php endforeach?>
			</select>
			</div>
		</form>
	</div>
</div>
<div class="row" style="margin-top:1em">
	<div class="col-lg-4 col-md-4 col-xs-12 ">
		<div class="panel panel-danger">
			<div class="panel-heading text-center"  style="background-color:#d9534f;color:white"><?= _('À corriger')?> <span class="badge"><?= count($not_graded_projects) ?></span>
			</div>
			<div class="panel-body text-left panel-overflow">
				<?php if($not_graded_projects): ?>
					<table class="table table-striped small">
						<?php foreach($not_graded_projects as $row): ?>
							<tr>
								<td><?= $row->class_name ?></td>
								<td><?= $row->last_name . ' ' . $row->first_name ?></td>
								<td><?= $row->project_name ?></td>
								<td><a title="Corriger"  data-toggle="modal" data-target="#projectModal" href="/admin/grade/assess/<?= $row->class?>/<?= $row->project_id?>/<?= $row->user_id?>"><span class="glyphicon glyphicon-pencil"> </span></a></td>
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
			<div class="panel-body text-left panel-overflow">
				<?php if($active_projects): ?>
					<table class="table table-striped small">
						<?php foreach($active_projects as $row): ?>
							<tr>
								<td><?= $row->class_name ?></td>
								<td><?= $row->term ?></td>
								<td><?= $row->project_name ?></td>
								<td><?= date_format(date_create($row->deadline),"d/m/Y"); ?></td>
								<td><a title="Modifier le projet" data-toggle="modal" data-target="#projectModal" href="/admin/project/management/<?= $row->project_id ?>"><span class="glyphicon glyphicon-file"> </span></a></td>
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
			<div class="panel-body text-left panel-overflow">
				<?php if($last_submitted): ?>
					<table class="table table-striped small">
						<?php foreach($last_submitted as $row): ?>
							<tr>
								<td><?= $row->class_name ?></td>
								<td><?= $row->last_name . ' ' . $row->first_name ?></td>
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
				<script src="/assets/js/highcharts.js"></script>
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
									text: '<?= _('Max. /10') ?>'
								},
								plotBands: [{
									from: 8,
									to: 10,
									color: 'rgba(204, 255, 153, .5)',
									label: {
										text: 'Très bonne maîtrise',
										style: {
											color: '#808080'
										}
									}
								}, {
									from: 6,
									to: 7.9,
									color: 'rgba(229, 255, 204, .5)',
									label: {
										text: 'Maîtrise satisfaisante',
										style: {
											color: '#808080'
										}
									}
								}, {
									from: 5.0,
									to: 5.9,
									color: 'rgba(255, 229, 204, .5)',
									label: {
										text: 'Maîtrise fragile',
										style: {
											color: '#808080'
										}
									}
								}, {
									from: 0,
									to: 4.9,
									color: 'rgba(255, 204, 204, .5)',
									label: {
										text: 'Maîtrise insuffisante',
										style: {
											color: '#808080'
										}
									}
								}
							]

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
									data: [<?= @implode(', ', @$gaus['percentage']) ?>]
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
			<div class="panel-heading text-center"  style="background-color:#d9534f;color:white"><?= _('Élèves à surveiller')?></div>
			<div class="panel-body text-left">
				<table class="table table-striped small">
					<?php foreach($ranking_bottom as $row): ?>
						<tr>
							<td><?= $row->class_name ?></td>
							<td><a data-toggle="modal" data-target="#projectModal" href="/admin/results/detail_by_student/<?= $row->user_id?>?modal=true&school_year=<?= $current_school_year ?>&class=<?= $row->class ?>"><?= $row->first_name . ' ' . $row->last_name ?></a></td>
							<td<?= ($row->average < 50 ? ' class="text-danger" ' : '')?>><?= $row->average ?> %</td>
						</tr>
					<?php endforeach ?>
				</table>
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
							<td><?= $row->class_name ?></td>
							<td><a data-toggle="modal" data-target="#projectModal" href="/admin/student/details'<?= $row->user_id?>?modal=true&school_year=<?= $current_school_year ?>&class=<?= $row->class ?>"><?= $row->first_name . ' ' . $row->last_name ?></a></td>
							<td<?= ($row->average < 50 ? ' class="text-danger" ' : '')?><?= ($row->average < 50 ? ' class="text-danger" ' : '')?>><?= $row->average ?> %</td>
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
			<div class="panel-heading text-center"><?= _('Réussites par compétence')?> </div>
			<div class="panel-body text-left">
				<div id="skills_results" style="margin: 0 auto"></div>
				<script>


					 // Age categories
					 var categories = ['<?= implode("', '", array_column($skills_stats, 'skill_name') ) ?>'];

					 Highcharts.chart('skills_results', {
					     chart: {
					         type: 'column'
					     },
					     title: {
					         text: ''
					     },
					     xAxis: [{
							  title: {
									text: 'Compétences'
							  },
					         categories: categories,
					         reversed: false,
					         labels: {
					             step: 1
					         }

					     }],
					     yAxis: {
					         title: {
					             text: 'Nombre de réussites/échecs'
					         },
					         labels: {
					             formatter: function () {
					                 return Math.abs(this.value) + 'x';
					             }
					         }
					     },

					     plotOptions: {
					         series: {
					             stacking: 'normal'
					         }
					     },

					     tooltip: {
					         formatter: function () {
					             return this.series.name + ': ' + Highcharts.numberFormat(Math.abs(this.point.y), 0);
					         }
					     },

					     series: [{
							  color: '#2B908F',
					         name: 'Réussites (>49%)',
					         data: [<?= implode(", ", array_column($skills_stats, 'success') ) ?>]
					     }, {
							  color: '#F45B5B',
					         name: 'Échecs',
					         data: [-<?= implode(", -", array_column($skills_stats, 'failed') ) ?>]
					     }]
					 });
						/**
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
							categories: ['<?= implode("', '", array_keys($skills_results)) ?>'],
							crosshair: true
						},
						yAxis: {
							min: 0,
							title: {
								text: '<?= _('Pourcentage') ?>'
							},
							allowDecimals: false

						},
						tooltip: {
							pointFormat: '<?= _('Pourcentage') ?>: <b>{point.y} % </b>'
						},
						plotOptions: {

						},

						series: [{
							name: '<?= _('Pourcentage') ?> ',
							colorByPoint: true,
							data: [<?= implode(', ', $skills_results) ?>]
						}]
					});
				});
				**/

				</script>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-xs-12 ">
		<div class="panel panel-primary">
			<div class="panel-heading text-center"><?= _('Répartition des compétences travaillées')?> </div>
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
							},
							allowDecimals: false
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
		<script src="https://code.highcharts.com/modules/treemap.js"></script>

		<script src="https://code.highcharts.com/modules/exporting.js"></script>

		<div class="col-lg-6 col-md-6 col-xs-12 ">
			<div class="panel panel-primary">
				<div class="panel-heading text-center"><?= _('Répartition des compétences évaluées')?> </div>
				<div class="panel-body text-left">
					<div id="skills_assessed" style="margin: 0 auto"></div>
					<?php
					$js_assessed = array();

					$graph_colors = ['#8085E9','#F15C80', '#90ED7D', '#F45B5B', '#2B908F'];
					$c_graph_colors = 5;
					foreach ($assessed_skills as $assessed_skill) {
						$parent_id = @ord($assessed_skill['skills_group'][0]) . @ord($assessed_skill['skills_group'][1]);
						$js_assessed[] = 	"name: '". js_special_chars($assessed_skill['id']) ."',
						value: " . $assessed_skill['count'] . ",
						parent: '" . $parent_id . "',
						skill: '" . js_special_chars($assessed_skill['name']) . "',
						skills_group: '" . js_special_chars($assessed_skill['skills_group']) . "'
						";
					}
					?>
					<?php
					$groups = '';
					$i = 0;
					foreach ($skills_groups as $skills_group)
					{
						if($skills_group)
						{
							$parent_id =  ord($skills_group->name[0]) . ord($skills_group->name[1]);

							$groups .= "{
								id:'" .  $parent_id . "',
								name: '" . js_special_chars($skills_group->name) . "',
								color: '". $graph_colors[$i] . "'},";

								if($i === $c_graph_colors - 1)
								{
									$i = 0;
								}
								$i++;
							}
						}
						?>
						<script>
						$(function () {
							$('#skills_assessed').highcharts({
								/*colorAxis: {
								minColor: '#FFFFFF',
								maxColor: Highcharts.getOptions().colors[0]
							},*/
							series: [{
								type: 'treemap',
								alternateStartingDirection: true,
								layoutAlgorithm: 'stripes',
								data: [<?= $groups ?> {<?= implode("}, {", $js_assessed) ?>}],
								levels: [{
									level: 1,
									layoutAlgorithm: 'sliceAndDice',
									dataLabels: {
										enabled: true,
										align: 'left',
										verticalAlign: 'top',
										style: {
											fontSize: '15px',
											fontWeight: 'normal',
											textOutline: '0'
										}
									}
								}]
							}],
							title: {
								text: ''
							},
							tooltip: {
								formatter: function() {
									return '<b>Occurences:</b> ' + this.point.value +'<br/><b>Pôle: </b>' + this.point.skills_group  + '<br/><b>Compétence: </b>' + this.point.skill + '';
								}
							}
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
