<style>
    .spacer-landing {
        height: 10vh;
    }
</style>
<div class="spacer-landing"></div>
<?php $this->load->view('dashboard/welcome'); ?>
<div class="spacer-landing"></div><br><br>
<?php $this->load->view('dashboard/statistic', $dt_get_stats_total_pin_by_category); ?>
<div class="spacer-landing"></div>
