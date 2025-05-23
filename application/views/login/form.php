<div class="container-fluid bg-light-primary"id="login-section" style="padding: var(--spaceJumbo);">
    <?php if (!$is_mobile_device): ?>
        <a class="position-absolute btn btn-dark px-4 py-2" href="#global-section" style="left:32.5%; top:-60px;"><i class="fa-solid fa-arrow-up"></i> Browse Global</a>
    <?php endif; ?>
    <form action="/LoginController/login" method="post">
        <h2 class="text-center" style="font-weight:600;">Welcome to PinMarker</h2><br>
        <div>
            <label for="name">Email/Username</label>
            <input type="text" name="username" id="username" class="form-control <?= form_error('username') ? 'invalid' : '' ?>"
                placeholder="Your username or email" value="<?= set_value('username') ?>" required />
            <div class="msg-error-input">
                <?= form_error('username') ?>
            </div>
        </div>
        <div class="mt-2">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control <?= form_error('password') ? 'invalid' : '' ?>"
                placeholder="Enter your password" value="<?= set_value('password') ?>" required />
            <div class="msg-error-input">
                <?= form_error('password') ?>
            </div>
        </div>
        <?php 
            if($this->session->flashdata('validation_error')){
                echo "
                    <div class='alert alert-danger' role='alert'>
                        <h5><i class='fa-solid fa-triangle-exclamation'></i> Error</h5>
                        ".$this->session->flashdata('validation_error')."
                    </div>
                "; 
            }
        ?>

        <br>
        <button type="submit" class="btn btn-success mt-3 py-3 w-100" id="sign-in-btn"><i class="fa-solid fa-right-to-bracket"></i> Sign In</button>
        <a class="btn btn-white w-100 py-3 mt-3" href="/RegisterController" id='register-btn' style="border: 2.5px solid black;"><?php if(!$is_mobile_device){ echo "Does'nt have an account? "; } ?><b>Register Now</b></a>
        <?php if (!$is_mobile_device): ?>
            <div class="my-2 text-center d-flex">
                <h6 class="my-2 me-2">Or, forget your password</h6>
                <a class="btn btn-dark py-2 px-4 me-2" href="/ForgetController" id='forget-pass-btn'><i class="fa-solid fa-lock-open"></i> Change Password?</a>
            </div>
        <?php else: ?>
            <div class="my-2 text-center">
                <h6 class="my-2 me-2">Or, forget your password</h6>
                <a class="btn btn-dark py-2 px-4 me-2 w-100" href="/ForgetController" id='forget-pass-btn'><i class="fa-solid fa-lock-open"></i> Change Password?</a>
            </div>
        <?php endif; ?>
    </form>
</div>