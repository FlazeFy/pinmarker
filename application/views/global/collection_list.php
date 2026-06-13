<div class="row g-4" id="globalList"></div>
<div class="d-flex justify-content-between align-items-center border-top pt-4 mt-4">
    <span class="pagination-info" id="paginationInfoHolder"></span>
    <div class="d-flex gap-2 align-items-center" id="paginationButtonHolder"></div>
</div>

<script>
    const fetchGlobalList = (page = 1) => {
        const holder = '#globalList'
        const companionHolder = '#companionTag'
        const paginationHolder = '#paginationHolder'
        const paginationInfoHolder = '#paginationInfoHolder'
        const sorting = $('#sortSelect').val()
        const per_page = $('#itemPerPageSelect').val()
        const with_companion = $('#withCompanionSelect').val()
        let visit_with = getSelectedTag('visit-with')
        visit_with = visit_with === '' ? null : visit_with

        $(holder).html(`
            <div class="col-xl-4 col-md-6">
                <div class="skeleton-loading global-list-skeleton"></div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="skeleton-loading global-list-skeleton"></div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="skeleton-loading global-list-skeleton"></div>
            </div>
        `)

        $.ajax({
            url: '/api/v1/global_list/my',
            method: 'GET',
            data: {
                page,
                per_page,
                sorting,
                with_companion,
                visit_with 
            },
            success: (response) => {
                const data = response.data.data
                if (!data || data.length === 0) {
                    renderPagination(1, 1, 0, 0, 0, paginationInfoHolder)
                    renderNoMessageBox(holder, 'global-lists')
                    return
                }

                let htmlItem = ''
                data.forEach(dt => {
                    console.log(dt)
                    const pinListElement = dt.pin_list ? 
                        dt.pin_list.split(", ").map(dt => `<a class="tag bg-primary pin-name me-1 mb-1" data-value="${dt}">${dt}</a>`).join("") 
                        : 
                        '<span class="text-none">- No marker attached -</span>'
                    const visitWithEl = dt.visit_with ? 
                        dt.visit_with.split(", ").map(dt => `<a class="tag bg-primary pin-name me-1 mb-1" data-value="${dt}">${dt}</a>`).join("") 
                        : 
                        '<span class="text-none">- No companion found -</span>'
                    const tagListElement = dt.tag_list ? 
                        dt.tag_list.split(", ").map(dt => `<a class="tag bg-success tag-name me-1 mb-1" data-value="${dt}">#${dt}</a>`).join("") 
                        :
                        '<span class="text-none">- No tags attached -</span>'

                    htmlItem += `
                        <div class="col-xl-4 col-md-6">
                            <div class="card h-100">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex gap-2">
                                        <span class="tag bg-info">
                                            <i class="fa-solid fa-thumbtack"></i> ${dt.total_pin} Marker${dt.total_pin > 1 ? 's':''}
                                        </span>
                                        ${
                                            dt.total_visit > 0 ? `<span class="tag bg-info">
                                                <i class="fa-solid fa-location-dot"></i> ${dt.total_visit} Visit${dt.total_visit > 1 ? 's':''}
                                            </span>` : ''
                                        }
                                    </div>
                                    <button class="icon-btn-sm"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                </div>
                                <h5 class="col-title">${dt.list_name}</h5>
                                <p class="text-secondary text-sm">${dt.list_desc || '<span class="text-none">- No description provided -</span>'}</p>
                                <div class="d-flex flex-column gap-2 mb-3">
                                    <div class="meta-row">
                                        <span class="meta-label"><i class="fa-solid fa-hashtag"></i> Tags</span>
                                        <span>${tagListElement}</span>
                                    </div>
                                    <div class="meta-row">
                                        <span class="meta-label"><i class="fa-solid fa-list"></i> Markers</span>
                                        <span>${pinListElement}</span>
                                    </div>
                                    ${
                                        with_companion === '1' ? `
                                            <div class="meta-row">
                                                <span class="meta-label"><i class="fa-solid fa-users"></i> Companion</span>
                                                <span>${visitWithEl}</span>
                                            </div>`
                                        : ''
                                    }
                                    <div class="d-flex justify-content-between">
                                        <div class="meta-row">
                                            <span class="meta-label">Created At</span>
                                            <span class="meta-text">${datetimeText(dt.created_at, true)}</span>
                                        </div>
                                        ${
                                            dt.updated_at ?
                                                `<div class="meta-row">
                                                    <span class="meta-label">Updated At</span>
                                                    <span class="meta-text">${datetimeText(dt.updated_at, true)}</span>
                                                </div>`
                                            : ''
                                        }
                                    </div>
                                </div>
                                <div class="col-actions">
                                    <a href="/GlobalListController/detail/bolu" class="btn btn-see-more w-100">
                                        See Detail
                                    </a>
                                    <button class="btn btn-outline"><i class="fa-solid fa-share-nodes"></i></button>
                                </div>
                            </div>
                        </div>
                    `
                })
                $(holder).html(htmlItem)

                const companions = response.data.visit_with
                if (companions && companions.length > 0) {
                    $(companionHolder).empty()
                    const companionsSelected = visit_with ? visit_with.split(',') : null
                    companions.forEach(dt => {
                        const name = dt.name
                        $(companionHolder).append(`<span class="filter-chip visit-with text-capitalize ${companionsSelected && companionsSelected.includes(name) ? 'active' : ''}" data-filter="${name}">(${dt.total}) ${name}</span>`)  
                    })
                } else {
                    $(companionHolder).html(`<span class="text-none">- No companions available -</span>`)  
                }

                const totalItem = response.data.total_item
                const totalPage = response.data.total_page
                const startItem = response.data.start_item
                const endItem = response.data.end_item
                renderPagination(page, totalPage, startItem, endItem, totalItem, paginationInfoHolder)
            },
            error: () => {
                $(holder).html(`
                    <div class="empty-state text-danger">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Failed fetch my collection</span>
                    </div>
                `)
            }
        })
    }

    $(document).ready(() => fetchGlobalList())

    $(document).on('click', '.page-btn', function(){
        fetchGlobalList($(this).data('page'))
    })
</script>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(99,91,255,.1);
        border-color: rgba(99,91,255,.3);
    }
    .icon-btn-sm {
        background: none;
        border: none;
        color: #777587;
        font-size: 16px;
        cursor: pointer;
        padding: 4px 6px;
        border-radius: var(--roundedMini);
        transition: all .2s;
        line-height: 1;
    }
    .icon-btn-sm:hover {
        background: #f2f3f7;
        color: var(--primaryColor);
    }
    .col-title {
        font-weight: 800;
        text-transform: capitalize;
    }
    .card:hover .col-title {
        color: var(--primaryColor) !important;
    }
    .meta-row {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .col-actions {
        display: flex;
        gap: var(--spaceMD);
        border-top: 1px solid #f2f3f7;
        padding-top: var(--spaceXMD);
        margin-top: auto;
    }
    .skeleton-loading.global-list-skeleton{
        width: 100%;
        height: 360px;
        border-radius: var(--roundedLG);
    }
</style>