<div class="card">
    <div class="d-flex justify-content-between">
        <h2><i class="fa-solid fa-circle-info"></i> Location Identity</h2>
        <div class="d-flex gap-2 align-items-center">
            <?php $this->load->view("detail/favorite_toggle"); ?>
            <span class="align-self-center py-2 px-4 tag text-md bg-<?= $dt_detail_pin->pin_color ?? 'secondary'?>">
                <?= $dt_detail_pin->pin_category ?>
            </span>
        </div>
    </div>
    <div class="row mt-4 text-start">
        <div class="col-lg-4 pb-2">
            <label class="mb-0">Latitude</label>
            <p class="mb-0 fw-bold"><?= $dt_detail_pin->pin_lat ?></p>
        </div>
        <div class="col-lg-4 pb-2">
            <label class="mb-0">Longitude</label>
            <p class="mb-0 fw-bold"><?= $dt_detail_pin->pin_long ?></p>
        </div>
        <div class="col-lg-4 pb-2">
            <label class="mb-0">Address</label>
            <p class="mb-0 fw-bold"><?= $dt_detail_pin->pin_address ?></p>
        </div>
        <div class="col-lg-4 pb-2">
            <label class="mb-0">Person in Touch</label>
            <?php
                if($dt_detail_pin->pin_person != null){ 
                    echo "<p class='mb-0 fw-bold'>$dt_detail_pin->pin_person</p>";
                } else {
                    echo "<p class='mb-0 text-none'>- Not provided -</p>";
                }
            ?>
        </div>
        <div class="col-lg-4 pb-2">
            <label class="mb-0">Email</label>
            <?php
                if($dt_detail_pin->pin_email != null){ 
                    echo "<p class='mb-0 fw-bold'>$dt_detail_pin->pin_email</p>";
                } else {
                    echo "<p class='mb-0 text-none'>- Not provided -</p>";
                }
            ?>
            <p class="mb-0 fw-bold"><?= $dt_detail_pin->pin_email ?></p>
        </div>
        <div class="col-lg-4 pb-2">
            <label class="mb-0">Phone Number</label>
            <?php
                if($dt_detail_pin->pin_call != null){ 
                    echo "<p class='mb-0 fw-bold'>$dt_detail_pin->pin_call</p>";
                } else {
                    echo "<p class='mb-0 text-none'>- Not provided -</p>";
                }
            ?>
        </div>
    </div>
    <hr>
    <label class="mb-0">Description</label>
    <?php
        if($dt_detail_pin->pin_desc != null){ 
            echo "<p class='mb-0 text-sm'>$dt_detail_pin->pin_desc</p>";
        } else {
            echo "<p class='mb-0 text-none text-sm'>- Not provided -</p>";
        }
    ?>
</div>