<?php if (!$is_mobile_device): ?>
    <h2 class="text-center" style="font-weight:600;">Maps</h2>
<?php endif; ?>
<?php $this->load->view('maps/maps_board'); ?>
<?php $this->load->view('maps/legend'); ?>