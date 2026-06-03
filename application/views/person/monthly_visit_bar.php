<div class="card">
    <div id="vertical-bar-chart"></div>
</div>

<script>
    const getMonthlyVisitBar = (data, year) => {
        renderVerticalBarChart(
            '#vertical-bar-chart',
            data.map(dt => dt.context),
            data.map(dt => dt.total),
            `Total Monthly Visit ${year}`,
            true
        )
    }
</script>