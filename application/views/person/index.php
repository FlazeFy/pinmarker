<h2 class="text-center" style="font-weight:600;">Persons</h2><br>
<a class="btn btn-danger mb-4 <?php if (!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> px-4 me-2" href="/HistoryController" id='back-page-btn'><i class="fa-solid fa-arrow-left"></i><?php if (!$is_mobile_device){ echo " Back"; } ?></a>
<?php $this->load->view('person/top_person_journey'); ?><br>
<?php $this->load->view('person/table'); ?>