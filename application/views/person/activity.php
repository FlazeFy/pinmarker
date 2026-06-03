<div class="card mb-4 position-sticky" style="top: 120px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="card-title">All Person</h3>
        <a id="see-all-button" class="card-sub">See All</a>
    </div>
    <div class="person-scroll">
        <div class="d-flex flex-column gap-1" id="person-holder"></div>
    </div>
    <button class="btn-see-more mt-4 w-100" id="person-see-more">See More</button>
</div>

<script>
    let page = 1
    let totalPage = 1
    let isLoading = false

    const fetchPerson = (append = false) => {
        if (isLoading) return
        isLoading = true
        const holder = '#person-holder'

        if (!append) {
            $(holder).html(`
                <div class="activity-item">
                    <div class="skeleton-loading activity-icon-skeleton"></div>
                    <div class="flex-grow-1">
                        <div class="skeleton-loading activity-line-skeleton mb-2"></div>
                        <div class="skeleton-loading activity-subline-skeleton"></div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="skeleton-loading activity-icon-skeleton"></div>
                    <div class="flex-grow-1">
                        <div class="skeleton-loading activity-line-skeleton mb-2"></div>
                        <div class="skeleton-loading activity-subline-skeleton"></div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="skeleton-loading activity-icon-skeleton"></div>
                    <div class="flex-grow-1">
                        <div class="skeleton-loading activity-line-skeleton mb-2"></div>
                        <div class="skeleton-loading activity-subline-skeleton"></div>
                    </div>
                </div>
            `)
        }

        $('#person-see-more').prop('disabled', true).text('Loading...')

        $.ajax({
            url: '/api/v1/visit/visit_with',
            method: 'GET',
            data: { page, per_page: 10 },
            success: (response) => {
                const rows = response.data.data || []
                totalPage = response.data.total_page || 1
                let html = ''

                // Keep global state between pages
                if (!append) {
                    window.personPreviousTotal = null
                    window.personRank = 0
                }

                rows.forEach((dt) => {
                    // Different score = increment rank
                    if (dt.total_visit_with !== window.personPreviousTotal) window.personRank++
                    window.personPreviousTotal = dt.total_visit_with

                    let bg = window.personRank === 1 ? 'bg-golden' : window.personRank === 2 ? 'bg-silver' : window.personRank === 3 ? 'bg-bronze' : 'bg-secondary'

                    html += `
                        <div class='activity-item mb-0'>
                            <div class='cat-icon ${bg}'>
                                #${window.personRank}
                            </div>
                            <div class='activity-info'>
                                <div class='activity-name text-capitalize'>${dt.name}</div>
                                <div class='activity-meta'>
                                    <b>${dt.total_visit_with} Visit${dt.total_visit_with > 1 ? 's':''}</b> • ${dt.last_visit_at}
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
            error: () => {
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

    $(document).on('click', '#person-see-more', function() {
        page++
        fetchPerson(true)
    })

    $(document).on('click', '.activity-item', function() {
        $('.activity-item').removeClass('active')
        $(this).toggleClass('active')
        $('#all-person-section').addClass('d-none')
        $('#single-person-section').removeClass('d-none')
    })

    $(document).on('click', '#see-all-button', function() {
        $('.activity-item').removeClass('active')
        $('#all-person-section').removeClass('d-none')
        $('#single-person-section').addClass('d-none')
    })

    $(document).ready(() => {
        fetchPerson()
    })
</script>

<style>
    .person-scroll{
        max-height: 70vh;
        overflow-y: auto;
        padding-right: var(--spaceMini);
    }
    .skeleton-loading.activity-icon-skeleton{
        width: 42px;
        height: 42px;
        border-radius: var(--roundedMD);
        flex-shrink: 0;
    }
    .skeleton-loading.activity-line-skeleton{
        width: 180px;
        height: 14px;
    }
    .skeleton-loading.activity-subline-skeleton{
        width: 120px;
        height: 12px;
    }
</style>