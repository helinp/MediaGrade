<?php if( ! $this->input->get('modal')): ?>
	<div id="content" class="col-xs-12 col-md-10 ">
		<?php $this->view('templates/submenu'); ?>
		<div class="row chapeau">
			<div class="col-xs-4  col-md-4">
			</div>
			<div class="col-xs-8  col-md-8">
				<form id="filter" method="get" class="form-inline" style="margin-top:1.5em">
					<div class="pull-right">
						<label><?= _('Année scolaire') ?>: </label>

						<select class="form-control input-sm" name="school_year" onchange="this.form.submit()">
							<?php foreach($school_years as $row): ?>
								<?= '<option value="' . $row->school_year . '"' . ( @$_GET['school_year'] === $row->school_year || ( !isset($_GET['school_year']) && $row->school_year === get_school_year() ) ? ' selected' : '') . '>' . $row->school_year . '</option>' . "\n" ?>
							<?php endforeach?>
						</select>

						<label><?= _('Classe') ?>: </label>

						<select class="form-control input-sm" name="classe" onchange="this.form.submit()">
							<option value="all" <?=( @$_GET['classe'] === 'all' ? ' selected' : '') ?>><?= _('Toutes les classes') ?></option>
							<?php foreach($classes as $class): ?>
								<?= '<option value="' . $class->id . '"' . ( @$_GET['classe'] === $class->id ? ' selected' : '') . '>' . $class->description . '</option>' . "\n" ?>
							<?php endforeach?>
						</select>

						<label><?= _('Élève') ?>: </label>

						<select id="student-select" class="form-control input-sm" name="student" onchange="this.form.submit()">
							<?php foreach($students as $class): ?>
								<?php foreach($class as $row): ?>
									<?= '<option value="' . $row->id . '"' . ( $this->uri->segment(4) === $row->id  ? ' selected' : '') . '>' . $row->first_name . ' ' . $row->last_name . '</option>' . "\n" ?>
								<?php endforeach ?>
							<?php endforeach?>
						</select>
					</div>
				</form>
			</div>
<?php else: ?>
			<div class="row chapeau chapeau-modal">
				<div class="col-xs-12  col-md-12">
					<h2><?= _('Fiche détaillée de') . ' ' . $user_data->first_name . ' ' . $user_data->last_name ?></h2>
				</div>
			</div>


<?php endif; ?>
	</div>

	<?php if(! isset($not_submitted)): ?>
		<div class="row" style="margin-top:1em">
			<div class="col-lg-4 col-md-4 col-xs-12 ">
				<p><?= _('Veuillez choisir un élève.'); ?></p>
			</div>
		</div>
	<?php else: ?>

		<!-- PANELS -->
		<?php if( ! $this->input->get('modal')): ?>
			<div class="row" style="margin-top:1em">
				<div class="col-xs-12 ">
					<h2><?= _('Fiche détaillée de') . ' ' . $user_data->first_name . ' ' . $user_data->last_name ?></h2>
				</div>
			</div>
		<?php endif ?>
		<div class="row" style="margin-top:1em">

			<div class="col-lg-4 col-md-4 col-xs-12 ">
				<div class="panel panel-danger">
					<div class="panel-heading text-center" style="background-color:#d9534f;color:white"><?= _('À remettre')?> <span class="badge"><?= count($not_submitted) ?></span></div>
					<div class="panel-body text-left">
						<table class="table table-striped small">
							<?php foreach($not_submitted as $row): ?>
								<tr>
									<td><?= $row->term_name ?></td>
									<td><?= $row->project_name ?></td>
									<td><?= $row->deadline; ?></td>
									<td><a data-toggle="modal" data-target="#projectModal" href="/projects/instructions/<?= $row->project_id?>"><span class="glyphicon glyphicon-file" data-toggle="tooltip" data-placement="top" title="Consignes"> </span></a></td>
								</tr>
							<?php endforeach ?>
						</table>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-xs-12 ">
				<div class="panel panel-success">
					<div class="panel-heading text-center"  style="background-color:#5cb85c;color:white"><?= _('Derniers résultats')?></div>
					<div class="panel-body text-left">
						<table class="table table-striped small">
							<?php foreach($graded as $row): ?>
								<tr>
									<td><?= $row->term_name ?></td>
									<td><?= $row->project_name ?></td>
									<td<?= ($row->average->total_user < $row->average->total_max / 2 ?  ' class="text-danger dotted_underline" ' : '') ?>><?= $row->average->total_user . ' / ' . $row->average->total_max ?></td>
									<td><a data-toggle="modal" data-target="#projectModal" href="/admin/results/details/<?= $row->project_id?>/<?= $row->average->user_id ?>"><span data-toggle="tooltip" data-placement="top" title="Détails" class="glyphicon glyphicon-zoom-in"> </span></a></td>
								</tr>
							<?php endforeach ?>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-xs-12 ">
				<div class="panel panel-info">
					<div class="panel-heading text-center"  style="background-color:#2EB2FA;color:white"><?= _('Tendance générale')?></div>
					<div class="panel-body text-left">
						<big class="overall-results" style="background-color:<?=returnLSUColorFromPercentage($total_year_result) ?>" data-toggle="tooltip" data-placement="top" title="<?= returnTextExplainationsFromPercentage($total_year_result) ?>">
							<?= returnFunMentionTextFromPercentage($total_year_result)?>
						</big>
						<table class="table small">
							<tr style="color: white;">
								<?php foreach ($terms_results as $term => $overall_result): ?>
								<td style="border: 1px solid white;width:<?= 100 / count($terms_results)?>%;text-align: center;background-color:<?=returnLSUColorFromPercentage($overall_result) ?>" data-toggle="tooltip" data-placement="bottom" title="<?= returnTextExplainationsFromPercentage($overall_result) ?>">
									<b><?= $term ?></b><br><?= returnFunMentionTextFromPercentage($overall_result) ?>
									<br />
									(<?=$overall_result?>%)
								</td>
								<?php endforeach; ?>
							</tr>
						</table>
					</div>
				</div>
			</div>

		</div><!-- . row -->
		<div class="row" style="margin-top:1em">
			<!--
			<div class="col-lg-12 col-md-12 col-xs-12 ">
			<div class="panel panel-info">
			<div class="panel-heading text-center"  style="background-color:#2EB2FA;color:white"><?= _('Moyenne des pôles de compétences')?></div>
			<div class="panel-body text-left" style="min-height:0">
			<table class="table small">
			<tr>
			<?php foreach ($skills_results as $skill => $overall_result): ?>
			<td style="text-align: center;border:none;">
			<h5><?= $skill ?></h5><span style="font-size:1.4em;<?= (is_numeric($overall_result) && $overall_result < 50 ? ';color:red' : '')?>"><?= (is_numeric($overall_result) ? $overall_result . ' %' : '-')  ?></span>
		</td>
	<?php endforeach; ?>
