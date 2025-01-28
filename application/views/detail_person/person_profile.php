<div class="container bordered p-3 bg-white">
    <h2 style='font-weight:600;'>
    <?= 
        $dt_visit_person_summary->ranking == 1 ? "<i class='fa-solid fa-crown text-warning'></i>" 
        : ($dt_visit_person_summary->ranking <= 3 ? "<i class='fa-solid fa-star text-warning'></i>" : "") ?>
    <?= $clean_name ?></h2>
    <span class='bg-dark text-light px-3 py-2 rounded-pill text-center' style='font-size: 16px;'><?= $total_appearance ?> Appearance</span>
    <br><hr class="mt-4">
    <div class="d-flex justify-content-between">
        <div>
            <h5 class="fw-bold mb-0">First Trip</h5>
            <h6 class="date-target"><?= $dt_visit_person_summary->first_trip ?></h6>
        </div>
        <div class="text-end">
            <h5 class="fw-bold mb-0">Last Trip</h5>
            <h6 class="date-target"><?= $dt_visit_person_summary->last_trip ?></h6>
        </div>
    </div>
    <div class="d-flex justify-content-between">
        <div>
            <h5 class="fw-bold mb-0">Ranking</h5>
            <h6>#<?= $dt_visit_person_summary->ranking ?></h6>
        </div>
        <div class="text-center">
            <h5 class="fw-bold mb-0">Favorite Hour</h5>
            <h6><?= $dt_visit_person_summary->favorite_hour_context ?>:00-<?= $dt_visit_person_summary->favorite_hour_context+1 ?>:00 with <?= $dt_visit_person_summary->favorite_hour_total ?> visit</h6>
        </div>
        <div class="text-end">
            <h5 class="fw-bold mb-0">Favorite Category</h5>
            <h6><?= $dt_visit_person_summary->most_visited_category ?></h6>
        </div>
    </div>
    <div class="d-flex justify-content-between">
        <div>
            <h5 class="fw-bold mb-0">Visit Trends</h5>
            <h6 class="mt-1 <?= $dt_visit_trends > 0 ? "text-success" : ($dt_visit_trends < 0 ? "text-danger" : "text-secondary") ?>">
            <i class="fa-solid <?= $dt_visit_trends > 0 ? "fa-arrow-trend-up" : ($dt_visit_trends < 0 ? "fa-arrow-trend-down" : "fa-arrow-right") ?> fa-xl"></i> 
            <span style="font-weight:900; font-size:var(--textJumbo);"><?= $dt_visit_trends ?>% in <?= date('M Y', strtotime("first day of -1 month")); ?></span></h6>
        </div>
        <div class="text-end">
            <h5 class="fw-bold mb-0"><a class="bordered text-dark px-2 py-1" title="See Detail" data-bs-toggle="collapse" href="#collapseReview" style="cursor:pointer; font-size:var(--textMD);"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></a> Visit Review</h5>
            <h6><?= count($dt_review_history) ?> </h6>
        </div>
    </div>
    <?php $this->load->view('detail_person/person_review'); ?>
</div>