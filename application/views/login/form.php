<div class="d-block mx-auto p-4 position-relative bg-white" style="max-width: 480px; border: var(--spaceMini) solid black; border-radius: 15px; top:-20px;"
    id="login-section">
    <?php if (!$is_mobile_device): ?>
        <a class="position-absolute btn btn-dark rounded-pill px-4 py-2" href="#global-section" style="left:32.5%; top:-60px;">Browse Global</a>
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
        <button type="submit" class="btn btn-success rounded-pill mt-3 py-3 w-100" id="sign-in"><i class="fa-solid fa-right-to-bracket"></i> Sign In</button>
        <a class="btn btn-white rounded-pill w-100 py-3 mt-3" href="/RegisterController" style="border: 2.5px solid black;"><?php if(!$is_mobile_device){ echo "Does'nt have an account? "; } ?><b>Register Now</b></a>
    </form>
</div>

<?php 
    if($this->session->flashdata('message_error')){
        echo "
            <script>
                Swal.fire({
                    title: 'Failed!',
                    text: '".$this->session->flashdata('message_error')."',
                    icon: 'error'
                });
            </script>
        ";
    }
?>