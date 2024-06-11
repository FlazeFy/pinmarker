<h4 class="text-center mt-3">Track Distance Travelled in <?= date('Y') ?></h4>
<div id="track_distance"></div>

<script>
    getDistanceTraveledHourly()
    function getDistanceTraveledHourly() {
        $.ajax({
            url: `http://127.0.0.1:2000/api/v1/track/hour/<?= date('Y') ?>/<?= $this->session->userdata('user_id'); ?>`,
            dataType: 'json',
            contentType: 'application/json',
            type: "get",
            beforeSend: function (xhr) {
                // ...
            }
        })
        .done(function (response) {            
            let data = response.data
            const timeCatOneTotal = data
                .filter(el => el.context >= 0 && el.context < 4)
                .reduce((acc, el) => acc + el.total, 0).toFixed(2)

            const timeCatTwoTotal = data
                .filter(el => el.context >= 4 && el.context < 8)
                .reduce((acc, el) => acc + el.total, 0).toFixed(2)

            const timeCatThreeTotal = data
                .filter(el => el.context >= 8 && el.context < 12)
                .reduce((acc, el) => acc + el.total, 0).toFixed(2)

            const timeCatFourTotal = data
                .filter(el => el.context >= 12 && el.context < 16)
                .reduce((acc, el) => acc + el.total, 0).toFixed(2)

            const timeCatFiveTotal = data
                .filter(el => el.context >= 16 && el.context < 20)
                .reduce((acc, el) => acc + el.total, 0).toFixed(2)

            const timeCatSixthTotal = data
                .filter(el => el.context >= 20 && el.context < 24)
                .reduce((acc, el) => acc + el.total, 0).toFixed(2)

            var options = {
                series: [{
                    name: 'Distance',
                    data: [timeCatOneTotal, timeCatTwoTotal, timeCatThreeTotal, timeCatFourTotal, timeCatFiveTotal, timeCatSixthTotal]
                }],
                chart: {
                    height: 350,
                    type: 'bar',
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        dataLabels: {
                            position: 'top',
                        },
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        if(val > 1000){
                            return (val/1000).toFixed(2) + " km"
                        } else {
                            return val.toFixed(2) + " m"
                        }
                    },
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ["#000"]
                    }
                },
                xaxis: {
                    categories: ["00:00 - 03:59", "04:00 - 07:59", "08:00 - 11:59", "12:00 - 15:59", "16:00 - 19:59", "20:00 - 23:59"],
                    position: 'top',
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    crosshairs: {
                        fill: {
                            type: 'gradient',
                            gradient: {
                                colorFrom: '#D8E3F0',
                                colorTo: '#BED1E6',
                                stops: [0, 100],
                                opacityFrom: 0.4,
                                opacityTo: 0.5,
                            }
                        }
                    },
                    tooltip: {
                        enabled: true,
                    }
                },
                yaxis: {
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false,
                    },
                    labels: {
                        show: false,
                        formatter: function (val) {
                            if(val > 1000){
                                return (val/1000).toFixed(2) + " km"
                            } else {
                                return val.toFixed(2) + " m"
                            }
                        }
                    }
                },
                stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            colors: ['#000'],
            };

            var chart = new ApexCharts(document.querySelector("#track_distance"), options);
            chart.render();
        })
    }
</script>