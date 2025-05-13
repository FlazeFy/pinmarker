<div class="text-center">
    <?php 
        if(count($data) != 0){
            echo "<h4>"; echo ucwords(str_replace('_',' ',$ctx)); echo"</h4>
            <div id='Line_$ctx'></div>";
        } else {
            echo "<img src='' class='img nodata-icon'>
            <h6 class='text-center'>No Data</h6>";
        }
    ?>
</div>

<script type="text/javascript">
    var options = {
            series: [
                {
                    name: 'Total Visit',
                    data: [<?php 
                        foreach($data as $c){
                            echo "$c->total,";
                        }
                    ?>],
                }
            ],
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: false,        
                tools: {
                    download: false 
                }
            },
            zoom: {
                enabled: false 
            }
        },
        dataLabels: {
            enabled: true
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            categories: [<?php 
                foreach($data as $c){
                    echo "'$c->context',";
                }
            ?>]
        },
    };

    var chart = new ApexCharts(document.querySelector("#Line_<?= $ctx ?>"), options)
    chart.render()
</script>