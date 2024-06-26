<form action="/LoginController/login" method="post" style="max-width: 480px; border: 2px solid black; border-radius: 15px; margin-top:25vh;" class="d-block mx-auto p-4">
    <h2 class="text-center" style="font-weight:600;">Welcome to PinMarker</h2><br>
    <div>
        <label for="name">Email/Username</label>
        <input type="text" name="username" class="form-control <?= form_error('username') ? 'invalid' : '' ?>"
            placeholder="Your username or email" value="<?= set_value('username') ?>" required />
        <div class="msg-error-input">
            <?= form_error('username') ?>
        </div>
    </div>
    <div class="mt-2">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control <?= form_error('password') ? 'invalid' : '' ?>"
            placeholder="Enter your password" value="<?= set_value('password') ?>" required />
        <div class="msg-error-input">
            <?= form_error('password') ?>
        </div>
    </div>
    <?php 
        if($this->session->flashdata('message_login_error')){
            echo "<div class='msg-error-input'>
                    "; echo $this->session->flashdata('message_login_error'); echo"
            </div>";
        }
    ?>

    <br>
    <button type="submit" class="btn btn-dark rounded-pill mt-3 py-3 w-100"><i class="fa-solid fa-right-to-bracket"></i> Sign In</button>
    <a class="btn btn-white rounded-pill w-100 py-3 mt-3" style="border: 2.5px solid black;">Does'nt have an account? <b>Register Now</b></a>
</form>