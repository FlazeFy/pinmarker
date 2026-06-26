<a class="btn btn-success see-person-btn py-1 text-sm px-2" data-bs-toggle='modal' data-bs-target='#visit-companion-modal'><i class="fa-solid fa-user-plus"></i> Add Person</a>
<div class="modal fade" id="visit-companion-modal" tabindex="-1" aria-labelledby="addGalleriesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Visit Companion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id='close-my-person-modal-btn'></button>
            </div>
            <div class="modal-body">
                <label>Person Name</label>
                <input id="person-search" type="text" class="form-control"/>
                <div class="person-scroll">
                    <div class="row me-1" id="person-holder"></div>
                </div>
                <button class="btn btn-see-more mt-4 w-100 w-100 mt-2 text-sm" id="person-see-more">See More</button>
            </div>
        </div>
    </div>
</div>

<script>
    let page = 1
    let totalPage = 1
    let isLoading = false
    let personFetched = false

    const syncVisitWith = () => {
        const names = $('.person-name.active').map((_, el) => $(el).data('name')).get()
        $('#visit_with').val(names.join(', '))
    }

    const fetchPerson = (append = false) => {
        if (isLoading) return
        isLoading = true
        const holder = '#person-holder'
        let html = ''

        if (!append) {
            $(holder).html(`
                <div class="activity-item">
                    <div class="skeleton-loading activity-line-skeleton"></div>
                </div>
                <div class="activity-item">
                    <div class="skeleton-loading activity-line-skeleton"></div>
                </div>
                <div class="activity-item">
                    <div class="skeleton-loading activity-line-skeleton"></div>
                </div>
            `)
        }

        $('#person-see-more').prop('disabled', true).text('Loading...')

        $.ajax({
            url: '/api/v1/visit/visit_with',
            method: 'GET',
            data: { 
                page, 
                per_page: 30,
                search: $('#person-search').val().trim()
            },
            headers: {
                'Authorization': `Bearer ${tokenKey}`
            },
            success: (response) => {
                const rows = response.data.data || []
                totalPage = response.data.total_page || 1

                const currentNames = $('#visit_with').val().split(',').map(n => n.trim().toLowerCase()).filter(n => n !== '')

                rows.forEach((dt) => {
                    const isActive = currentNames.includes(dt.name.toLowerCase()) ? 'active' : ''
                    html += `
                        <div class="col-md-6 col-sm-12 mb-2">
                            <div class='activity-item w-100 mb-0 person-name ${isActive} shadow p-3 m-2' data-name="${dt.name}">
                                <div class='activity-info'>
                                    <div class='activity-name text-capitalize'>${dt.name}</div>
                                    <div class='activity-meta'>
                                        <b>${dt.total_visit_with} Visit${dt.total_visit_with > 1 ? 's' : ''}</b> • ${dt.last_visit_at}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `
                })

                if (append) {
                    $(holder).append(html)

                    const $scroll = $('.person-scroll')
                    $scroll.animate({
                        scrollTop: $scroll[0].scrollHeight
                    }, 300)
                } else {
                    $(holder).html(html)
                }

                page >= totalPage ? $('#person-see-more').hide() : $('#person-see-more').show().prop('disabled', false).text('See More')
            },
            error: (response) => {
                if (response.status === 401) return failedAuth()
                if (!append) {
                    $(holder).html(`
                        <div class="text-center py-3">
                            <span class="tag bg-danger"><i class="fa-solid fa-triangle-exclamation"></i> Failed fetch person</span>
                        </div>
                    `)
                }

                $('#person-see-more').prop('disabled', false).text('See More')
            },
            complete: () => {
                isLoading = false
            }
        })
    }

    $('#visit-companion-modal').on('show.bs.modal', function() {
        page = 1
        totalPage = 1
        personFetched = false
        fetchPerson()
    })

    $(document).on('click', '#person-see-more', function() {
        page++
        fetchPerson(true)
    })

    $(document).on('click', '.person-name', function() {
        const name = $(this).data('name').toLowerCase()
        const isActive = $(this).hasClass('active')

        $(this).toggleClass('active')

        if (!isActive) {
            const current = $('#visit_with').val().split(',').map(n => n.trim()).filter(n => n !== '')
            const alreadyExists = current.some(n => n.toLowerCase() === name)
            
            if (!alreadyExists) {
                current.push($(this).data('name'))
                $('#visit_with').val(current.join(', '))
            }
        }

        cleanListPerson('.visit-with')
    })    

    let searchDebounce = null
    $(document).on('input', '#person-search', function () {
        clearTimeout(searchDebounce)
        const val = $(this).val().trim()

        searchDebounce = setTimeout(() => {
            val ? addUrlParam('search-person', val) : removeUrlParam('search-person')
            fetchPerson()
        }, debouncerTime)
    })
</script>

<style>
    .person-scroll{
        max-height: 70vh;
        overflow-y: auto;
        padding-right: var(--spaceMini);
    }
    .skeleton-loading.activity-line-skeleton{
        width: 100%;
        height: 50px;
    }
</style>