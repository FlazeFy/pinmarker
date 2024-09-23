<div>
    <div class="wrapper-progressBar py-4">
        <ul class="progressBar">
            <li id="tnc_indicator">Terms & Condition</li>
            <li id="profile_indicator">User Profile</li>
            <li id="validate_indicator">Validate</li>
        </ul>
    </div>
    <div style="width: 720px;" class="d-block mx-auto mt-4">
        <form method="POST" action="/RegisterController/register" id="register-form">
            <div style="height:90vh; padding-top:calc(var(--spaceJumbo)*3);">
                <div class="d-flex justify-content-between">
                    <h2>Terms & Condition</h2>  
                    <a class="btn btn-danger pt-2 px-4 rounded-pill" href="/LoginController" id='back-page-btn'><i class="fa-regular fa-circle-xmark"></i> Back to Login</a>
                </div><br>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Eu non diam phasellus vestibulum lorem sed risus ultricies. Ipsum a arcu cursus vitae congue mauris rhoncus. Pharetra sit amet aliquam id diam maecenas ultricies. Fringilla urna porttitor rhoncus dolor. Velit euismod in pellentesque massa placerat duis ultricies lacus. Ut enim blandit volutpat maecenas volutpat blandit aliquam. Morbi tincidunt augue interdum velit euismod in pellentesque. Commodo viverra maecenas accumsan lacus vel facilisis volutpat. Quam lacus suspendisse faucibus interdum. Diam ut venenatis tellus in metus vulputate eu. Vitae et leo duis ut diam quam nulla.</p>
                <p>Vitae auctor eu augue ut lectus. Vitae purus faucibus ornare suspendisse. Turpis nunc eget lorem dolor. Est sit amet facilisis magna etiam tempor orci eu. Tortor condimentum lacinia quis vel eros donec ac odio. Eget sit amet tellus cras adipiscing. Aliquam nulla facilisi cras fermentum odio eu feugiat pretium nibh. Eget sit amet tellus cras adipiscing enim eu. Sed arcu non odio euismod lacinia. Elementum facilisis leo vel fringilla est ullamcorper eget. Elementum eu facilisis sed odio morbi quis commodo odio. Mauris a diam maecenas sed enim ut sem viverra aliquet. Arcu non odio euismod lacinia at quis.</p>
                <p>Ullamcorper sit amet risus nullam eget. Nunc eget lorem dolor sed viverra ipsum. Quam nulla porttitor massa id neque aliquam vestibulum morbi. Amet est placerat in egestas erat imperdiet sed euismod nisi. Eu augue ut lectus arcu bibendum at. Mi tempus imperdiet nulla malesuada pellentesque elit eget gravida. Pretium fusce id velit ut tortor pretium viverra. Lobortis mattis aliquam faucibus purus in massa. Id donec ultrices tincidunt arcu non sodales neque sodales. Leo vel fringilla est ullamcorper eget nulla. Dui vivamus arcu felis bibendum ut tristique et egestas quis. Bibendum est ultricies integer quis auctor elit sed vulputate. Pharetra convallis posuere morbi leo urna molestie. Iaculis urna id volutpat lacus laoreet non curabitur gravida. Lorem donec massa sapien faucibus et molestie ac. Nam aliquam sem et tortor consequat id porta nibh venenatis. Quis blandit turpis cursus in hac. Netus et malesuada fames ac. Risus quis varius quam quisque id diam vel.</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="checkTerm">
                    <label class="form-check-label" for="flexCheckDefault">I agree with this terms & condition</label>
                </div>
            </div>
            <div style="height:90vh; padding-top:calc(var(--spaceJumbo)*3); display:none;" id="user_profile_holder"></div>
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
                        <a class="btn btn-link rounded-pill px-3 mt-3" id='send-again-token-btn'>Don't receive the token. Send again!</a>
                    </div>
                </div>
            </div>
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
            url: `http://127.0.0.1:8000/api/v1/req_validate/register/${token}/${username}`,
            dataType: 'json',
            contentType: 'application/json',
            type: "POST",
        })
        .done(function (response, textStatus, xhr) {         
            const data = response
            const status_code = xhr.status
            is_process = false

            Swal.hideLoading()
            Swal.fire({
                title: data.is_validated ? "Success!" : "Failed!",
                text: data.message,
                icon: data.is_validated ? "success" : "error" 
            });

            if(status_code == 200 && data.is_validated){
                $('#register-form').submit()
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

    $(document).ready(function() {
        $(document).on('change', '#checkTerm', function() { 
            const is_check = $(this).is(':checked')

            if(is_check){
                is_process = true
                $('#tnc_indicator').attr('class', 'active')
                $('#user_profile_holder').css({'display':'block'})
            
                $('#user_profile_holder').html(`
                    <h2>User Profile</h2><br>
                    <label>Username</label>
                    <input name="username" id="username" type="text" onchange="check_avaiability('username')" class="form-control mb-0"/>
                    <p class="msg-error-input mb-2" id="username_err_msg"></p>
                    <label>Fullname</label>
                    <input name="fullname" id="fullname" type="text" class="form-control"/>
                    
                    <label>Email</label>
                    <input name="email" id="email" type="email" onchange="check_avaiability('email')" class="form-control"/>
                    
                    <label>Password</label>
                    <input name="password" id="password" type="password" class="form-control"/>
                    <label>Re-Type Password</label>
                    <input name="password_check" id="password_check" type="password" class="form-control"/>
                    <div id="btn-regis-holder">
                        <a class="btn btn-success rounded-pill px-3" onclick="navProfile()" id='submit-register-btn'><i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Create Account</a>
                    </div>
                `)
            } else {
                is_process = false
                $('#tnc_indicator').removeClass()
                $('#user_profile_holder').css({'display':'none'})
                $('#user_profile_holder').empty()
            }
        })
    });

    const check_avaiability = (type) => {
        const ctx = $(`#${type}`).val()
        $.ajax({
            url: `http://127.0.0.1:8000/api/v1/user/check/${type}/${ctx}`,
            dataType: 'json',
            type: "GET",
        })
        .done(function (response) {            
            const data = response
            let colTarget

            if(type == "username"){
                colTarget = "#email"
            } else if(type == "email"){
                colTarget = "#username"
            }

            if (typeof data.is_found != "undefined") {
                if(data.is_found){
                    $(`#fullname, ${colTarget}, #password, #password_check`).attr('disabled', true);
                    $(`#${type}_err_msg`).html(`<i class="fa-solid fa-triangle-exclamation"></i> ${data.message}`)
                } else {
                    $(`#fullname, ${colTarget}, #password, #password_check`).attr('disabled', false);
                    $(`#${type}_err_msg`).empty()
                }
            } else {
                $(`#fullname, ${colTarget}, #password, #password_check`).attr('disabled', true);
                $(`#${type}_err_msg`).html(`<i class="fa-solid fa-triangle-exclamation"></i> ${data.message}`)
            }
            
            Swal.fire({
                title: data.is_found || typeof data.is_found == "undefined" ? "Failed!" : "Success!",
                text: data.message,
                icon: data.is_found || typeof data.is_found == "undefined"? "error" : "success"
            });
        })
        .error(function (xhr, ajaxOptions, thrownError) {
            Swal.fire({
                title: "Oops!",
                text: "Something error! Please call admin",
                icon: "error"
            });
        })
    }

    const navProfile = () => {
        if(validateInput("text", "username", 36, 4) && validateInput("text", "email", 255, 10) && validateInput("text", "password", 36, 6) && validateInput("text", "password_check", 36, 6)){
            const username = $('#username').val()
            const email = $('#email').val()

            if($('#password').val().trim() == $('#password_check').val().trim()){
                if (email.includes("@gmail")){
                    if (/\d/.test($('#password').val().trim())) {
                        loading() 
                        $.ajax({
                            url: `http://127.0.0.1:8000/api/v1/req/register/${email}/${username}`,
                            dataType: 'json',
                            contentType: 'application/json',
                            type: "POST",
                        })
                        .done(function (response) {            
                            Swal.hideLoading()

                            const data = response
                            $(document).ready(function() {
                                $('#checkTerm').attr('disabled', true)
                                $('#username, #fullname, #email, #password, #password_check').attr('readonly', true)
                                $('#profile_indicator').attr('class', 'active')
                                $('#validate_holder').css({'display':'block'})
                            });
                            $('#validate_holder').css({'display':'block'})
                            $('#btn-regis-holder').html(`
                                <a class="text-success">Sended!</a>
                            `)
                            
                            Swal.fire({
                                title: "Success!",
                                text: data.message,
                                icon: "success"
                            });

                            startTimer(900)
                        })
                        .error(function (xhr, ajaxOptions, thrownError) {
                            Swal.fire({
                                title: "Oops!",
                                text: "Something error! Please call admin",
                                icon: "error"
                            });
                        })
                    } else {
                        Swal.fire({
                            title: "Oops!",
                            text: "Your password must contain at least one number",
                            icon: "error"
                        });
                    }
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
                    text: "Your password verification is not same",
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
</script>