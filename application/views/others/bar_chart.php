<div class="text-center">
    <?php 
        if(count($data) != 0){
            echo "<h2>"; echo ucwords(str_replace('_',' ',$ctx)); echo"</h2>
            <div id='Bar_$ctx'></div>";
        } else {
            echo "
                <h4>".ucwords(str_replace('_',' ',$ctx))."</h4>
                <img src='http://127.0.0.1:8080/public/images/chart.png' class='img nodata-icon'>
                <h5>No Data</h5>
            ";
        }
    ?>
</div>

<script type="text/javascript">
    var options = {
        series: [{
            name: "Total",
            data: [
                <?php 
                    foreach($data as $c){
                        echo "$c->total,";
                    }
                ?>
            ]
        }],
        chart: {
            type: 'bar',
            height: 400
        },
        xaxis: {
            categories: [
                <?php 
                    foreach($data as $c){
                        echo "'$c->context',";
                    }
                ?>
            ]
        },
        legend: {
            position: 'bottom'
        },
        responsive: [{
            options: {
                chart: {
                    height: 250
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#Bar_<?= $ctx ?>"), options);
    chart.render();
</script>
