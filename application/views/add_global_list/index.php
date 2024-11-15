<h2 class="text-center" style="font-weight:600;">Add List</h2>
<a class="btn btn-danger mb-4 <?php if (!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> px-4" href="/GlobalListController" id="back-page-btn"><i class="fa-solid fa-arrow-left"></i><?php if (!$is_mobile_device){ echo " Back"; } ?></a>
<?php $this->load->view('add_global_list/form'); ?>