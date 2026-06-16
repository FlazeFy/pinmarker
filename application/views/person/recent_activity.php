<div class="card h-100">
    <h2 class="card-title">Recent Visit</h2>
    <div class="d-flex flex-column gap-1 pe-1" id="recent-visit-holder" style='overflow-y:auto; max-height: 320px;'></div>
    <button class="btn-see-more mt-auto w-100">See More</button>
</div>

<script>
    const getRecentVisit = (data) => {
        const holder = '#recent-visit-holder'
        $(holder).empty()
        data.data.forEach(dt => {
            $(holder).append(`
                <div class='activity-item mb-0'>
                    <div class='activity-thumb'>
                        <img src='https://lh3.googleusercontent.com/aida-public/AB6AXuB9TgxRWJZ1lxyBC2boJYHByBkeSaroy5x0M-AVvRCH_M7rWkJDFoVc1Lykvj4iQd7LMnmKIZdneHmRaXwFC9lv7_R60HRIGCVjEjIOXZfaa7J7VxkudxcVJ4rY3Rgs-ylDPPCviUANS--Z29u4nWUV66EasfeFSHxoNl_DXhJTkoVFcwXP083QKchtEh0pwmn4zKaOzhGVc1BczkDGjALzZe6T1f9_UaS1XcyhLg9yioAdJ4m4iYi-5GEJreWku7OO99GdpvvHlmjT' alt=''>
                        ${dt.is_favorite ? `<span class='fav-dot'><i class='fa-solid fa-heart'></i></span>` : '' }
                    </div>
                    <div class='activity-info'>
                        <div class='activity-name'>${dt.pin_name}</div>
                        <div class='activity-meta'>${datetimeText(dt.visit_at)} • ${dt.pin_category}</div>
                        <div class='activity-tags'>
                            <span class='tag bg-success'><i class='fa-solid fa-location-dot'></i> ${dt.pin_address}</span>
                            <span class='tag bg-primary'><i class='fa-solid fa-users'></i> ${dt.visit_with}</span>
                        </div>
                    </div>
                </div>
            `)
        })
    }
</script>