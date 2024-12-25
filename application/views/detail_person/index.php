<h2 class="text-center" style="font-weight:600;">Detail Person</h2>
<div class="d-flex justify-content-between">
    <a class="btn btn-danger mb-4 <?php if (!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> px-4" href="/PersonController" id="back-page-btn"><i class="fa-solid fa-arrow-left"></i><?php if (!$is_mobile_device){ echo " Back"; } ?></a>
</div>
<h2 class='text-center' style='font-weight:600;'><?= $clean_name ?>
    <span class='bg-dark text-light px-3 py-2 rounded-pill' style='font-size: 16px;'><?= $total_appearance ?> Appearance</span>
</h2>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <?php $this->load->view('detail_person/analyze_time'); ?><hr>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">

    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <?php $this->load->view('detail_person/analyze_visit'); ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <?php $this->load->view('detail_person/analyze_pin'); ?>
    </div>
</div>