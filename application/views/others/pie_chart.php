<div class="text-center">
    <?php 
        if(count($data) != 0){
            echo "<h4>"; echo ucwords(str_replace('_',' ',$ctx)); echo"</h4>
            <div id='Pie_$ctx'></div>";
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
                foreach($data as $c){
                    echo "$c->total,";
                }
            ?>
        ],
        chart: {
        width: '360',
        type: 'pie',
    },
    labels: [
        <?php 
            foreach($data as $c){
                echo "'$c->context',";
            }
        ?>
    ],
    colors: ['#000','#2A272A','#4B4A54','#677381','#82A0AA','#B9B9B9'],
    legend: {
        position: 'bottom'
    },
    responsive: [{
        // breakpoint: 480,
        options: {
            chart: {
                width: 160
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
    };

    var chart = new ApexCharts(document.querySelector("#Pie_<?= $ctx ?>"), options);
    chart.render();
</script>