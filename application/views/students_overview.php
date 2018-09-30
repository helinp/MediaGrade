<div id="content" class="col-xs-10 col-md-10 ">
		<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau">
		<div class="col-xs-7 col-md-7">
		</div>
		<div class="col-md-5 ">
			<form id="view" action="" method="get" class="form-inline" style="margin-top:1.5em">
				<div class="form-group pull-right">
					<?php if($this->session->role == 'admin'): ?>
						<select class="form-control input-sm" name="view" onchange="this.form.submit()">
							<option value="detailled"<?= (@$_GET['view'] !== 'general' ? ' selected' : '')?>><?= _('Vue détaillée')?></option>
							<option value="general"<?= (@$_GET['view'] === 'general' ? ' selected' : '')?>><?= _('Vue d\'ensemble')?></option>
						</select>
					<?php endif ?>


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

	<!-- ACHIEVEMENTS -->
	<div class="row" style="margin-top:1em">
		<?php $class_txt = NULL; ?>
		<?php foreach($students as $student): ?>
			<?php if($student->class !== $class_txt)
			{
				echo '<div class="col-lg-12 col-xs-12"><h3>'. $student->class_name . '</h3></div>';
				$class_txt = $student->class;
			}
			?>
			<div class="col-lg-2 col-md-3 col-xs-4 ">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<?php if($this->session->role == 'admin'): ?>
							<a style="color:white" href="/admin/student/details?class=<?=$student->class?>&student=<?=$student->id?>"><?= $student->first_name ?></a>
						<?php else: ?>
							<?= $student->first_name ?>
						<?php endif ?>
					</div>
					<div class="panel-body text-center" style="min-height:17em">
						<img alt="avatar" class="center-block img-circle img-responsive"  src=<?= ($student->picture ? $student->picture : '/assets/img/default_avatar.jpg') ?>>
						<div style="margin-top:1em">
							<?php if($this->session->role == 'admin'): ?>
								<p<?= ($student->results < 50 ? ' style="color:darkred;font-weight:400"' : '') ?>>
								<?= $student->results ?>%
							</p>
							<h5><small><?= _('Résultats') ?></small></h5>
							<div style="margin-bottom:1em;" data-sparkline="<?= implode(', ', $student->all_results) ?> ; column"></div>
							<h5><small><?=_('Moyenne glissante')?></small></h5>
							<div style="margin-bottom:1em;" data-sparkline="<?= @implode(', ', $student->trend) ?> "></div>
						<?php endif; ?>
						<p style="font-size: smaller;">&quot;<?= $student->motto ?>&quot;</p>
					</div>
				</div>
				<div class="panel-footer" style="min-height: 3em;">
					<?php foreach ($student->achievements as $achievement): ?>
						<img src="<?= $achievement->icon?>" style="height:1.5em;" data-toggle="tooltip" data-placement="left"
						title="<?= $achievement->name ?>&#013;&#010;<?= str_repeat('&#9733;', $achievement->star)?>&#013;&#010;&quot;<?= $achievement->description; ?>&quot;" />
						<!--	<?= str_repeat('<img src="/assets/img/badges/star.svg" class="achievement-star-img" alt="stars" />', $achievement->star) ?>-->
					<?php endforeach ?>
				</div>
			</div>
		</div>
	<?php endforeach ?>
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
			<?= getPlotLinesJS() ?>
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
				lineWidth: 1,
				shadow: false,
				states: {
					hover: {
						lineWidth: 1
					}
				},
				marker: {
					radius: 2,
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
