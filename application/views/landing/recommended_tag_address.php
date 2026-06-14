<div class="hero-card-wrap">
    <div class="hero-card">
        <div class="float-label mb-2">Wanna try?</div>
        <div id="recommended_tag_holder" class="d-flex flex-wrap gap-2"></div>
    </div>
    <div class="float-badge">
        <div class="float-icon">
            <i class="fa-solid fa-compass"></i>
        </div>
        <div>
            <div class="float-label">Let's Visit</div>
            <div class="float-value" id="recommended_address_holder"></div>
        </div>
    </div>
</div>

<script>
    const fetchRecommendedTagAddress = () => {
        const tagHolder = '#recommended_tag_holder'
        const addressHolder = '#recommended_address_holder'
        $(tagHolder).html(`
            <div class="skeleton-loading tag-skeleton"></div>
            <div class="skeleton-loading tag-skeleton"></div>
            <div class="skeleton-loading tag-skeleton"></div>
            <div class="skeleton-loading tag-skeleton"></div>
            <div class="skeleton-loading tag-skeleton"></div>
            <div class="skeleton-loading tag-skeleton"></div>
        `)
        $(addressHolder).html(`
            <div class="skeleton-loading address-skeleton"></div>
        `)

        $.ajax({
            url: '/api/v1/global_list/recommended/tag_address',
            method: 'GET',
            success: function(response) {
                const data = response.data
                if (!data.tags || data.tags.length === 0) {
                    $(tagHolder).html(`
                        <div class="empty-state text-danger">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>Failed to fetch recommendation</span>
                        </div>
                    `)
                }
                if (!data.pin_address || data.pin_address.length === 0) {
                    $(addressHolder).html(`<span class="text-none">Failed to fetch recommendation</span>`)
                }

                $(tagHolder).empty()
                data.tags.forEach(dt => {
                    $(tagHolder).append(`<a class="tag bg-primary recommendation-tag-search-marker-btn" data-tag-name="${dt.tag_name}">#${dt.tag_name}</a>`)
                })

                // Pin address with typing effect
                const addresses = data.pin_address
                let currentIndex = 0

                const typeText = (text, callback) => {
                    const el = $(addressHolder)
                    el.text('')
                    let i = 0
                    const interval = setInterval(() => {
                        el.text(el.text() + text[i])
                        i++
                        if (i >= text.length) {
                            clearInterval(interval)
                            if (callback) callback()
                        }
                    }, 50)
                }

                const showNext = () => {
                    typeText(addresses[currentIndex].pin_address)
                    currentIndex = (currentIndex + 1) % addresses.length
                }

                showNext()
                setInterval(showNext, 2000)
            },
            error: () => {
                $(tagHolder).html(`
                    <div class="empty-state text-danger">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Failed to fetch recommendation</span>
                    </div>
                `)
                $(addressHolder).html(`<span class="text-none">Failed to fetch recommendation</span>`)
            }
        })
    }
    fetchRecommendedTagAddress()
</script>

<style>
    .skeleton-loading.tag-skeleton{
        width: 50px;
        height: 12px;
    }
    .skeleton-loading.address-skeleton{
        width: 150px;
        height: 20px;
    }
</style>