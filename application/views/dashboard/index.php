<?php $this->load->view('dashboard/welcome'); ?>

<h2 class="text-center" style="font-weight:600;">Summary</h2>
<?php $this->load->view('dashboard/dash'); ?>
<hr>

<div class="row mb-4">
    <div class="col-lg-7 col-md-6 col-sm-12 col-12">
        <h2 class="text-center mt-3" style="font-weight:600;">Statistic</h2>
    </div>
    <div class="col-lg-5 col-md-6 col-sm-12 col-12">
        <?php $this->load->view('dashboard/control_panel'); ?>
    </div>
</div>

<?php $this->load->view('dashboard/statistic', $dt_get_stats_total_pin_by_category); ?>