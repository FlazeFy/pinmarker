<div class="card h-100">
    <h3 class="card-title">Reviews</h3>
    <div class="d-flex flex-column gap-1 pe-1" id="reviews-holder" style='overflow-y:auto; max-height: 320px;'></div>
    <button class="btn-see-more mt-auto w-100" id="reviews-see-more-button">See More</button>
</div>

<script>
    const getReviews = (data) => {
        const holder = '#reviews-holder'
        $(holder).empty()

        data = data.data
        if (data && data.length > 0) {
            $('#reviews-see-more-button').removeClass('d-none')
            data.forEach(dt => {
                const rate = dt.review_rate
                const bg = rate >= 4 ? 'bg-success' : rate >= 2 ? 'bg-warning' : 'bg-danger'

                $(holder).append(`
                    <div class='activity-item mb-0'>
                        <div class='cat-icon ${bg} fw-bold'>
                            <i class='fa-solid fa-star'></i> ${rate}
                        </div>
                        <div class='activity-info'>
                            <div class='activity-name'>${dt.pin_name}</div>
                            <div class='activity-meta'>${datetimeText(dt.created_at, true)} • ${dt.pin_category}</div>
                            <div class='activity-meta'>${dt.review_body ?? "<span class='text-secondary fst-italic'>- No description provided -</span>"}</div>
                        </div>
                    </div>
                `)  
            })
        } else {
            generateNoData(holder, 'No review has been made from this person')
            $('#reviews-see-more-button').addClass('d-none')
        }
    }
</script>