<script>
    $(document).ready(function() {
        $('.grid').isotope({
            itemSelector: '.grid-item',
            layoutMode: 'masonry', 
        });
    })
</script>
<?php $this->load->view('login/global'); ?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-12 position-relative">
        <?php if (!$is_mobile_device): ?>
            <a class="position-absolute btn btn-dark px-4 py-2" href="#login-section" id='login-section-btn' style="left:40%; top:10px;"><i class="fa-solid fa-arrow-down"></i> Go to Login</a>
            <div class="mx-auto" style="border: var(--spaceMini) solid black; width:30px; height:85vh; margin-top:-20px;"></div>
        <?php else: ?>
            <hr>
        <?php endif; ?>
        <div class="position-relative">                
            <?php $this->load->view('login/form'); ?>
            <?php if (!$is_mobile_device): ?>
                <div style="border: var(--spaceMini) solid black; width:120%; height:30px; margin-top:-45%; margin-left:20%; z-index:97;"></div>
            <?php endif; ?>
        </div>          
    </div>
    <?php if ($is_mobile_device): ?>
        <br><hr><br>
    <?php endif; ?>
    <div class="col py-4 text-center">
        <img class='img img-fluid mt-3' style="height:40%;" src='http://127.0.0.1:8080/public/images/global.png'>
        <h2 class="fw-bold">SHARE YOUR PINNED LOCATION WITH EVERYONE</h2>
        <p class="text-secondary mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Nunc sed blandit libero volutpat sed cras ornare</p>
        <?php $this->load->view('login/socmed')?>
    </div>
</div>
<div class="row text-center">
    <div class="col-lg-6 col-md-6 col-sm-12 col-12 position-relative py-3">
        <br><br>
        <img class='img img-fluid mt-3 mx-auto d-block' style="height:40%;" src='http://127.0.0.1:8080/public/images/track.png'>
        <h2 class="fw-bold">TRACKING YOUR JOURNEY</h2>
        <p class="text-secondary mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Nunc sed blandit libero volutpat sed cras ornare</p>

        <br><br>
        <h2 class="fw-bold" style="margin-top:10vh;">SOME FACTS!</h2>
        <div class="row mt-4">
            <div class="col">
                <h2 class="mb-0"><?= $dt_total_user[0]->total ?></h2>
                <h5>Total User</h5>
            </div>
            <div class="col">
                <h2 class="mb-0"><?= $dt_total_pin[0]->total ?></h2>
                <h5>Total Marker</h5>
            </div>
            <div class="col">
                <h2 class="mb-0"><?= $dt_total_visit[0]->total ?></h2>
                <h5>Total Visit</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-12 pb-4">
        <?php if (!$is_mobile_device): ?>
            <div class="mx-auto" style="border: var(--spaceMini) solid black; width:30px; height:95vh; margin-top:-30%;"></div>
        <?php endif; ?>
        <?php $this->load->view('login/feedback'); ?>
    </div>
</div>
<?php $this->load->view('login/platform'); ?>