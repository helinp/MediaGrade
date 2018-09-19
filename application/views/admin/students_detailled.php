<div id="content" class="col-xs-10 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau">
		<div class="col-xs-7 col-md-7">
		</div>
		<div class="col-md-3 ">
			<?php if($this->session->role == 'admin'): ?>
				<form id="view" action="" method="get" class="form-inline" style="margin-top:1.5em">
					<div class="form-group">
						<select class="form-control input-sm" name="view" onchange="this.form.submit()">
							<option value="detailled"<?= (@$_GET['view'] !== 'general' ? ' selected' : '')?>><?= _('Vue détaillée')?></option>
							<option value="general"<?= (@$_GET['view'] === 'general' ? ' selected' : '')?>><?= _('Vue d\'ensemble')?></option>
						</select>
					</div>
				</form>
			<?php endif ?>
		</div>
		<div class="col-md-2">
			<form id="filter" action="" method="get" class="form-inline" style="margin-top:1.5em">
				<div class="form-group">
					<select class="form-control input-sm" name="classe" onchange="this.form.submit()">
						<option value=""><?= _('Toutes les classes')?></option>
						<?php foreach($classes as $classe): ?>
							<?= '<option value="' . $classe->id . '"' . (@$_GET['classe'] === $classe->id ? 'selected' : '') . '>' . $classe->description . '</option>' . "\n" ?>
						<?php endforeach?>
					</select>
				</div>
			</form>
		</div>
	</div>
	<?php $class_txt = NULL?>
	<table class="table">
		<?php foreach($students as $student): ?>
			<?php if($student->class !== $class_txt): ?>
			</table>
			<h3 style="margin-bottom: 0;"><?= $classes[array_search($student->class , array_column($classes, 'id'))]->description; ?></h3>
			<table class="table">
				<thead>
					<tr>
						<th style="width:6em">Progression</th> <!-- -->
						<th style="width:6em"></th> <!-- -->
						<th></th> <!-- -->
						<th><?= _('Badges')?></th> <!-- -->
						<!--<th><?= _('Travaux à remettre') ?></th> -->
						<?php foreach ($skills_groups as $skills_group): ?>
							<th style="width:4em"><span  data-toggle="tooltip" data-placement="top" title="<?=$skills_group->name?>"><?= $skills_group->name[0] ?></span></th> <!-- -->
						<?php endforeach; ?>
						<th><?= _('Total') ?></th> <!-- -->
						<th><?= _('Résultats') ?></th> <!-- -->
					</tr>
				</thead>
				<?php $class_txt = $student->class; ?>
			<?php endif	?>
			<tr class="students-detailled-tr">
				<?php
				switch ($student->progression)
				{
					case 1:
					$progress = 'up';
					$progress_color = 'success';
					break;

					case -1:
					$progress = 'down';
					$progress_color = 'danger';
					break;

					default:
					$progress = 'right';
					$progress_color = 'primary';
					break;
				}

				?>
				<td><div  data-sparkline="<?= @implode(', ', $student->trend) ?> "></div></td>
				<!--<td><span style="font-size:1em;" class="text-<?=$progress_color?> glyphicon glyphicon-arrow-<?= $progress ?>"> <span></td>  -->
				<td><img data-toggle="tooltip"  data-placement="bottom" title="&quot;<?= $student->motto ?>&quot;" alt="avatar" style="height: 4em;" class="center-block img-circle img-responsive"  src="<?= ($student->picture ? $student->picture : '/assets/img/default_avatar.jpg') ?>"></td> <!-- Avatar -->
				<td><a href="/admin/student/details/<?=$student->id?>"><?= $student->first_name . ' ' . $student->last_name ?></a></td> <!-- Name -->
				<td>
					<?php foreach ($student->achievements as $achievement): ?>
						<img src="<?= $achievement->icon?>" style="height:1.5em;" data-toggle="tooltip" data-placement="right"
						title="<?= $achievement->name ?>&#013;&#010;<?= str_repeat('&#9733;', $achievement->star)?>&#013;&#010;&quot;<?= $achievement->description; ?>&quot;" />
					<?php endforeach ?>
				</td> <!-- Achievements -->

				<!--	<td>05 / 10</td>  A remettre -->
				<?php foreach ($skills_groups as $skills_group): ?>
					<td<?= @(is_numeric($student->skills_groups_results{$skills_group->id}) && $student->skills_groups_results{$skills_group->id} < 50 ? ' class="text-danger"' : '') ?>><?= @$student->skills_groups_results{$skills_group->id} ?><?=(is_numeric($student->skills_groups_results{$skills_group->id})? '%' : '')?></td> <!-- -->
				<?php endforeach; ?>
				<td style="font-weight:500"<?= ($student->results < 50 ? ' class="text-danger"' : '') ?>><?= ($student->results ? $student->results . '%' : '---') ?></td> <!-- Results -->
				<td style="width:10em"><div style="align:left" data-sparkline="<?= implode(', ', $student->all_results) ?> ; column"></div></td> <!-- Graph -->
			</tr>
		<?php endforeach ?>
	</table>











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

	<!-- Sparklines -->
	<script src="/assets/js/highcharts.js"></script>

	<script>
	/**
	* Create a constructor for sparklines that takes some sensible defaults and merges in the individual
	* chart options. This function is also available from the jQuery plugin as $(element).highcharts('SparkLine').
	*/
	Highcharts.SparkLine = function (a, b, c) {
		var hasRenderToArg = typeof a === 'string' || a.nodeName,
		options = arguments[hasRenderToArg ? 1 : 0],
		defaultOptions = {
			chart: {
				renderTo: (options.chart && options.chart.renderTo) || this,
				backgroundColor: null,
				borderWidth: 0,
				type: 'spline',
				margin: [2, 0, 2, 0],
				height: 40,
				style: {
					overflow: 'visible'
				},

				// small optimalization, saves 1-2 ms each sparkline
				skipClone: true
			},
			title: {
				text: ''
			},
			credits: {
				enabled: false
			},
			xAxis: {
				labels: {
					enabled: false
				},
				title: {
					text: null
				},
				startOnTick: false,
				endOnTick: false,
				lineWidth: 0,
				tickPositions: []
			},
			yAxis: {
				endOnTick: false,
				gridLineColor: 'transparent',
				lineColor: null,
				startOnTick: false,
				labels: {
					enabled: false
				},
				title: {
					text: null
				},
				min: 0,
				softMin: 50,
				max: 100,
				tickPositions: [0],
				plotLines: [{
					color: 'rgba(125,125,125,.35)',
					dashStyle: 'solid',
					width: 1,
					value: 50,
					zIndex: 3,
					opacity: 0.25,
				},{
					color: 'rgba(125,125,125,.45)',
					dashStyle: 'dot',
					width: 1,
					value: 100,
					zIndex: 3,
					opacity: 0.25
				},{
					color: 'rgba(125,125,125,.45)',
					dashStyle: 'dot',
					width: 1,
					value: 0,
					zIndex: 3,
					opacity: 0.25
				}]
			},
			legend: {
				enabled: false
			},
			tooltip: {
				/*backgroundColor: '#ffffff',*/
				fillOpacity: 0.25,
				borderWidth: 1,
				shadow: false,
				useHTML: true,
				hideDelay: 0,
				shared: true,
				padding: 0,
				positioner: function (w, h, point) {
					return { x: point.plotX - w / 2, y: point.plotY - h };
				}
			},
			plotOptions: {
				series: {
					animation: false,
					connectNulls: true,
					lineWidth: 2,
					shadow: false,
					states: {
						hover: {
							lineWidth: 1
						}
					},
					marker: {
						radius: 0,
						states: {
							hover: {
								radius: 2
							}
						}
					},
					fillOpacity: 0.25,
					threshold: 50,
					negativeColor: '#910000',
					borderColor: 'silver',

				},
			}
		};

		options = Highcharts.merge(defaultOptions, options);

		return hasRenderToArg ?
		new Highcharts.Chart(a, options, c) :
		new Highcharts.Chart(options, b);
	};

	var start = +new Date(),
	$divs = $('div[data-sparkline]'),
	fullLen = $divs.length,
	n = 0;

	// Creating 153 sparkline charts is quite fast in modern browsers, but IE8 and mobile
	// can take some seconds, so we split the input into chunks and apply them in timeouts
	// in order avoid locking up the browser process and allow interaction.
	function doChunk() {
		var time = +new Date(),
		i,
		len = $divs.length,
		$div,
		stringdata,
		arr,
		data,
		chart;

		for (i = 0; i < len; i += 1) {
			$div = $($divs[i]);
			stringdata = $div.data('sparkline');
			arr = stringdata.split('; ');
			data = $.map(arr[0].split(', '), parseFloat);
			chart = {};

			if (arr[1]) {
				chart.type = arr[1];
			}
			$div.highcharts('SparkLine', {
				series: [{
					data: data,
					pointStart: 1
				}],
				tooltip: {
					headerFormat: '',
					pointFormat: '<b>{point.y}</b>%'
				},
				chart: chart
			});

			n += 1;

			// If the process takes too much time, run a timeout to allow interaction with the browser
			if (new Date() - time > 500) {
				$divs.splice(0, i + 1);
				setTimeout(doChunk, 0);
				break;
			}
			/*
			// Print a feedback on the performance
			if (n === fullLen) {
			$('#result').html('Generated ' + fullLen + ' sparklines in ' + (new Date() - start) + ' ms');
		}*/
	}
}
doChunk();
</script>
