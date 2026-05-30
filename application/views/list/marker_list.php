<div class="d-flex flex-column gap-3 mb-4" id="markerList"></div>

<div class="d-flex justify-content-between align-items-center border-top pt-4 mt-2">
    <span class="pagination-info" id="paginationInfo"></span>
    <div class="d-flex gap-2 align-items-center" id="paginationButtonHolder"></div>
</div>

<script>
    const fetchPin = (page = 1) => {
        const holder = '#markerList'
        const paginationHolder = '#paginationHolder'

        $(holder).html(`
            <div class="skeleton-loading marker-skeleton"></div>
            <div class="skeleton-loading marker-skeleton"></div>
            <div class="skeleton-loading marker-skeleton"></div>
        `)

        $.ajax({
            url: '/api/v1/pin',
            method: 'GET',
            data: {
                page,
                per_page: 14
            },
            success: (response) => {
                if (!response.data || !response.data.data) {
                    $(holder).html(`
                        <div class="empty-state">
                            <i class="fa-solid fa-map-location-dot"></i>
                            <span>No marker found</span>
                        </div>
                    `)
                    return
                }

                const pins = response.data.data
                let html = ''
                pins.forEach(pin => {
                    html += `
                        <div class="marker-card card-lift">
                            <div class="marker-thumb position-relative">
                                <img src="https://placehold.co/300x220" alt="${pin.pin_name}">
                                ${pin.is_favorite == 1 ? `
                                    <span class="fav-dot">
                                        <i class="fa-solid fa-heart"></i>
                                    </span>
                                ` : ''}
                            </div>
                            <div class="marker-info">
                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                    <h4 class="marker-name">${pin.pin_name}</h4>
                                    <span class="tag bg-${pin.pin_color || 'secondary'}">
                                        ${pin.pin_category}
                                    </span>
                                </div>
                                <div class="marker-addr">
                                    <i class="fa-solid fa-location-dot"></i>
                                    ${pin.pin_address}
                                </div>
                                <p class="marker-desc">
                                    ${pin.pin_desc || '<span class="fst-italic text-secondary">- No description provided -</span>'}
                                </p>
                                <hr>
                                <div class="d-flex gap-4 mt-2 flex-wrap">
                                    <div class="marker-meta-col">
                                        <span class="meta-label">Created At</span>
                                        <span class="meta-val">
                                            ${pin.created_at}
                                        </span>
                                    </div>
                                    <div class="marker-meta-col">
                                        <span class="meta-label">Visits</span>
                                        <span class="meta-val meta-val--primary">
                                            ${pin.total_visit} Visits
                                        </span>
                                    </div>
                                    <div class="marker-meta-col">
                                        <span class="meta-label">Last Visit</span>
                                        <span class="meta-val meta-val--warn">
                                            ${pin.last_visit || '-'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="marker-actions">
                                <a href="https://www.google.com/maps/dir/?api=1&destination=${pin.pin_lat},${pin.pin_long}" target="_blank" class="btn btn-outline" title="Directions">
                                    <i class="fa-solid fa-location-arrow"></i>
                                </a>
                                <a href="/DetailController/view/${pin.id}" class="btn btn-see-more">
                                    See Details
                                </a>
                            </div>
                        </div>
                    `
                })
                $(holder).html(html)

                const totalItem = response.data.total_item
                const totalPage = response.data.total_page
                const startItem = response.data.start_item
                const endItem = response.data.end_item
                $('#paginationInfo').html(`Showing ${startItem}-${endItem} of ${totalItem} markers`)
                renderPagination(page, totalPage)
            },
            error: () => {
                $(holder).html(`
                    <div class="empty-state text-danger">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Failed fetch marker</span>
                    </div>
                `)
            }
        })
    }

    $(document).ready(() => fetchPin())
</script>

<style>
    .skeleton-loading.marker-skeleton{
        width: 100%;
        height: 220px;
        border-radius: var(--roundedLG);
    }
</style>