<h2 class="text-center" style="font-weight:600;">Detail Person</h2>
<div class="d-flex justify-content-between">
    <a class="btn btn-danger mb-4 <?php if (!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> px-4" href="/PersonController" id="back-page-btn"><i class="fa-solid fa-arrow-left"></i><?php if (!$is_mobile_device){ echo " Back"; } ?></a>
</div>

<?php $this->load->view('detail_person/person_profile'); ?>
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