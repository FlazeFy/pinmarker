<div class="card">
    <div id="visited-place-category-chart"></div>
    <hr>
    <div id="favorite-place-comparison-chart"></div>
</div>

<script>
    const getVisitCategoryDonut = (dataVisited, dataFavorite) => {
        renderDonutChart(
            '#visited-place-category-chart',
            dataVisited.map(dt => dt.context),
            dataVisited.map(dt => dt.total),
            'Visited Place Category'
        )
        renderDonutChart(
            '#favorite-place-comparison-chart',
            dataFavorite.map(dt => dt.context),
            dataFavorite.map(dt => dt.total),
            'Favorited Place Category'
        )
    }
</script>