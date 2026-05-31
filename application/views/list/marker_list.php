<div class="d-flex flex-column gap-3 mb-4" id="markerList"></div>

<div class="d-flex justify-content-between align-items-center border-top pt-4 mt-2">
    <span class="pagination-info" id="paginationInfo"></span>
    <div class="d-flex gap-2 align-items-center" id="paginationButtonHolder"></div>
</div>

<script>
    const fetchPin = (page = 1) => {
        const holder = '#markerList'
        const categoryHolder = '#categoryTag'
        const paginationHolder = '#paginationHolder'
        const sorting = $('#sortSelect').val()
        const per_page = $('#itemPerPageSelect').val()
        let pin_category = getSelectedCategories()
        pin_category = pin_category === '' ? null : pin_category

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
                per_page,
                sorting,
                pin_category 
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
                let htmlItem = ''
                pins.forEach(dt => {
                    htmlItem += `
                        <div class="marker-card card-lift">
                            <div class="marker-thumb position-relative">
                                <img src="https://placehold.co/300x220" alt="${dt.pin_name}">
                                ${dt.is_favorite == 1 ? `
                                    <span class="fav-dot">
                                        <i class="fa-solid fa-heart"></i>
                                    </span>
                                ` : ''}
                            </div>
                            <div class="marker-info">
                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                    <h4 class="marker-name">${dt.pin_name}</h4>
                                    <span class="tag bg-${dt.pin_color || 'secondary'}">
                                        ${dt.pin_category}
                                    </span>
                                </div>
                                <div class="marker-addr">
                                    <i class="fa-solid fa-location-dot"></i>
                                    ${dt.pin_address}
                                </div>
                                <p class="marker-desc">
                                    ${dt.pin_desc || '<span class="text-none">- No description provided -</span>'}
                                </p>
                                <hr>
                                <div class="d-flex gap-4 mt-2 flex-wrap">
                                    <div class="marker-meta-col">
                                        <span class="meta-label">Created At</span>
                                        <span class="meta-val">
                                            ${dt.created_at}
                                        </span>
                                    </div>
                                    <div class="marker-meta-col">
                                        <span class="meta-label">Visits</span>
                                        <span class="meta-val meta-val--primary">
                                            ${dt.total_visit} Visits
                                        </span>
                                    </div>
                                    <div class="marker-meta-col">
                                        <span class="meta-label">Last Visit</span>
                                        <span class="meta-val meta-val--warn">
                                            ${dt.last_visit || '-'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="marker-actions">
                                <a href="https://www.google.com/maps/dir/?api=1&destination=${dt.pin_lat},${dt.pin_long}" target="_blank" class="btn btn-outline" title="Directions">
                                    <i class="fa-solid fa-location-arrow"></i>
                                </a>
                                <a href="/DetailController/view/${dt.id}" class="btn btn-see-more">
                                    See Detail
                                </a>
                            </div>
                        </div>
                    `
                })
                $(holder).html(htmlItem)

                const categories = response.data.category
                if (categories && categories.length > 0) {
                    $(categoryHolder).empty()
                    const categoriesSelected = pin_category ? pin_category.split(',') : null
                    categories.forEach(dt => {
                        const cat = dt.pin_category
                        $(categoryHolder).append(`<span class="filter-chip ${categoriesSelected && categoriesSelected.includes(cat) ? 'active' : ''}" data-filter="${cat}">(${dt.total}) ${cat}</span>`)  
                    })
                } else {
                    $(categoryHolder).html(`<span class="text-none">- No markers available -</span>`)  
                }

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