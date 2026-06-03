const renderVerticalBarChart = (holder, categories, data, label, hideYAxis = false, height = 350, color = '#635bff') => {
    const options = {
        chart: {
            type: 'bar',
            height,
            toolbar: {
                show: false
            }
        },
        series: [{
            name: label,
            data
        }],
        xaxis: {
            categories
        },
        yaxis: {
            show: !hideYAxis
        },
        plotOptions: {
            bar: {
                borderRadius: 8,
                columnWidth: '45%'
            }
        },
        dataLabels: {
            enabled: false
        },
        colors: [color],
        grid: {
            borderColor: '#ececf5',
            yaxis: {
                lines: {
                    show: !hideYAxis
                }
            }
        },
        tooltip: {
            theme: 'light'
        }
    }

    const chart = new ApexCharts(
        document.querySelector(holder),
        options
    )
    chart.render()

    $(holder).before(`<h3>${label}</h3>`)

    return chart
}

const renderDonutChart = (holder, labels, data, label, height = 350, colors = ['#635bff', '#8b85ff', '#ff9f43', '#00966b', '#ef0025']) => {
    const options = {
        chart: {
            type: 'donut',
            height
        },
        labels,
        series: data,
        colors,
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 0
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '68%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label
                        }
                    }
                }
            }
        },
        tooltip: {
            theme: 'light'
        }
    }

    const chart = new ApexCharts(
        document.querySelector(holder),
        options
    )
    chart.render()

    $(holder).before(`<h3>${label}</h3>`)

    return chart
}