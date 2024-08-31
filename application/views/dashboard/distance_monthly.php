<div class="text-center">
    <h4>Distance In <?= date('Y') ?></h4>
    <div id="distance_montly_line_chart"></div>
</div>

<script type="text/javascript">
    getMonthlyDistance()
    function getMonthlyDistance() {
        $.ajax({
            url: `http://127.0.0.1:8000/api/v1/track/year/<?= date('Y') ?>/<?= $this->session->userdata('user_id'); ?>`,
            dataType: 'json',
            contentType: 'application/json',
            type: "get",
            beforeSend: function (xhr) {
                // ...
            }
        })
        .done(function (response) {
            $("#tb_history_track_body").empty()
            
            let data = response.data
            let values = []
            for (let index = 1; index <= 12; index++) {
                let found = false
                data.forEach(el => {
                    if(el.context == index){
                        values.push(el.total.toFixed(2))
                        found = true 
                    }
                });
                if(!found){
                    values.push(0)
                }
            }

            var options = {
                    series: [
                        {
                            name: 'Total Distance',
                            data: values,
                            color: 'black',
                        }
                    ],
                chart: {
                    height: 350,
                    type: 'area',
                },
                dataLabels: {
                    enabled: true,
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
                },
                yaxis: {
                    title: {
                        text: 'Distance in Meters'
                    },
                    labels: {
                        formatter: function (val) {
                            return val.toFixed(2)
                        }
                    },
                }
            };

            var chart = new ApexCharts(document.querySelector("#distance_montly_line_chart"), options)
            chart.render()
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            // Do something
        });
    }
</script>