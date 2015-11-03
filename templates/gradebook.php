 <?php 
                    function results($group_id, $results)
                    {
                        $string = "";
                                              
                        foreach ($results[$group_id] as $result)
                        {
                            
                                $string .= ("[Date.UTC(20" . str_replace("-", ",", $result["date"]) . "), " . $result["user_grade"] . "], \n");                       
                           
                        }  
                        
                        print(substr("$string", 0, -2));
                    }
                    ?>  
   <main class="col-md-10">         
       
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="http://code.highcharts.com/modules/exporting.js"></script>

    <script>
            $(function () {
                $('#chart1').highcharts({
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: 'Compétences'
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {
                        type: 'datetime',
                        dateTimeLabelFormats: { // don't display the dummy year
                        month: '%b',
                        year: '%y'
                        }
                        
                    },
                    yAxis: {
                        title: {
                            text: 'Pourcentage'
                        }
                    },
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            },
                            enableMouseTracking: true
                        }
                    },
                    series: [{
                        name: 'FAIRE',
                        data: [
                        <?= results("F", $results); ?>]
                    }, {
                        name: 'COMPRENDRE',
                        data: [
                        <?= results("C", $results); ?>]
                    }, {
                        name: 'APPRECIER',
                        data: [
                        <?= results("A", $results); ?>]
                    }, {
                        name: 'REGARDER ET ECOUTER',
                        data: [<?= results("R", $results); ?>]
                    }, {
                        name: 'S\'EXPRIMER',
                        data: [<?= results("E", $results); ?>]
                    }]
                });
        });
    </script>

    <div id="chart1" class="col-centered"></div>

     
                    
    
    </main>
