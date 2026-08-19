<div class="card mb-4">
    <h4><?= date('d M Y') ?></h4>
    <div class='row mt-2' id="visit-history-holder"></div>
</div>

<script>
    const date = "<?= date('d-m-Y') ?>";

    const fetchVisitByDate = (date) => {
        const holder = '#visit-history-holder'

        $.ajax({
            url: `/api/v1/visit/by_date/${date}`,
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${tokenKey}`
            },
            beforeSend: () => {
                $(holder).html(`
                    <div class='col-lg-4 col-md-6 col-sm-12 mb-2'>
                        <div class="skeleton-loading visit-history-line-skeleton mb-2"></div>
                    </div>
                    <div class='col-lg-4 col-md-6 col-sm-12 mb-2'>
                        <div class="skeleton-loading visit-history-line-skeleton mb-2"></div>
                    </div>
                    <div class='col-lg-4 col-md-6 col-sm-12 mb-2'>
                        <div class="skeleton-loading visit-history-line-skeleton mb-2"></div>
                    </div>
                `)
            },
            success: (response) => {
                $(holder).empty()
                const data = response.data
                
                if (data.length === 0) {
                    $(holder).html(`
                        <div class='text-center text-secondary'>
                            <img class='img img-fluid m-1' style='max-width:200px;' src='http://127.0.0.1:8080/public/images/empty_item.png'>
                            <h6>No visit history found</h6>
                        </div>
                    `)
                    return
                }

                data.forEach(dt => {
                    $(holder).append(`
                        <div class='col-lg-4 col-md-6 col-sm-12 mb-2'>
                            <div class="marker-card card-lift">
                                <div class="marker-info">
                                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                        <h4 class="marker-name">Visit on ${dt.pin_name} using ${dt.visit_by}</h4>
                                    </div>
                                    <p class="marker-desc">
                                        ${dt.visit_desc || '<span class="text-none">- No description provided -</span>'}
                                    </p>
                                    <hr>
                                    <div class="d-flex gap-4 mt-2 flex-wrap">
                                        <div class="marker-meta-col">
                                            <span class="meta-label">Visit At</span>
                                            <span class="meta-val">
                                                ${dt.created_at}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `)
                })
            },
            error: (response) => {
                if (response.status === 401) return failedAuth()
                $(holder).html(`
                    <div class="text-center py-3">
                        <span class="tag bg-danger">
                            <i class="fa-solid fa-triangle-exclamation"></i> Failed fetch visit history
                        </span>
                    </div>
                `)
            }
        })
    }
    fetchVisitByDate(date)
</script>

<style>
    .skeleton-loading.visit-history-line-skeleton{
        width: 100%;
        height: 80px;
    }
</style>