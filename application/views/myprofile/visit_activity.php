<h4 class="text-center mt-3">Visit Activity in <?= date('Y') ?></h4>
<div id="visit_activity"></div>

<script>
    let data = [<?php 
        foreach($dt_visit_activity as $dt){
            echo "
                {
                    context: '$dt->context',
                    total: $dt->total
                },
            ";
        }
    ?>]

    function generateDataForWeekday(weekday, year) {
        let res = []
        let currentDate = new Date(year, 0, 1)
        while (currentDate.getFullYear() === year) {
            if (currentDate.getDay() === weekday) {
                let date = currentDate.toISOString().split('T')[0]
                // let val = Math.floor(Math.random() * (range.max - range.min + 1)) + range.min
                let val  = 0
                for(var i = 0; i < data.length; i++){
                    if(data[i].context == date){
                        val = data[i].total
                    } 
                }
                res.push({
                    x: date,
                    y: val,
                })
            }
            currentDate.setDate(currentDate.getDate() + 1)
        }
        return res
    }

    var options = {
        series: [
            {
                name: 'Saturday',
                data: generateDataForWeekday(6, 2024)
            },
            {
                name: 'Friday',
                data: generateDataForWeekday(5, 2024)
            },
            {
                name: 'Thursday',
                data: generateDataForWeekday(4, 2024)
            },
            {
                name: 'Wednesday',
                data: generateDataForWeekday(3, 2024)
            },
            {
                name: 'Tuesday',
                data: generateDataForWeekday(2, 2024)
            },
            {
                name: 'Monday',
                data: generateDataForWeekday(1, 2024)
            },
            {
                name: 'Sunday',
                data: generateDataForWeekday(0, 2024)
            },
        ],
        chart: {
            height: 200,
            type: 'heatmap',
            toolbar: {
                show: false
            },
        },
        dataLabels: {
            enabled: false,
        },
        colors: ["#212121"],
        xaxis: {
            type: 'category',
            labels: {
                formatter: function (value) {
                    let date = new Date(value);
                    let month = date.toLocaleString('default', { month: 'short' })
                    let weekNumber = Math.ceil(date.getDate() / 7)

                    if(weekNumber == 1){
                        return `${month}`
                    } else {
                        return ``
                    }
                },
                style: {
                    colors: '#000'
                }
            }
        },
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
        yaxis : {
            labels : {
                formatter: function (value) {
                    if (value == 'Sunday') {
                        return 'Sat'
                    } else if (value == 'Monday') {
                        return 'Sun'
                    } else if (value == 'Tuesday') {
                        return 'Mon'
                    } else if (value == 'Wednesday') {
                        return 'Tue'
                    } else if (value == 'Thursday') {
                        return 'Wed'
                    } else if (value == 'Friday') {
                        return 'Thu'
                    } else if (value == 'Saturday') {
                        return 'Fri'
                    }
                },
                style: {
                    colors: '#000'
                }
            }
        },
        tooltip: {
            x: {
                show: true,
                formatter: function (value, { series, seriesIndex, dataPointIndex, w }) {
                    let dayOfWeek = new Date(value).getDay()
                    let date = new Date(value)                            

                    let seriesName = w.config.series[seriesIndex].name

                    if (seriesName == 'Sunday') {
                        date.setDate(date.getDate() + 0) 
                    } else if (seriesName == 'Monday') {
                        date.setDate(date.getDate() + 1)
                    } else if (seriesName == 'Tuesday') {
                        date.setDate(date.getDate() + 2) 
                    } else if (seriesName == 'Wednesday') {
                        date.setDate(date.getDate() + 3)
                    } else if (seriesName == 'Thursday') {
                        date.setDate(date.getDate() + 4) 
                    } else if (seriesName == 'Friday') {
                        date.setDate(date.getDate() + 5) 
                    } else if (seriesName == 'Saturday') {
                        date.setDate(date.getDate() + 6)
                    }
                    date.setDate(date.getDate() - 6)
                    return `${date.getDate()}-${date.getMonth()+1}-${date.getFullYear()}`
                },
            },
            y: {
                formatter: function (value, { series, seriesIndex, dataPointIndex, w }) {
                    let seriesData = w.config.series[seriesIndex].data
                    let yValue = seriesData[dataPointIndex].y
                    return yValue
                },
                title: {
                    formatter: (seriesName) => `Total Visit : `,
                },
            },
        },
    }

    var chart = new ApexCharts(document.querySelector("#visit_activity"), options)
    chart.render()
</script>