<div class="hero-banner mb-4">
    <div class="position-relative" style="z-index:1;">
        <div>
            <h3 class="text-white mb-0 text-capitalize" id="analyze-name-text">Jhon</h3>
            <p class="mb-0 text-white text-sm" id="analyze-first-trip-text">First trip at 20 Jan 2026</p>
        </div>
        <hr>
        <div class="row g-3">
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">My Ranking</div>
                    <div class="stat-value" id="analyze-rank-text">#12</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Total Visits</div>
                    <div class="stat-value" id="analyze-total-trip-text">142</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Avg. Distance</div>
                    <div class="stat-name">2.4 Km</div>
                    <div class="stat-meta">From your current location</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Last Visit</div>
                    <div class="stat-name" id="analyze-last-trip-text">2 days ago</div>
                    <div class="stat-meta">at Place A</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Avg. Day / Visit</div>
                    <div class="stat-value">Every 9 days</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Favorite Days</div>
                    <div class="stat-name">Thursday</div>
                    <div class="stat-meta">34 visit at this day</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Favorite Hours</div>
                    <div class="stat-name" id="analyze-favorite-hour-context-text">2 PM</div>
                    <div class="stat-meta"><span id="analyze-favorite-hour-total-text">40</span> visit at this hour</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Favorite Category</div>
                    <div class="stat-name" id="analyze-most-visited-category-text">Cafe</div>
                    <div class="stat-meta">40 visit at this category</div>
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

        $.ajax({
            url: `/api/v1/visit/visit_with/analyze/${name}?year_monthly_chart=${year}`,
            method: 'GET',
            success: (response) => {
                const data = response.data
                
                const personSummary = data.visit_person_summary
                $('#analyze-first-trip-text').text(personSummary.first_trip)
                $('#analyze-last-trip-text').text(personSummary.last_trip)
                $('#analyze-total-trip-text').text(personSummary.total_trip)
                $('#analyze-most-visited-category-text').text(personSummary.most_visited_category)
                $('#analyze-favorite-hour-context-text').text(personSummary.favorite_hour_context)
                $('#analyze-favorite-hour-total-text').text(personSummary.favorite_hour_total)

                getMonthlyVisitBar(data.visit_pertime_year, year)
                getVisitCategoryDonut(data.visit_location_category, data.visit_location_favorite)
                getRecentVisit(data.visit_by_person)
                getHourlyVisit(data.visit_pertime_hour)
            },
            error: () => {
                Swal.fire({
                    title: 'Error!',
                    text: `Failed to fetch person analyze`,
                    icon: 'error'
                })
                $('#all-person-section').removeClass('d-none')
                $('#single-person-section').addClass('d-none')
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
        fetchPersonAnalyze(name)
    })
</script>