<div class="card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="card-title">Recent Visit</h3>
        <a href="/HistoryController" class="card-sub">See All</a>
    </div>
    <div class="d-flex flex-column gap-1">
        <?php
            if (count($dt_recent_visit) > 0) {
                foreach ($dt_recent_visit as $dt) {
                    $visit_with_el = $dt->visit_with ? "<span class='tag bg-primary'><i class='fa-solid fa-users'></i> $dt->visit_with</span>" : "";
                    $is_favorite_el = $dt->is_favorite == 1 ? "<span class='fav-dot'><i class='fa-solid fa-heart'></i></span>" : "";
                    $pin_address_el = $dt->pin_address ? "<span class='tag bg-success'><i class='fa-solid fa-location-dot'></i> $dt->pin_address</span>" : "";
                    $pin_list = $dt->list_names ? "<span class='tag bg-info'><i class='fa-solid fa-hashtag'></i> $dt->list_names</span>" : "";
                    $created_at_text = datetime_text($dt->visit_at);

                    echo "
                        <div class='activity-item mb-0'>
                            <div class='activity-thumb'>
                                <img src='https://lh3.googleusercontent.com/aida-public/AB6AXuB9TgxRWJZ1lxyBC2boJYHByBkeSaroy5x0M-AVvRCH_M7rWkJDFoVc1Lykvj4iQd7LMnmKIZdneHmRaXwFC9lv7_R60HRIGCVjEjIOXZfaa7J7VxkudxcVJ4rY3Rgs-ylDPPCviUANS--Z29u4nWUV66EasfeFSHxoNl_DXhJTkoVFcwXP083QKchtEh0pwmn4zKaOzhGVc1BczkDGjALzZe6T1f9_UaS1XcyhLg9yioAdJ4m4iYi-5GEJreWku7OO99GdpvvHlmjT' alt=''>
                                $is_favorite_el
                            </div>
                            <div class='activity-info'>
                                <div class='activity-name'>$dt->pin_name</div>
                                <div class='activity-meta'>$created_at_text • $dt->pin_category</div>
                                <div class='activity-tags'>
                                    $pin_address_el
                                    $pin_list
                                    $visit_with_el
                                </div>
                            </div>
                        </div>
                    ";
                } 
            } else {
                echo "<span class='text-none text-center'>- No visited marker found for last 2 weeks -</span>";
            }
        ?>
    </div>
    <button class="btn-see-more mt-4 w-100">View History Map</button>
</div>