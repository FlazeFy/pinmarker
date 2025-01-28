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

<h2 class="text-center" style="font-weight:600;">Detail Person</h2>
<div class="d-flex justify-content-start">
    <a class="btn btn-danger mb-4 me-2 <?php if (!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> px-4" href="/PersonController" id="back-page-btn"><i class="fa-solid fa-arrow-left"></i><?php if (!$is_mobile_device){ echo " Back"; } ?></a>
    <a class="btn btn-dark btn-menu-main" href="/DetailPersonController/print_visit/<?= $raw_name?>" style='bottom:calc(7*var(--spaceXLG));' id='print-btn'><i class="fa-solid fa-print"></i><?php if(!$is_mobile_device){ echo " Print Visit";} ?></a>
</div>

<div class="row">
    <div class="col-lg-8 col-md-12 col-sm-12 col-12">
        <div class="profile-card">
            <?php $this->load->view('detail_person/person_profile'); ?>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12 col-12">
        <div class="profile-card">
            <?php $this->load->view('detail_person/filter_chart'); ?>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
        <?php $this->load->view('detail_person/day_history'); ?><hr>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
        <?php $this->load->view('detail_person/visited_pin_category'); ?><hr>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
        <?php $this->load->view('detail_person/visited_pin_favorite'); ?><hr>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 col-12">
        <?php $this->load->view('detail_person/favorite_tag'); ?><hr>
    </div>
    <div class="col-lg-9 col-md-8 col-sm-12 col-12">
        <?php $this->load->view('detail_person/daily_hour_visit'); ?><hr>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <?php $this->load->view('detail_person/monthly_person'); ?><hr>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <?php $this->load->view('detail_person/analyze_time'); ?><hr>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <?php $this->load->view('detail_person/maps_history'); ?><hr>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <?php $this->load->view('detail_person/analyze_visit'); ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <?php $this->load->view('detail_person/analyze_pin'); ?>
    </div>
</div>