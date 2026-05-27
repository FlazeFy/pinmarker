<script>
    $(document).ready(function() {
        $('.grid').isotope({
            itemSelector: '.grid-item',
            layoutMode: 'masonry', 
        });
    })
</script>
<?php $this->load->view('login/global'); ?>
<br></br>
<br></br>
<div class="row">
    <div class="col-md-6 col-sm-12 py-4 d-flex flex-column justify-content-center text-center">
        <img class='img img-fluid mx-auto' style="max-width:300px;" src='http://127.0.0.1:8080/public/images/global.png'>
        <h2 class="fw-bold">SHARE YOUR PINNED LOCATION WITH EVERYONE</h2>
        <p class="text-secondary mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Nunc sed blandit libero volutpat sed cras ornare</p>
    </div>
    <div class="col-md-6 col-sm-12 col-12">
        <?php $this->load->view('login/form'); ?>
    </div>
</div>
<br></br>
<br></br>
<div class="row text-center">
    <div class="col-lg-6 col-md-6 col-sm-12 col-12 position-relative py-3">
        <div class="container bg-light-warning text-center" style="padding: var(--spaceJumbo);">
            <img class='img img-fluid mt-3 mx-auto d-block' style="max-height:300px;" src='http://127.0.0.1:8080/public/images/track.png'>
            <h2 class="fw-bold">TRACKING YOUR JOURNEY</h2>
            <p class="text-secondary mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Nunc sed blandit libero volutpat sed cras ornare</p>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-12 p-4 text-start d-flex flex-column justify-content-center">
        <h1 class="fw-bold">SOME FACTS!</h1>
        <p class="text-secondary">Since we had been published to Public. We've gathered and handle about thousand marker and visit from many user</p>
        <div class="row mt-4 text-center">
            <div class="col">
                <div class="container-fluid text-center shadow">
                    <h1 class="mb-0"><?= $dt_total_user[0]->total ?></h1>
                    <h5 class="text-secondary">Total User</h5>
                </div>
            </div>
            <div class="col">
                <div class="container-fluid text-center shadow">
                    <h1 class="mb-0"><?= $dt_total_pin[0]->total ?></h1>
                    <h5 class="text-secondary">Total Marker</h5>
                </div>
            </div>
            <div class="col">
                <div class="container-fluid text-center shadow">
                    <h1 class="mb-0"><?= $dt_total_visit[0]->total ?></h1>
                    <h5 class="text-secondary">Total Visit</h5>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('login/platform'); ?>