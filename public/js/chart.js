const renderVerticalBarChart = (holder, categories, data, label, hideYAxis = false, height = 350, color = '#635bff') => {
    $(holder).empty()
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

    $(holder).prev('.chart-title').remove()
    $(holder).before(`<h3 class="chart-title">${label}</h3>`)

    return chart
}

const renderDonutChart = (holder, labels, data, label, height = 350, colors = ['#635bff', '#8b85ff', '#ff9f43', '#00966b', '#ef0025']) => {
    $(holder).empty()
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

    $(holder).prev('.chart-title').remove()
    $(holder).before(`<h3 class="chart-title">${label}</h3>`)

    return chart
}

const renderHeatMapChart = (holder, rawData, label, height = 350, color = '#635bff') => {
    $(holder).empty()

    const grouped = {}
    rawData.forEach(dt => {
        if (!grouped[dt.day]) grouped[dt.day] = []

        grouped[dt.day].push({
            x: dt.hour,
            y: parseInt(dt.total)
        })
    })

    const series = Object.keys(grouped).map(day => ({
        name: day,
        data: grouped[day]
    }))

    const options = {
        chart: {
            type: 'heatmap',
            height,
            toolbar: {
                show: false
            }
        },
        series,
        dataLabels: {
            enabled: false
        },
        colors: [color],
        legend: {
            show: false
        },
        plotOptions: {
            heatmap: {
                radius: 6,
                shadeIntensity: 0.5,
                colorScale: {
                    ranges: [
                        {
                            from: 0,
                            to: 0,
                            color: '#ececf5',
                            name: 'Empty'
                        }
                    ]
                }
            }
        },
        stroke: {
            width: 2,
            colors: ['#ffffff']
        },
        grid: {
            borderColor: '#ececf5'
        },
        tooltip: {
            theme: 'light'
        },
        xaxis: {
            labels: {
                rotate: -45
            }
        }
    }

    const chart = new ApexCharts(
        document.querySelector(holder),
        options
    )

    chart.render()
    $(holder).prev('.chart-title').remove()
    $(holder).before(`<h3 class="chart-title">${label}</h3>`)

    return chart
}