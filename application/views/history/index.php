<h2 class="text-center" style="font-weight:600;">My Visit</h2><br>
<a class="btn btn-success btn-menu-main" href="/AddVisitController" id="add-visit-btn"><i class="fa-solid fa-plus"></i><?php if(!$is_mobile_device){ echo " Add Visit";} ?></a>
<a class="btn btn-dark btn-menu-main" style='bottom:calc(4*var(--spaceXLG));' href="/HistoryController/print_visit" id="print-btn"><i class="fa-solid fa-print"></i><?php if(!$is_mobile_device){ echo " Print";} ?></a>
<?php $this->load->view('history/calendar'); ?>
<hr>
<h2 class="text-center" style="font-weight:600;">My Activity</h2><br>
<?php $this->load->view('history/activity'); ?>