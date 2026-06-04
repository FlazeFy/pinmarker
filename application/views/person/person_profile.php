<div class="hero-banner">
    <div class="position-relative" style="z-index:1;">
        <div>
            <h3 class="text-white mb-0 text-capitalize" id="analyze-name-text">-</h3>
            <p class="mb-0 text-white text-sm" id="analyze-first-trip-text">-</p>
        </div>
        <hr>
        <div class="row g-3">
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">My Ranking</div>
                    <div class="stat-value" id="analyze-rank-text">-</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Total Visits</div>
                    <div class="stat-value" id="analyze-total-trip-text">-</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Avg. Distance</div>
                    <div class="stat-name"><span id="analyze-avg-distance-text"></span> Km</div>
                    <div class="stat-meta">From your current location</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Last Visit</div>
                    <div class="stat-name" id="analyze-last-trip-text">-</div>
                    <div class="stat-meta" id="analyze-last-visit-pin-name-text"></div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Avg. Day / Visit</div>
                    <div class="stat-value" id="analyze-avg-day-visit-text">-</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Favorite Days</div>
                    <div class="stat-name" id="analyze-favorite-day-context-text">-</div>
                    <div class="stat-meta"><span id="analyze-favorite-day-total-text">-</span> visit at this day</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Favorite Hours</div>
                    <div class="stat-name" id="analyze-favorite-hour-context-text">-</div>
                    <div class="stat-meta"><span id="analyze-favorite-hour-total-text">-</span> visit at this hour</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Favorite Category</div>
                    <div class="stat-name" id="analyze-most-visited-category-context-text">-</div>
                    <div class="stat-meta"><span id="analyze-most-visited-category-total-text">-</span> visit at this category</div>
                </div>
            </div>
        </div>
    </div>
    <i class="fa-solid fa-user hero-deco"></i>
    <div class="hero-blur"></div>
</div>

<script>
    const fetchPersonAnalyze = (name, year = 2026) => {
        if (isLoading) return
        isLoading = true
        const userLat = getCookie('lat')
        const userLng = getCookie('long')

        $.ajax({
            url: `/api/v1/visit/visit_with/analyze/${name}?year_monthly_chart=${year}&lat=${userLat}&long=${userLng}`,
            method: 'GET',
            success: (response) => {
                const data = response.data
                
                const personSummary = data.visit_person_summary
                $('#analyze-first-trip-text').text(`First trip at ${datetimeText(personSummary.first_trip)}`)
                $('#analyze-last-trip-text').text(datetimeText(personSummary.last_trip))
                $('#analyze-total-trip-text').text(personSummary.total_trip)
                $('#analyze-most-visited-category-context-text').text(personSummary.most_visited_category_context)
                $('#analyze-most-visited-category-total-text').text(personSummary.most_visited_category_total)
                $('#analyze-favorite-hour-context-text').text(personSummary.favorite_hour_context)
                $('#analyze-favorite-hour-total-text').text(personSummary.favorite_hour_total)
                $('#analyze-favorite-day-context-text').text(personSummary.favorite_day_context)
                $('#analyze-favorite-day-total-text').text(personSummary.favorite_day_total)
                $('#analyze-avg-distance-text').text(personSummary.avg_distance)
                $('#analyze-last-visit-pin-name-text').text(`at ${personSummary.last_visit_pin_name}`)
                const avgDayVisit = personSummary.avg_day_visit
                $('#analyze-avg-day-visit-text').text(`Every ${avgDayVisit} day${avgDayVisit > 1 ? 's':''}`)

                getMonthlyVisitBar(data.visit_pertime_year, year)
                getVisitCategoryDonut(data.visit_location_category, data.visit_location_favorite)
                getRecentVisit(data.visit_by_person)
                getHourlyVisit(data.visit_pertime_hour)
                renderVisitedPin(data.visit_location)    
                getVisitDailyHourHeatmap(data.visit_daily_hour_by_person)  
                getReviews(data.reviews)      
                getFavoriteTag(data.favorite_tag)
            },
            error: () => {
                Swal.fire({
                    title: 'Error!',
                    text: `Failed to fetch person analyze`,
                    icon: 'error'
                })
                $('#all-person-section').removeClass('d-none')
                $('#single-person-section').removeClass('d-none').addClass('d-flex')
            },
            complete: () => {
                isLoading = false
            }
        })
    }

    $(document).on('click', '.activity-item', function(){
        const name = $(this).data('name')
        const rank = $(this).data('rank')

        $('#analyze-name-text').text(name)
        $('#analyze-rank-text').text(rank)

        setTimeout(() => {
            map.invalidateSize()
        }, 200)

        fetchPersonAnalyze(name)
    })
</script>