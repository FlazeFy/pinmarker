<div class="d-block mx-auto <?php if (!$is_mobile_device){ echo "p-4"; } else { echo "p-2"; } ?> position-relative bg-white" style="max-width: 480px; border: var(--spaceMini) solid black; border-radius: 15px; margin-top:-20px;">
    <?php if (!$is_mobile_device): ?>
        <a class="btn btn-dark rounded-pill py-2 px-4 me-2"><i class="fa-solid fa-circle-question"></i> FAQ</a>
        <a class="btn btn-dark rounded-pill py-2 px-4"><i class="fa-solid fa-user"></i> About Us</a>
    <?php else: ?>
        <div class="row">
            <div class="col">
                <a class="btn btn-dark rounded-pill py-2 px-4 w-100"><i class="fa-solid fa-circle-question"></i> FAQ</a>
            </div>
            <div class="col">
                <a class="btn btn-dark rounded-pill py-2 px-4 w-100"><i class="fa-solid fa-user"></i> About Us</a>
            </div>
        </div>
    <?php endif; ?>
    <hr>
    <a>We also provided you with our dummy data</a><br>
    <div class="mt-3">
        <a class="btn btn-socmed"><i class="fa-solid fa-cloud-arrow-down"></i></a>
    </div>
</div>