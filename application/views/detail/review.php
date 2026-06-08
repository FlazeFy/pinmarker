<div class="card h-100">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="card-title">Reviews</h3>
        <span class="py-2 px-4 tag text-md bg-primary" id="total-review-text"></span>
    </div>
    <div class="d-flex flex-column gap-1 pe-1" id="review-holder" style='overflow-y:auto; max-height: 320px;'></div>
    <button class="btn-see-more mt-auto w-100" id="review-see-more-button">See More</button>
</div>

<script>
    const fetchReview = (append = false) => {
        let page = 1
        let totalPage = 1
        let isLoading = false
        
        if (isLoading) return
        isLoading = true
        const holder = '#review-holder'

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

        $('#review-see-more-button').prop('disabled', true).text('Loading...')

        const id = "<?= $dt_detail_pin->id ?>"
        $.ajax({
            url: `/api/v1/review/${id}`,
            method: 'GET',
            data: { page, per_page: 10 },
            success: (response) => {
                const rows = response.data.data || []
                totalPage = response.data.total_page || 1
                $('#total-review-text').text(`${response.data.total_item} Review`)

                let html = ''
                rows.forEach(dt => {
                    const rate = dt.review_rate
                    const bg = rate >= 4 ? 'bg-success' : rate >= 2 ? 'bg-warning' : 'bg-danger'

                    html += `
                        <div class='activity-item mb-0'>
                            <div class='cat-icon ${bg} fw-bold'>
                                <i class='fa-solid fa-star'></i> ${rate}
                            </div>
                            <div class='activity-info'>
                                <div class='activity-name'>${dt.review_person}</div>
                                <div class='activity-meta'>${datetimeText(dt.created_at, true)}</div>
                                <div class='activity-meta'>${dt.review_body ?? "<span class='text-secondary fst-italic'>- No description provided -</span>"}</div>
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
                    $('#review-see-more-button').hide()
                } else {
                    $('#review-see-more-button').show().prop('disabled', false).text('See More')
                }
            },
            error: () => {
                if (!append) {
                    $(holder).html(`
                        <div class="text-center py-3">
                            <span class="tag bg-danger">
                                <i class="fa-solid fa-triangle-exclamation"></i> Failed fetch review
                            </span>
                        </div>
                    `)
                }

                $('#review-see-more-button').prop('disabled', false).text('See More')
            },
            complete: () => {
                isLoading = false
            }
        })

        $(document).on('click', '#review-see-more-button', function() {
            page++
            fetchReview(true)
        })
    }

    $(document).ready(() => {
        fetchReview()
    })
</script>