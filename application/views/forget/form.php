<div>
    <div class="wrapper-progressBar py-4">
        <ul class="progressBar">
            <li id="fu_indicator">Find User</li>
            <li id="validate_acc_indicator">Validate</li>
            <li id="new_pass_indicator">New Password</li>
        </ul>
    </div>
    <div style="width: 720px;" class="d-block mx-auto mt-4">
        <form method="POST" action="/RegisterController/register" id="register-form">
            <div style="height:90vh; padding-top:calc(var(--spaceJumbo)*3);" id="fu_form">
                <div class="d-flex justify-content-between">
                    <h2>Forget Password</h2>  
                    <a class="btn btn-danger pt-2 px-4 rounded-pill" href="/LoginController"><i class="fa-regular fa-circle-xmark"></i> Back to Login</a>
                </div><br>
                <p>Give us your username and email to change the password. Before the process begin, we must validate that you're the owner. The token validation will send to your email or telegram</p>
                <label>Username</label>
                <input name="username" id="username" type="text" class="form-control mb-0"/>
                <p class="msg-error-input mb-2" id="username_err_msg"></p>
                <label>Email</label>
                <input name="email" id="email" type="email" class="form-control"/>
                
                <a class="btn btn-success rounded-pill px-3" id="check-account-btn" onclick="searchUser()"><i class="fa-solid fa-magnifying-glass me-2"></i> Check Account</a>
            </div>
            <div style="height:90vh; padding-top:calc(var(--spaceJumbo)*3); display:none;" id="validate_holder">
                <div class="text-center">
                    <h2>Validate</h2><br>
                    <h1 class="my-2" id="timer">15:00</h1>
                    <label>Type the Token that has sended to your email</label>
                    <div class="pin-code" id="pin-holder">
                        <input type="text" maxlength="1" oninput="validatePin()" autofocus>
                        <input type="text" maxlength="1" oninput="validatePin()">
                        <input type="text" maxlength="1" oninput="validatePin()">
                        <input type="text" maxlength="1" oninput="validatePin()">
                        <input type="text" maxlength="1" oninput="validatePin()">
                        <input type="text" maxlength="1" oninput="validatePin()">
                    </div>
                    <div id="token_validate_msg" class="msg-error-input mb-2" style="font-size:13px;"></div>
                    <div class="d-inline-block mx-auto">
                        <a class="btn btn-link rounded-pill px-3 mt-3" id="send-again-token-btn">Don't receive the token. Send again!</a>
                    </div>
                </div>
            </div>
            <div style="height:90vh; padding-top:calc(var(--spaceJumbo)*3); display:none;" id="new_pass_form"></div>
        </form>
    </div>
</div>

