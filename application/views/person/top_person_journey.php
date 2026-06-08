<div class="card mb-4">
    <h3>7 Top Person Journey</h3>
    <div id='MultiLine_TopPersonJourney'></div>
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
        colors: ['var(--primaryColor)','var(--infoBG)','var(--successBG)','var(--warningBG)','var(--dangerBG)','var(--secondaryColor)'],
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

