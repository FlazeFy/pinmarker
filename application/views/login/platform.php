<?php if ($is_mobile_device): ?>
    <br><hr><br>
<?php endif; ?>
<h1 class="fw-bold text-center" <?php if(!$is_mobile_device){ echo 'style="margin-top:20vh;"'; } ?>>WHERE YOU CAN USE OUR APPS?</h1>
<div class="row text-center">
    <div class="col-lg-4 col-md-4 col-sm-12 col-12 <?php if(!$is_mobile_device){ echo "py-3"; } ?>">
        <div class="container-fluid bg-light-primary text-center p-4 d-flex flex-column justify-content-center align-items-center" style="height: 400px;">
            <img class='img img-fluid <?php if(!$is_mobile_device){ echo "mt-3"; } ?> mx-auto d-block' style="height:<?php if(!$is_mobile_device){ echo "60%"; } else { echo "160px"; } ?>" src='http://127.0.0.1:8080/public/images/mobile.png'>
            <h2 class="fw-bold">Mobile</h2>
            <p class="text-secondary mb-0">For better experience with <b>TRACKER</b> feature</p>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12 <?php if(!$is_mobile_device){ echo "py-3"; } ?>">
        <div class="container-fluid bg-light-primary text-center p-4 d-flex flex-column justify-content-center align-items-center" style="height: 400px;">
            <img class='img img-fluid <?php if(!$is_mobile_device){ echo "mt-3"; } ?> mx-auto d-block' style="height:<?php if(!$is_mobile_device){ echo "60%"; } else { echo "160px"; } ?>" src='http://127.0.0.1:8080/public/images/web.png'>
            <h2 class="fw-bold">WEB</h2>
            <p class="text-secondary mb-0">Use PinMarker everywhere with low resource and slow connection</p>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12 <?php if(!$is_mobile_device){ echo "py-3"; } ?>">
        <div class="container-fluid bg-light-primary text-center p-4 d-flex flex-column justify-content-center align-items-center" style="height: 400px;">
            <img class='img img-fluid <?php if(!$is_mobile_device){ echo "mt-3"; } ?> mx-auto d-block' style="height:<?php if(!$is_mobile_device){ echo "60%"; } else { echo "160px"; } ?>" src='http://127.0.0.1:8080/public/images/bot.png'>
            <h2 class="fw-bold">BOT</h2>
            <p class="text-secondary mb-0">Get some data or get closest location using chat. Available on <b>Telegram</b> and <b>Discord</b></p>
        </div>
    </div>        
</div>
<?php if ($is_mobile_device): ?>
    <br>
<?php endif; ?>