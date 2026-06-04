<div class="card">
    <div id="favorite-tag-chart"></div>
</div>

<script>
    const getFavoriteTag = (data) => {
        data.pop()

        renderVerticalBarChart(
            '#favorite-tag-chart',
            data.map(dt => `#${dt.context}`),
            data.map(dt => dt.total),
            `Top Favorite Tag`,
            true
        )
    }
</script>