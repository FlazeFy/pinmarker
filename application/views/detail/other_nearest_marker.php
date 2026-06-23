<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="card-title mb-0">Other Nearest Marker</h3>
            <p class="text-sm mb-0">Less than 5 Km</p>
        </div>
        <a href="/MapsController?lat=<?= $dt_detail_pin->pin_lat ?>&long=<?= $dt_detail_pin->pin_long ?>&max_distance=5" class="card-sub">See All</a>
    </div>
    <div class="row" id="nearest-marker-holder"></div>
    <div class="text-center mt-3">
        <button class="btn-see-more" id="nearest-marker-see-more-button" style="display:none">
            See More
        </button>
    </div>
</div>

<script>
    let pageNearestMarker = 1
    let totalPageNearestMarker = 1
    let isLoadingNearestMarker = false

    const fetchNearestMarker = (append = false) => {
        if (isLoadingNearestMarker) return
        isLoadingNearestMarker = true

        const holder = '#nearest-marker-holder'

        if (!append) {
            pageNearestMarker = 1
            $(holder).html(`
                <div class='col-lg-4 col-md-6 col-sm-12'>
                    <div class="skeleton-loading marker-skeleton"></div>
                </div>
                <div class='col-lg-4 col-md-6 col-sm-12'>
                    <div class="skeleton-loading marker-skeleton"></div>
                </div>
                <div class='col-lg-4 col-md-6 col-sm-12'>
                    <div class="skeleton-loading marker-skeleton"></div>
                </div>
            `)
        }

        $('#nearest-marker-see-more-button').prop('disabled', true).text('Loading...')

        const max_distance = 5
        const per_page = 7
        const lat = <?= $dt_detail_pin->pin_lat ?>;
        const long = <?= $dt_detail_pin->pin_long ?>;
        $.ajax({
            url: `/api/v1/pin/maps`,
            data: {
                page: pageNearestMarker,
                per_page,
                max_distance,
                lat,
                long
            },
            headers: {
                'Authorization': `Bearer ${tokenKey}`
            },
            method: 'GET',
            success: (response) => {
                const rows = response.data.data || []
                totalPageNearestMarker = response.data.total_page || 1

                if (rows.length === 0 && pageNearestMarker === 1) {
                    $(holder).html(`<span class='text-none text-center'>- No nearest-marker found -</span>`)
                    $('#nearest-marker-see-more-button').hide()
                    return
                }

                let html = ''
                rows.forEach(dt => {
                    if (dt.pin_name !== $('.page-title').text()) {
                        html += `
                            <div class='col-lg-4 col-md-6 col-sm-12'>
                                <div class="card flex-column p-3 position-relative mb-4">
                                    <span class="tag bg-primary position-absolute" style="top:-10px; right: var(--spaceMD);">${dt.pin_category}</span>
                                    <div class="marker-thumb position-relative w-100">
                                        <img src="https://placehold.co/300x220" alt="${dt.pin_name}" class="w-100">
                                        ${dt.is_favorite == 1 ? `
                                            <span class="fav-dot">
                                                <i class="fa-solid fa-heart"></i>
                                            </span>
                                        ` : ''}
                                    </div>
                                    <div class="marker-info">
                                        <h4 class="marker-name">${dt.pin_name}</h4>
                                        <div class="marker-addr">
                                            <i class="fa-solid fa-location-dot"></i> ${dt.pin_address}
                                        </div>
                                        <hr>
                                        <div class="d-flex gap-2 justify-content-between">
                                            <div class="marker-meta-col">
                                                <span class="meta-label">Visits</span>
                                                <span class="meta-val meta-val--primary">${dt.total_visit} Visits</span>
                                            </div>
                                            <div class="marker-meta-col text-end">
                                                <span class="meta-label">Distance</span>
                                                <span class="meta-val meta-val--primary">${dt.distance} Km</span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="/DetailController/view/${dt.id}" class="btn btn-see-more w-100">See Detail</a>
                                </div>
                            </div>
                        `
                    }
                })

                if (append) {
                    $(holder).append(html)
                } else {
                    $(holder).html(html)
                }

                if (pageNearestMarker < totalPageNearestMarker) {
                    $('#nearest-marker-see-more-button').show().prop('disabled', false).text('See More')
                } else {
                    $('#nearest-marker-see-more-button').hide()
                }
            },
            error: (response) => {
                if (response.status === 401) failedAuth()
                if (!append) $(holder).html(`<span class='text-none text-center'>- Failed fetch nearest-marker -</span>`)
                $('#nearest-marker-see-more-button').hide()
            },
            complete: () => {
                isLoadingNearestMarker = false
            }
        })
    }

    $(document).ready(() => {
        fetchNearestMarker()

        $('#nearest-marker-see-more-button').on('click', () => {
            pageNearestMarker++
            fetchNearestMarker(true)
        })
    })
</script>

<style>
    .skeleton-loading.marker-skeleton{
        width: 100%;
        height: 340px;
        border-radius: var(--roundedMD);
        flex-shrink: 0;
    }
</style>