<script>
    let pinContainer = document.querySelector(".pin-code")
    let pin_holder = document.getElementById('pin-holder')
    let timer = document.getElementById("timer")
    let remain = 900
    let is_process = false

    window.addEventListener('beforeunload', function(event) {
        if(is_process){
            event.preventDefault()
            event.returnValue = ''
        }
    });

    pinContainer.addEventListener('keyup', function (event) {
        var target = event.srcElement
        
        var maxLength = parseInt(target.attributes["maxlength"].value, 10)
        var myLength = target.value.length

        if (myLength >= maxLength) {
            var next = target
            while (next = next.nextElementSibling) {
                if (next == null) break
                if (next.tagName.toLowerCase() == "input") {
                    next.focus()
                    break
                }
            }
        }

        if (myLength === 0) {
            var next = target;
            while (next = next.previousElementSibling) {
                if (next == null) break
                if (next.tagName.toLowerCase() == "input") {
                    next.focus()
                    break
                }
            }
        }
    }, false);

    const formatTime = (seconds) => {
        var minutes = Math.floor(seconds / 60)
        var remainingSeconds = seconds % 60
        return minutes + ':' + remainingSeconds.toString().padStart(2, '0')
    }

    const controlPin = (type) => {
        var pins = pin_holder.querySelectorAll('input')
        var result = ""

        pins.forEach(function(e) {
            if(type == "time_out"){
                e.disabled = true
                e.style = "background: var(--hoverBG);"
            } else if(type == "regenerate"){
                e.disabled = false
                e.value = ""
                e.style = "background: var(--whiteColor);"
            } else if(type == "invalid"){
                e.value = ""
                e.style = "border: 1.5px solid var(--warningBG); "
            } else if(type == "fetch"){
                result += e.value
            }
        });

        return result;
    }

    const validatePin = () => {
        var pins = pin_holder.querySelectorAll('input')
        var is_empty = false

        pins.forEach(function(e) {
            if(e.value == "" || e.value == null){
                is_empty = true
                return
            }
        });

        if(is_empty == false){
            const token = controlPin('fetch')
            validateToken(token, $('#username').val())
        }
    }

    const validateToken = (token, username) => {
        loading() 
        $.ajax({
            url: `http://127.0.0.1:8000/api/v1/req_validate/forget/${token}/${username}`,
            dataType: 'json',
            contentType: 'application/json',
            type: "POST",
        })
        .done(function (response) {         
            const data = response
            is_process = false

            Swal.hideLoading()
            Swal.fire({
                title: data.is_validated ? "Success!" : "Failed!",
                text: data.message,
                icon: data.is_validated ? "success" : "error" 
            });

            if(data.is_sended){
                $(document).ready(function() {
                    $('#validate_holder').css({'height':'auto','display':'none'})
                    $('#new_pass_indicator').attr('class', 'active')
                    $('#new_pass_form').css({'display':'block'})
                });
                $('#new_pass_form').html(`
                    <h2>New Password</h2> 
                    <p>Make sure you keep this password safe</p>
                    <form id='form-pass-recovery' method='POST' action='/ForgetController/forget_pass'>
                        <input hidden name="username" value="${$('#username').val()}">
                        <label>Password</label>
                        <input name="password" id="password" type="password" class="form-control"/>
                        <label>Re-Type Password</label>
                        <input name="password_check" id="password_check" type="password" class="form-control"/>
                        <a class="btn btn-dark rounded-pill px-3" onclick="submitForgetPass()" id="submit-btn"><i class="fa-solid fa-floppy-disk me-2"></i> Submit New Password</a>
                    </form>
                `)
            }
        })
        .error(function (xhr, ajaxOptions, thrownError) {
            Swal.fire({
                title: "Oops!",
                text: "Something error! Please call admin",
                icon: "error"
            });
        })
    }
    
    const startTimer = (duration) => {
        var remain = duration

        function updateTimer() {
            timer.innerHTML = formatTime(remain)

            if (remain > 0) {
                remain--
                setTimeout(updateTimer, 1000)

                if (remain <= 180) {
                    timer.style = "color: var(--warningBG);"
                }
            } else {
                token_msg.innerHTML = "<span class='text-danger'>Time's up, please try again</span>"
                controlPin("time_out")
            }
        }

        updateTimer()
    }

    pinContainer.addEventListener('keydown', function (event) {
        var target = event.srcElement
        target.value = ""
    }, false);

    const searchUser = () => {
        if(validateInput("text", "username", 36, 4) && validateInput("text", "email", 255, 10)){
            const username = $('#username').val()
            const email = $('#email').val()

            if (email.includes("@gmail")){
                loading() 
                $.ajax({
                    url: `http://127.0.0.1:8000/api/v1/req/forget/${email}/${username}`,
                    dataType: 'json',
                    contentType: 'application/json',
                    type: "POST",
                })
                .done(function (response) {            
                    Swal.hideLoading()

                    const data = response

                    if(data.is_sended){
                        $(document).ready(function() {
                            $('#fu_form').css({'height':'auto','display':'none'})
                            $('#username, #email').attr('readonly', true)
                            $('#validate_acc_indicator').attr('class', 'active')
                            $('#validate_holder').css({'display':'block'})
                        });
                        $('#btn-regis-holder').html(`
                            <a class="text-success">Sended!</a>
                        `)

                        startTimer(900)
                    }

                    Swal.fire({
                        title: data.is_sended ? "Success!" : "Failed!",
                        text: data.message,
                        icon: data.is_sended ? "success" : "error" 
                    });
                })
                .fail(function (xhr, ajaxOptions, thrownError) {
                    Swal.fire({
                        title: "Oops!",
                        text: "Something error! Please call admin",
                        icon: "error"
                    });
                })
            } else {
                Swal.fire({
                    title: "Oops!",
                    text: "Your email must from google mail",
                    icon: "error"
                });
            }
        } else {
            Swal.fire({
                title: "Oops!",
                text: "All form must be filled!",
                icon: "error"
            });
        }
    }

    const submitForgetPass = () => {
        if($('#password').val().trim() == $('#password_check').val().trim()){
            $('#form-pass-recovery').submit()
        } else {
            Swal.fire({
                title: "Oops!",
                text: "Your password verification is not same",
                icon: "error"
            });
        }
    }
</script>