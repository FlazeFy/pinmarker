<div class="hero-banner mb-4" id="summary-section">
    <div class="position-relative" style="z-index:1;">
        <h2 class="hero-eyebrow">Summary</h2>
        <div class="row g-3 mt-1">
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Total Markers</div>
                    <div class="stat-value"><?= $dt_count_my_pin->total; ?></div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Favorite Pins</div>
                    <div class="stat-value"><?= $dt_count_my_fav_pin->total; ?></div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Most Visit</div>
                    <div class="stat-name"><?= $dt_get_most_visit->context ?></div>
                    <div class="stat-meta"><?= $dt_get_most_visit->total ?> Total Visits</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stat-chip">
                    <div class="stat-label">Top Category</div>
                    <div class="stat-name"><?= $dt_get_most_category->context ?></div>
                    <div class="stat-meta"><?= $dt_get_most_category->total ?> Markers</div>
                </div>
            </div>
        </div>
    </div>
    <i class="fa-solid fa-compass hero-deco"></i>
    <div class="hero-blur"></div>
</div>