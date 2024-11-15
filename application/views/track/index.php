<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#tb_history_track').DataTable();
    });
</script>
<h2 class="text-center" style="font-weight:600;">Tracking <?php if($this->session->userdata('filter_date_track') == null){ echo '<span class="btn bg-danger text-white px-3 py-1" style="font-size:16px; margin-top:-6px;"><i class="fa-solid fa-satellite-dish"></i> Now Live</span>'; }?></h2><br>
<?php if (!$is_mobile_device): ?>
    <div class="d-flex justify-content-start">
        <a class="btn btn-dark mb-4 py-3 px-4 me-2" data-bs-toggle="modal" data-bs-target="#historyTrackModal" onclick="getHistoryTrack()" id='detail-track-btn'><i class="fa-solid fa-table"></i> Detail</a>
        <a class="btn btn-dark mb-4 py-3 px-4 me-2" data-bs-toggle="modal" data-bs-target="#relatedPinTrackModal" id='related-track-btn'><i class="fa-solid fa-table"></i> Related Pin x Track</a>
        <form action="TrackController/reset_filter_date" method="POST">
            <button class="btn btn-danger py-3 px-4 me-2 mb-4" id='reset-filter-btn'><i class="fa-solid fa-rotate"></i> Reset</button>
        </form>
        <?php $this->load->view('track/day_route'); ?>
        <?php $this->load->view('track/view_mode'); ?>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-4 p-0 ps-2">
            <a class="btn btn-dark mb-2 p-2 w-100" data-bs-toggle="modal" data-bs-target="#historyTrackModal" onclick="getHistoryTrack()" id='detail-track-btn'><i class="fa-solid fa-table"></i> Detail</a>
        </div>
        <div class="col-5 px-1">
            <a class="btn btn-dark mb-2 p-2 w-100" data-bs-toggle="modal" data-bs-target="#relatedPinTrackModal" id='related-track-btn'><i class="fa-solid fa-table"></i> Pin x Track</a>
        </div>
        <div class="col-2 p-0">
            <form action="TrackController/reset_filter_date" method="POST">
                <button class="btn btn-danger p-2 mb-2 w-100" id='reset-filter-btn'><i class="fa-solid fa-rotate"></i></button>
            </form>
        </div>
        <div class="col-6">
            <?php $this->load->view('track/day_route'); ?>
        </div>
        <div class="col-6">
            <?php $this->load->view('track/view_mode'); ?>
        </div>
    </div>
<?php endif; ?>
<?php $this->load->view('track/history_track'); ?>
<?php $this->load->view('track/related_pin_track'); ?>
<?php $this->load->view('track/maps_board'); ?>