<a class="btn btn-danger mb-4 <?php if (!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> px-4" href="/HistoryController" id="back-page-btn"><i class="fa-solid fa-arrow-left"></i><?php if (!$is_mobile_device){ echo " Back"; } ?></a>
<div class="container-fluid" id="add_visit-section">
    <h2>Add Visit</h2><hr>
    <?php $this->load->view('add_visit/form'); ?>
</div>