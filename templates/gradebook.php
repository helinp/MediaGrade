 <?php 
                    function results($results)
                    {
                        $string = "";
                        
                        foreach ($results as $skill => $node)                      
                        {
                            $string .= "name:'" . $skill . "',\ndata:[";
                        
                            foreach ($node as $result)
                            {
                                $string .= ("[Date.UTC(" . str_replace("-", ",", $result["date"]) . "), " . $result["AVG(user_grade)"] * 10 . "], \n");                       
                            }  
                            
                            $string = substr("$string", 0, -3);
                            $string .= "]}, {"; 
                        }
                        $string = substr("$string", 0, -3);
                        //
                        
                        print($string);
                    }
                    //dump(results("COMPRENDRE", $results));
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
                        text: '<?= $lang['SKILLS'] ?>'
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
                            text: '<?= $lang['PERCENT'] ?>'
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
                        
                        <?= results($results); ?>
                    ]
                });
        });
    </script>
    <div class="row">
        <div class="col-md-2"></div>
        <div id="chart1" style="margin-top:1em;" class="col-md-10"></div>
    </div>
     
                    
    
    </main>
