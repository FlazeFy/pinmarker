<a class="btn btn-danger mb-4 <?php if (!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> px-4" href="/GlobalListController" id="back-page-btn"><i class="fa-solid fa-arrow-left"></i><?php if (!$is_mobile_device){ echo " Back"; } ?></a>
<div class="container-fluid" id="add_global_list-section">
    <h2>Add Global List</h2><hr>
    <?php $this->load->view('add_global_list/form'); ?>
</div>