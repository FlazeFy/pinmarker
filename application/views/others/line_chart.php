<div class="text-center">
    <?php 
        // Remove year
        $ctx = substr($ctx, 0, -5);

        if(count($data) != 0){
            echo "<h2>"; echo ucwords(str_replace('_',' ',$ctx)); echo"</h2>
            <div id='line_$ctx'></div>";
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
                name: '<?= $label ?>',
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
        colors: ['var(--primaryColor)'],
        xaxis: {
            categories: [<?php 
                foreach($data as $c){
                    echo "'$c->context',";
                }
            ?>]
        },
    };

    var chart = new ApexCharts(document.querySelector("#line_<?= $ctx ?>"), options)
    chart.render()
</script>