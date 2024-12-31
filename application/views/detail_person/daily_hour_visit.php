<div class="text-center">
    <?php 
        if(count($dt_visit_daily_hour_by_person) != 0){
            echo "<h4>Total Visit Daily & Hour</h4>
            <div id='HeatMap_$ctx'></div>";
        } else {
            echo "<img src='' class='img nodata-icon'>
            <h6 class='text-center'>No Data</h6>";
        }
    ?>
</div>

<script type="text/javascript">
    var options = {
        series: [
            <?php 
                $day_before = null;
                $series_data = [];
                foreach ($dt_visit_daily_hour_by_person as $idx => $dt) {
                    if ($day_before === null || $day_before !== $dt['day']) {
                        if ($day_before !== null) {
                            echo "]},";
                        }

                        $day_before = $dt['day'];
                        echo "{ name: '{$dt['day']}', data: [";
                    }

                    echo "{ x: '{$dt['hour']}', y: {$dt['total']} }";

                    if (isset($dt_visit_daily_hour_by_person[$idx + 1]) 
                        && $dt_visit_daily_hour_by_person[$idx + 1]['day'] === $day_before) {
                        echo ",";
                    }
                }
                echo "] }";
            ?>
        ],
        plotOptions: {
            heatmap: {
                shadeIntensity: 0.5,
                colorScale: {
                    ranges: [{
                        from: 0,
                        to: 0,
                        color: '#C7C5C5'
                    }]
                },
                stroke: {
                    width: 2, 
                    colors: ['#000000'] 
                }
            }
        },
        legend: {
            show: false
        },
        chart: {
            height: 360,
            type: 'heatmap',
            toolbar: {
                show: false
            },
        },
        dataLabels: {
            enabled: false
        },
        colors: ["#212121"],
    };

    var chart = new ApexCharts(document.querySelector("#HeatMap_<?=$ctx?>"), options);
    chart.render();
</script>
