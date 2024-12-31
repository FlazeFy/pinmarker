<style>
    .profile-card {
        padding: var(--spaceSM);
        margin-top: var(--spaceLG) !important;
        margin: calc(var(--spaceJumbo) * 2) 0;
        background: <?= $dt_visit_person_summary->ranking <= 3 ? "var(--warningBG)" : "var(--whiteColor)"?>;
        border: 2px solid <?= $dt_visit_person_summary->ranking <= 3 ? "var(--warningBG)" : "var(--whiteColor)"?>;
        max-width: 720px;
        display: block;
        margin-inline: auto;
        border-radius: var(--roundedMD);
        overflow: hidden;
        box-shadow: <?= $dt_visit_person_summary->ranking <= 3 ? "var(--warningBG) 6px 6px" : "var(--primaryColor) 0px 4px"?> 12.5px !important;
        transform: translatey(0px);
        rotate: -1.5deg;
        animation: float 4.0s ease-in-out infinite;
        box-sizing: border-box;
    }
    @keyframes float {
        0% {
            box-shadow: 0 5px 15px 0px rgba(0,0,0,0.6);
            transform: translatey(0px);
        }
        50% {
            box-shadow: 0 25px 15px 0px rgba(0,0,0,0.2);
            transform: translatey(-20px);
        }
        100% {
            box-shadow: 0 5px 15px 0px rgba(0,0,0,0.6);
            transform: translatey(0px);
        }
    }
</style>
<div class="profile-card">
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
        </div>
    </div>
</div>