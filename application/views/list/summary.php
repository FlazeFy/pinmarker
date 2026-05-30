<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card card-lift">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stats-icon" style="background:#e2dfff; color:var(--primaryColor);">
                    <i class="fa-solid fa-map-pin"></i>
                </div>
                <span class="tag <?= $dt_get_total_pin->growth_percentage < 0 ? "bg-danger" : "bg-success" ?>"><i class="fa-solid fa-arrow-trend-<?= $dt_get_total_pin->growth_percentage < 0 ? "down" : "up" ?>"></i> <?= $dt_get_total_pin->growth_percentage ?>%</span>
            </div>
            <div class="stats-label">Total Markers</div>
            <div class="stats-val" style="color:var(--primaryColor);"><?= $dt_get_total_pin->total ?></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-lift">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stats-icon" style="background:#ffdcc2; color:#8a4b00;">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <span class="badge-muted">Top Category</span>
            </div>
            <div class="stats-label"><?= $dt_get_most_category->context ?></div>
            <div class="stats-val"><?= $dt_get_most_category->total ?></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-lift">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stats-icon" style="background:#e2e1f1; color:#5d5d6b;">
                    <i class="fa-regular fa-calendar"></i>
                </div>
                <span class="badge-muted">This Month</span>
            </div>
            <div class="stats-label">Recent Visits</div>
            <div class="stats-val"><?= $dt_get_total_visit_current_month ?></div>
        </div>
    </div>
</div>

<style>
    .card {
        height: 100%;
    }
</style>