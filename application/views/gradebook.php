
  <main class="col-md-10">

     <script src="/assets/js/highcharts.js"></script>
     <script src="/assets/js/exporting.js"></script>

     <script>
     $(function () {
         $('#chart1').highcharts({
             title: {
                 text: 'Évolution de mes compétences',
                 x: -20 //center
             },
             subtitle: {
                 text: 'Année scolaire 2015-2016',
                 x: -20
             },
              xAxis: {
                  title: {
                     text: 'Projets'
                 },
                 categories: [<?= $projects ?>]
             },

             yAxis: {
                 title: {
                     text: 'Pourcentage'
                 },
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
                <?= $results ?>
             }]
         });
     });
     </script>
     <div class="row">
         <div class="col-md-2"></div>
         <div id="chart1" style="margin-top:1em;" class="col-md-10"></div>
     </div>



   </main>
