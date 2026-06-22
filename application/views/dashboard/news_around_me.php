<div class="card stats-card--news card-lift position-relative overflow-hidden mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="card-title mb-0 text-white">Whats Around Me</h2>
    </div>
    <div class="d-flex flex-column">
        <div id="newsCarousel" class="carousel slide mt-0" data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-indicators news-indicators" id="newsIndicators"></div>
            <div class="carousel-inner" id="newsItems">
                <div class="carousel-item active">
                    <div class="news-skeleton"></div>
                </div>
            </div>
        </div>
    </div>
    <i class="fa-solid fa-building news-deco"></i>
</div>

<style>
    .stats-card--news {
        background: var(--infoBG);
        color: #fff;
        border-color: transparent;
    }
    .news-eyebrow {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        opacity: .8;
        margin-bottom: 4px;
    }
    .news-title {
        font-size: var(--textMD);
        font-weight: 700;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .news-source {
        font-size: var(--textSM);
        opacity: .75;
        margin-top: 6px;
    }
    .news-date {
        font-size: var(--textSM);
        opacity: .6;
    }
    .news-deco {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 90px;
        opacity: .1;
        pointer-events: none;
    }
    .news-indicators {
        position: static;
        margin-top: 0;
        margin-bottom: var(--spaceSM);
        justify-content: flex-end;
        margin-right: 0;
    }
    .news-indicators [data-bs-target] {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        border: 0;
        margin: 0 4px;
        opacity: .4;
        background: #fff;
    }
    .news-indicators .active {
        opacity: 1;
    }
    .news-link {
        display: inline-block;
        margin-top: 10px;
        font-size: var(--textSM);
        color: #fff;
        opacity: .85;
        text-decoration: underline;
        text-underline-offset: 3px;
    }
    .news-link:hover {
        opacity: 1;
        color: #fff;
    }
    .news-skeleton {
        height: 80px;
        background: rgba(255,255,255,.15);
        border-radius: var(--roundedMD);
        animation: shimmer 1.4s infinite;
    }
    @keyframes shimmer {
        0% { opacity: .4 }
        50% { opacity: .8 }
        100% { opacity: .4 }
    }
</style>

<script>
    const fetchWhatsAroundMe = () => {
        const userLat = getCookie('lat')
        const userLng = getCookie('long')

        $.ajax({
            url: `/api/v1/news/by/coordinate?lat=${userLat}&long=${userLng}`,
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${tokenKey}`
            },
            success: (response) => {
                const news = response.data.news.sort(() => Math.random() - 0.5).slice(0, 10)
                const location = response.data.location

                let indicatorsEl = ''
                let itemsEl = ''

                news.forEach((item, idx) => {
                    const isActive = idx === 0 ? ' active' : ''
                    const activeAttr = idx === 0 ? "class='active'" : ''
                    const publishedDate = new Date(item.published_at).toLocaleDateString('en-US', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    })

                    indicatorsEl += `<button type='button' data-bs-target='#newsCarousel' data-bs-slide-to='${idx}' ${activeAttr}></button>`

                    itemsEl += `
                        <div class='carousel-item${isActive}'>
                            <div class='news-eyebrow'><i class='fa-solid fa-location-dot me-1'></i> ${location}</div>
                            <div class='news-title'>${item.title}</div>
                            <div class='d-flex justify-content-between align-items-center mt-1'>
                                <span class='news-source'>${item.source}</span>
                                <span class='news-date'>${publishedDate}</span>
                            </div>
                            <a href='${item.url}' target='_blank' rel='noopener noreferrer' class='news-link'>Read more <i class='fa-solid fa-arrow-up-right-from-square' style='font-size:10px;'></i></a>
                        </div>
                    `
                })

                $('#newsIndicators').html(indicatorsEl)
                $('#newsItems').html(itemsEl)
            },
            error: () => {
                $('#newsItems').html(`
                    <div class='carousel-item active'>
                        <div style='opacity:.7; font-size:var(--textSM);'>
                            <i class='fa-solid fa-circle-exclamation me-1'></i> Failed to load news
                        </div>
                    </div>
                `)
            }
        })
    }

    fetchWhatsAroundMe()
</script>