<div id="content" class="col-xs-10 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau">
	</div>

<div class="row"  style="margin-top:1em">
	<div class="col-lg-6 col-md-6 col-xs-12 ">
		<div class="panel panel-success">
			<div class="panel-heading text-center" style="background-color:#5cb85c;color:white"><?= _('Nombre d\'élèves par année et par classe')?></div>
			<div class="panel-body text-left">
				<div id="class_size_progression"></div>
			</div>
		</div>
	</div>
</div>


<script src="/assets/js/highcharts.js"></script>
<script src="../assets/js/exporting.js"></script>
<script>

Highcharts.chart('class_size_progression', {
  chart: {
    type: 'area'
  },
  title: {
    text: ''
  },
  subtitle: {
    text: ''
  },
  xAxis: {
    categories: ['<?= implode("', '", $history['school_years']) ?>']
  },
  yAxis: {
    title: {
      text: 'Nombre d\'élèves'
    }
  },
  plotOptions: {
    line: {
      dataLabels: {
        enabled: true
      },
      enableMouseTracking: false
    },
		area: {
		stacking: 'normal',
		lineColor: '#666666',
		lineWidth: 1,
		marker: {
				lineWidth: 1,
				lineColor: '#666666'
		}
}
  },
  series: [
		<?php $i = 60;; foreach ($history['values'] as $class => $school_years): ?>
	{
		name: '<?= $class ?>',
		data: [<?= implode(", ", $school_years) ." \n"?>],
		index: <?= --$i  ?>
	},
	<?php endforeach ?>]
});
</script>
