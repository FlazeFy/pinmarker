<div class="text-center">
    <?php 
        if(1 != 0){
            echo "<h4>7 Top Person Journey</h4>
            <div id='MultiLine_TopPersonJourney'></div>";
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
                $person_before = null;
                $person_data = [];
                $months = [];

                foreach ($dt_top_visit_person_journey as $idx => $dt) {
                    if ($person_before != $dt->name) {
                        if ($person_before !== null) {
                            echo "{
                                name: '".ucwords($person_before)."',
                                data: [" . implode(",", $person_data[$person_before]) . "]
                            },";
                        }
                        $person_before = $dt->name;
                        $person_data[$person_before] = [];
                    }

                    $person_data[$dt->name][] = $dt->total;

                    if (!in_array($dt->visit_at, $months)) {
                        $months[] = "'$dt->visit_at'";
                    }
                }
                if ($person_before !== null) {
                    echo "{
                        name: '$person_before',
                        data: [" . implode(",", $person_data[$person_before]) . "]
                    },";
                }
            ?>
        ],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false
            }
        },
        colors: ['var(--warningBG)','#000','#414141','#4B4A54','#677381','#82A0AA','#B9B9B9'],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            curve: 'smooth'
        },
        grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            },
        },
        markers: {
            size: 1
        },
        xaxis: {
            categories: [<?php echo implode(",", $months); ?>],
        },
        yaxis: {
            title: {
                text: 'Total Visit'
            },
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            offsetX: 0
        }
    };

    var chart = new ApexCharts(document.querySelector("#MultiLine_TopPersonJourney"), options);
    chart.render();
</script>

