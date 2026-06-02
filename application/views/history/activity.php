<div class="card mb-4">
    <span class="card-title">Recent Activity</span>
    <div class="activity-scroll">
        <div class="d-flex flex-column gap-1" id="activity-holder"></div>
    </div>
    <button class="btn-see-more mt-4 w-100" id="activity-see-more">
        See More
    </button>
</div>

<script>
    let page = 1
    let totalPage = 1
    let isLoading = false

    const fetchActivity = (append = false) => {
        if (isLoading) return
        isLoading = true
        const holder = '#activity-holder'

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

        $('#activity-see-more').prop('disabled', true).text('Loading...')

        $.ajax({
            url: '/api/v1/history',
            method: 'GET',
            data: { page, per_page: 10 },
            success: (response) => {
                const rows = response.data.data || []
                totalPage = response.data.total_page || 1

                let html = ''
                rows.forEach(dt => {
                    let icon = 'fa-clock-rotate-left'
                    let bg = 'bg-secondary'

                    if (dt.history_type.includes('Marker')) {
                        icon = 'fa-thumbtack'
                        bg = 'bg-primary'
                    }

                    if (dt.history_type.includes('Visit')) {
                        icon = 'fa-location-dot'
                        bg = 'bg-success'
                    }

                    html += `
                        <div class='activity-item mb-0'>
                            <div class='cat-icon ${bg}'>
                                <i class='fa-solid ${icon}'></i>
                            </div>
                            <div class='activity-info'>
                                <div class='activity-name'>${dt.history_context}</div>
                                <div class='activity-meta'>
                                    ${dt.history_type} • ${datetimeText(dt.created_at)}
                                </div>
                            </div>
                        </div>
                    `
                })

                if (append) {
                    $(holder).append(html)
                    const $scroll = $('.activity-scroll')
                    $scroll.animate({
                        scrollTop: $scroll[0].scrollHeight
                    }, 300)
                } else {
                    $(holder).html(html)
                }

                if (page >= totalPage) {
                    $('#activity-see-more').hide()
                } else {
                    $('#activity-see-more').show().prop('disabled', false).text('See More')
                }
            },
            error: () => {
                if (!append) {
                    $(holder).html(`
                        <div class="text-center py-3">
                            <span class="tag bg-danger">
                                <i class="fa-solid fa-triangle-exclamation"></i> Failed fetch activity
                            </span>
                        </div>
                    `)
                }

                $('#activity-see-more').prop('disabled', false).text('See More')
            },
            complete: () => {
                isLoading = false
            }
        })
    }

    $(document).on('click', '#activity-see-more', function() {
        page++
        fetchActivity(true)
    })

    $(document).ready(() => {
        fetchActivity()
    })
</script>

<style>
    .activity-scroll{
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