<div class="d-block mx-auto <?php if (!$is_mobile_device){ echo "p-4"; } else { echo "p-2"; } ?> position-relative bg-white" style="max-width: 480px; border: var(--spaceMini) solid black; border-radius: 15px; margin-top:20vh;">
    <?php if (!$is_mobile_device): ?>
        <a class="btn btn-dark rounded-pill py-2 px-4 me-2" href="/ForgetController"><i class="fa-solid fa-lock-open"></i> Forget Password?</a>
        <a class="btn btn-dark rounded-pill py-2 px-4"><i class="fa-solid fa-mobile-screen"></i> Get Mobile Apps</a>
    <?php else: ?>
        <div class="row">
            <div class="col">
                <a class="btn btn-dark rounded-pill py-2 px-4"  href="/ForgetController"><i class="fa-solid fa-lock-open"></i> Forget Password?</a>
            </div>
            <div class="col">
                <a class="btn btn-dark rounded-pill py-2 px-4"><i class="fa-solid fa-mobile-screen"></i> Get Mobile Apps</a>
            </div>
        </div>
    <?php endif; ?>
    <hr>
    <a>We also available on</a><br>
    <div class="mt-3">
        <a class="btn btn-socmed" href="javascript:void(0)" onclick="window.open('https://t.me/pinmarker_bot', '_blank')"><i class="fa-brands fa-telegram"></i></a>
        <a class="btn btn-socmed" href=""><i class="fa-brands fa-discord"></i></a>
    </div>
</div>