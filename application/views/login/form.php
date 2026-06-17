<div class="login-wrap text-center">
    <div class="mb-4">
        <h1 class="text-primary fw-bold">PinMarker</h1>
        <p class="text-secondary">Marks Your World</p>
    </div>
    <div class="card" id="form-login">
        <h2 class="card-title">Welcome Back</h2>
        <p class="card-sub">Sign in to your account to continue exploring.</p>
        <hr>
        <form action="/LoginController/login" method="POST">
            <div class="field mb-3 text-start">
                <label class="form-label" for="username">Email or Username</label>
                <div class="form-control-wrap">
                    <i class="fa-solid fa-user form-control-icon" id="icon-username"></i>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter your email" required>
                </div>
            </div>
            <div class="field mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="form-label" for="password">Password</label>
                    <a href="/ForgotController" class="forgot">Forgot Password?</a>
                </div>
                <div class="form-control-wrap">
                    <i class="fa-solid fa-lock form-control-icon" id="icon-password"></i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            <?php 
                if($this->session->flashdata('validation_error')){
                    echo "
                        <div class='alert alert-danger' role='alert'>
                            <h6><i class='fa-solid fa-triangle-exclamation'></i> Failed</h6>
                            ".$this->session->flashdata('validation_error')."
                        </div>
                    "; 
                }
            ?>
            <?php 
                if($this->session->flashdata('message_error')){
                    echo "
                        <div class='alert alert-danger' role='alert'>
                            <h6><i class='fa-solid fa-triangle-exclamation'></i> Failed</h6>
                            ".$this->session->flashdata('message_error')."
                        </div>
                    "; 
                }
            ?>
            <a class="btn-primary btn-lg w-100 btn-submit">Sign In</a>
        </form>
        <div class="divider">
            <span>Or Continue With</span>
        </div>
        <a href="/LoginController/google" class="btn-outline d-flex justify-content-center gap-2 align-items-center mb-4">
            <i class="fa-brands fa-google"></i> Google
        </a>
        <p class="card-sub">Don't have an account? <a href="/RegisterController" class="link-primary">Register Now</a></p>
    </div>
    <div class="util-footer">
        <a href="/HelpController" class="help-btn">
            <i class="fa-regular fa-circle-question"></i> Help Center
        </a>
        <div class="util-links">
            <a href="/PrivacyController">Privacy Policy</a>
            <span>•</span>
            <a href="/TermsController">Terms of Service</a>
        </div>
    </div>
</div>

<script>
    const postLogin = () => {
        const username = $('#username').val().trim()
        const password = $('#password').val().trim()

        if (!username || !password) {
            Swal.fire({
                icon: 'warning',
                title: 'Failed!',
                text: 'username and password cannot be empty'
            })
            return
        }

        Swal.showLoading()

        $.ajax({
            url: '/api/v1/auth/login',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ username, password }),
            success: (response) => {
                localStorage.setItem('auth_token', response.data.token)
                $('form').off('submit').submit()
            },
            error: (response) => {
                Swal.hideLoading()

                const message = response.responseJSON?.message ?? 'Wrong username or password'

                Swal.fire({
                    icon: 'warning',
                    title: 'Failed!',
                    text: message
                })
            }
        })
    }

    $(document).ready(function () {
        $(document).on('click', '.btn-submit', function (e) {
            postLogin()
        })
    })
</script>