</tr>
</table>
</div>
</div>
</div>
-->
<div class="col-lg-12 col-md-12 col-xs-12 ">
	<div class="panel panel-primary">
		<div class="panel-heading text-center" >
			<?= _('Évolution des pôles de compétences')?>
		</div>
		<div class="panel-body text-left">
			<div id="skills_evolution" class="chartReport" style="margin-top:1em;" ></div>
			<script src="https://code.highcharts.com/highcharts.js"></script>
			<script src="https://code.highcharts.com/highcharts-more.js"></script>
			<script>
			Highcharts.chart('skills_evolution', {
				title: {
					text: ''
				},
				xAxis: {
					categories: [<?= $graph_projects_list ?>],
					reversed: true
				},
				yAxis: {
					title: {
						text: '<?= _('Pourcentage') ?>'
					},
					min: 0, max: 100,
					<?= getPlotLinesJS() ?>
			},
			series: [<?= $graph_results ?>, {
				type: 'spline',
				name: 'Total pondéré',
				data: [<?= implode(', ', $projects_overall_results) ?>],
				connectNulls: true,
				marker: {
					lineWidth: 2,
					lineColor: Highcharts.getOptions().colors[1],
					fillColor: 'white'
				}
			}],
			<?= getAutoColorsJS() ?>
		});
		</script>
	</div>
</div>
</div>
</div><!-- . row -->

<div class="row" style="margin-top:1em">
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
						<?= getPlotLinesJS() ?>
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
				}],
				<?= getAutoColorsJS() ?>
			});
			</script>
		</div>
	</div>
</div>

<div class="col-lg-6 col-md-6 col-xs-12 ">
	<div class="panel panel-info">
		<div class="panel-heading text-center"  style="background-color:#2EB2FA;color:white"><?= _('Résultats par critère')?></div>
		<div class="panel-body text-left">
			<script>
			$(function () {
				Highcharts.chart('polar_chart', {
					navigation: {
						buttonOptions: {
							enabled: false
						}
					},
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
						tickInterval: 20,
						<?= getPlotLinesJS() ?>
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
		<div id="polar_chart" class="chartReport" style="margin-top:1em;"></div>
	</div>
</div>
</div>

<div class="col-lg-6 col-md-6 col-xs-12 ">
	<div class="panel panel-info">
		<div class="panel-heading text-center"  style="background-color:#2EB2FA;color:white"><?= _('Détail par curseur')?></div>
		<div class="panel-body text-left">
			<table class="table small">
				<?php $temp = '';?>
				<?php foreach ($cursor_results as $detailled_result): ?>
					<?php if($detailled_result['criterion'] === $temp) {} else {$temp = $detailled_result['criterion'];
						echo('<tr><th colspan="3" style="padding-bottom:4px;border-top:none;border-bottom: 1px lightgray solid">' . $temp . ' (' . $cnt_graded_criteria[$temp] . ')</th></tr>');}
						?>
						<tr<?= ($detailled_result['average'] < 50 ? ' class="text-danger"' : '')?>>
						<td style="border-top: 1px #ddd dotted">
							<?= _('J\'ai') . ' ' .  $detailled_result['cursor'] ?>
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
</div><!-- . row -->

<?php endif ?>
<?php if($this->input->get('modal') !== 'true'): ?>
</div><!-- . content -->
<?php endif ?>
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
