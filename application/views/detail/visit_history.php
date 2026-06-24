<div class="card h-100">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="card-title mb-0">Visit History</h3>
        <span class="py-2 px-4 tag text-md bg-primary" id="total-visit-text"></span>
    </div>
    <div class="d-flex flex-column gap-1 pe-1" id="visit-holder" style='overflow-y:auto; max-height: 350px;'></div>
    <button class="btn-see-more mt-auto w-100" id="visit-see-more-button">See More</button>
</div>

<script>
    let page = 1
    let totalPage = 1
    let isLoading = false

    const fetchVisit = (append = false) => {
        if (isLoading) return
        isLoading = true
        const holder = '#visit-holder'

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

        $('#visit-see-more-button').prop('disabled', true).text('Loading...')

        const id = "<?= $dt_detail_pin->id ?>"
        $.ajax({
            url: `/api/v1/visit/by_pin/${id}`,
            method: 'GET',
            data: { page, per_page: 10 },
            headers: {
                'Authorization': `Bearer ${tokenKey}`
            },
            success: (response) => {
                const rows = response.data.data || []
                totalPage = response.data.total_page || 1
                $('#total-visit-text').text(`${response.data.total_item} Visit`)

                if (rows.length === 0) {
                    $(holder).html(`<span class='text-none text-center'>- No visit history on this marker -</span>`)
                    $('#visit-see-more-button').hide()
                    return
                }

                let html = ''
                rows.forEach(dt => {
                    html += `
                        <div class='activity-item mb-0'>
                            <div class='activity-thumb'>
                                <img src='https://lh3.googleusercontent.com/aida-public/AB6AXuB9TgxRWJZ1lxyBC2boJYHByBkeSaroy5x0M-AVvRCH_M7rWkJDFoVc1Lykvj4iQd7LMnmKIZdneHmRaXwFC9lv7_R60HRIGCVjEjIOXZfaa7J7VxkudxcVJ4rY3Rgs-ylDPPCviUANS--Z29u4nWUV66EasfeFSHxoNl_DXhJTkoVFcwXP083QKchtEh0pwmn4zKaOzhGVc1BczkDGjALzZe6T1f9_UaS1XcyhLg9yioAdJ4m4iYi-5GEJreWku7OO99GdpvvHlmjT' alt=''>
                            </div>
                            <div class='activity-info'>
                                <div class='activity-name'>Visit at ${datetimeText(dt.created_at)}</div>
                                <div class='activity-meta'>${dt.visit_desc ?? `<span class="text-none">- No description provided -</span>`}</div>
                                <div class='activity-tags'>
                                    <span class='tag bg-primary'><i class='fa-solid fa-users'></i> ${dt.visit_with}</span>
                                    <span class='tag bg-info'><i class='fa-solid fa-car'></i> ${dt.visit_by}</span>
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
                    $('#visit-see-more-button').hide()
                } else {
                    $('#visit-see-more-button').show().prop('disabled', false).text('See More')
                }
            },
            error: (response) => {
                if (response.status === 401) return failedAuth()
                if (!append) {
                    $(holder).html(`
                        <div class="text-center py-3">
                            <span class="tag bg-danger">
                                <i class="fa-solid fa-triangle-exclamation"></i> Failed fetch visit
                            </span>
                        </div>
                    `)
                }

                $('#visit-see-more-button').prop('disabled', false).text('See More')
            },
            complete: () => {
                isLoading = false
            }
        })
    }

    $(document).on('click', '#visit-see-more-button', function() {
        page++
        fetchVisit(true)
    })

    $(document).ready(() => {
        fetchVisit()
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