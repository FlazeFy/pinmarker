<h2 class="text-center" style="font-weight:600;">Add Marker</h2>
<a class="btn btn-danger mb-4 <?php if (!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> px-4" href="/MapsController" id="back-page-btn"><i class="fa-solid fa-arrow-left"></i><?php if (!$is_mobile_device){ echo " Back"; } ?></a>
<a class="btn btn-dark mb-4 <?php if (!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> px-4" id="import-pin-modal-btn" data-bs-toggle="modal" data-bs-target="#importMarker"><i class="fa-solid fa-upload"></i> Import Marker</a>
<span id="imported_map_btn_holder"></span>
<?php $this->load->view('add/form'); ?>