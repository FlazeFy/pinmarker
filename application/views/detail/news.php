<div class="card" style="max-height: 70vh;">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="card-title mb-0">News</h3>
        <span class="py-2 px-4 tag text-md bg-primary" id="total-news-text"></span>
    </div>
    <div class="d-flex flex-column gap-1 pe-1" id="news-holder" style='overflow-y:auto; max-height: auto;'></div>
    <button class="btn-see-more mt-auto w-100" id="news-see-more-button">See More</button>
</div>

<script>
    const fetchNews = (append = false) => {
        let page = 1
        let totalPage = 1
        let isLoading = false
        
        if (isLoading) return
        isLoading = true
        const holder = '#news-holder'

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

        $('#news-see-more-button').prop('disabled', true).text('Loading...')

        const id = "<?= $dt_detail_pin->id ?>"
        $.ajax({
            url: `/api/v1/news/${id}`,
            method: 'GET',
            data: { page, per_page: 10 },
            success: (response) => {
                const rows = response.data.data || []
                totalPage = response.data.total_page || 1
                $('#total-news-text').text(`${response.data.total_item} News`)

                if (rows.length === 0) {
                    $(holder).html(`<span class='text-none text-center'>- No news on this marker -</span>`)
                    $('#news-see-more-button').hide()
                    return
                }

                let html = ''
                rows.forEach(dt => {

                    html += `
                        <div class='activity-item mb-0 news-item' data-news-url="${dt.news_url}">
                            <div class='activity-info'>
                                <div class='tag bg-info mb-1'>${dt.news_source}</div>
                                <div class='activity-name' style='line-clamp: 3;'>${dt.news_title}</div>
                                <div class='activity-meta'>Published at ${datetimeText(dt.published_at, true)}</div>
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
                    $('#news-see-more-button').hide()
                } else {
                    $('#news-see-more-button').show().prop('disabled', false).text('See More')
                }
            },
            error: () => {
                if (!append) {
                    $(holder).html(`
                        <div class="text-center py-3">
                            <span class="tag bg-danger">
                                <i class="fa-solid fa-triangle-exclamation"></i> Failed fetch news
                            </span>
                        </div>
                    `)
                }

                $('#news-see-more-button').prop('disabled', false).text('See More')
            },
            complete: () => {
                isLoading = false
            }
        })

        $(document).on('click', '#news-see-more-button', function() {
            page++
            fetchNews(true)
        })

        $(document).on('click', '.news-item', function() {
            const url = $(this).data('news-url')
            window.open(url, '_blank')
        })
    }

    $(document).ready(() => {
        fetchNews()
    })
</script>