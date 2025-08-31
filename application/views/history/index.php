<a class="btn btn-success btn-menu-main" href="/AddVisitController" id="add-visit-btn"><i class="fa-solid fa-plus"></i><?php if(!$is_mobile_device){ echo " Add Visit";} ?></a>
<a class="btn btn-dark btn-menu-main" style='bottom:calc(4*var(--spaceXLG));' href="/HistoryController/print_visit" id="print-btn"><i class="fa-solid fa-print"></i><?php if(!$is_mobile_device){ echo " Print";} ?></a>
<a class="btn btn-dark btn-menu-main" style='bottom:calc(4*var(--spaceXLG));' href="/PersonController" id="person-btn"><i class="fa-solid fa-user"></i><?php if(!$is_mobile_device){ echo " Persons";} ?></a>
<div class="container-fluid" id="calendar-section">
    <h2>Calendar</h2><hr>
    <?php $this->load->view('history/calendar'); ?>
</div>
<div class="container-fluid" id="activity-section">
    <h2>Activity</h2><hr>
    <?php $this->load->view('history/activity'); ?>
</div>