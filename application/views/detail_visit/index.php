<h2 class="text-center" style="font-weight:600;">Detail Visit</h2>
<div class="d-flex justify-content-between">
    <a class="btn btn-danger mb-4 <?php if (!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> px-4" href="/HistoryController" id="back-page-btn"><i class="fa-solid fa-arrow-left"></i><?php if (!$is_mobile_device){ echo " Back"; } ?></a>
    <?php $this->load->view('detail_visit/delete'); ?>
</div>
<?php $this->load->view('detail_visit/form'); ?>
<?php $this->load->view('detail_visit/review'); ?>