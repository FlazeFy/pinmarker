<?php $this->load->view('dashboard/welcome'); ?>

<h2 class="text-center" style="font-weight:600;">Summary</h2>
<?php $this->load->view('dashboard/dash'); ?>
<hr>

<h2 class="text-center" style="font-weight:600;">Statistic</h2>
<?php $this->load->view('dashboard/statistic', $dt_get_stats_total_pin_by_category); ?>