<style>
    .wrapper-progressBar {
        position: fixed;
        background: white;
        width: 100%;
        left: 0;
        top: 0;
        margin: 0;
    }
    .progressBar li {
        list-style-type: none;
        float: left;
        width: 33%;
        position: relative;
        text-align: center;
    }
    .progressBar li:before {
        content: " ";
        line-height: 30px;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        border: 1px solid #ddd;
        display: block;
        text-align: center;
        margin: 0 auto 10px;
        background-color: white;
        -webkit-transition: all 0.5s;
        -o-transition: all 0.5s;
        transition: all 0.5s;
    }
    .progressBar li:after {
        content: "";
        position: absolute;
        width: 100%;
        height: 4px;
        background-color: #ddd;
        top: 15px;
        left: -50%;
        z-index: -1;
    }
    .progressBar li:first-child:after {
        content: none;
    }
    .progressBar li.active {
        color: black;
    }
    .progressBar li.active:before {
        border-color: black;
        background-color: black;
    }
    .progressBar .active:after {
        background-color: black;
    }

    .pin-code{ 
        padding: 0; 
        margin: 0 auto; 
        display: flex;
        justify-content:center;
    
    } 
    .pin-code input { 
        border: none; 
        text-align: center; 
        width: 48px;
        height:48px;
        font-size: 36px; 
        background-color: #F3F3F3;
        margin-right:5px;
    } 
    .pin-code input:focus { 
        border: 1px solid #573D8B;
        outline:none;
    } 
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

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
                    <a class="btn btn-dark pt-2 px-4 rounded-pill" href="/LoginController">Back to Login</a>
                </div><br>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Eu non diam phasellus vestibulum lorem sed risus ultricies. Ipsum a arcu cursus vitae congue mauris rhoncus. Pharetra sit amet aliquam id diam maecenas ultricies. Fringilla urna porttitor rhoncus dolor. Velit euismod in pellentesque massa placerat duis ultricies lacus. Ut enim blandit volutpat maecenas volutpat blandit aliquam. Morbi tincidunt augue interdum velit euismod in pellentesque. Commodo viverra maecenas accumsan lacus vel facilisis volutpat. Quam lacus suspendisse faucibus interdum. Diam ut venenatis tellus in metus vulputate eu. Vitae et leo duis ut diam quam nulla.</p>
                <p>Vitae auctor eu augue ut lectus. Vitae purus faucibus ornare suspendisse. Turpis nunc eget lorem dolor. Est sit amet facilisis magna etiam tempor orci eu. Tortor condimentum lacinia quis vel eros donec ac odio. Eget sit amet tellus cras adipiscing. Aliquam nulla facilisi cras fermentum odio eu feugiat pretium nibh. Eget sit amet tellus cras adipiscing enim eu. Sed arcu non odio euismod lacinia. Elementum facilisis leo vel fringilla est ullamcorper eget. Elementum eu facilisis sed odio morbi quis commodo odio. Mauris a diam maecenas sed enim ut sem viverra aliquet. Arcu non odio euismod lacinia at quis.</p>
                <p>Ullamcorper sit amet risus nullam eget. Nunc eget lorem dolor sed viverra ipsum. Quam nulla porttitor massa id neque aliquam vestibulum morbi. Amet est placerat in egestas erat imperdiet sed euismod nisi. Eu augue ut lectus arcu bibendum at. Mi tempus imperdiet nulla malesuada pellentesque elit eget gravida. Pretium fusce id velit ut tortor pretium viverra. Lobortis mattis aliquam faucibus purus in massa. Id donec ultrices tincidunt arcu non sodales neque sodales. Leo vel fringilla est ullamcorper eget nulla. Dui vivamus arcu felis bibendum ut tristique et egestas quis. Bibendum est ultricies integer quis auctor elit sed vulputate. Pharetra convallis posuere morbi leo urna molestie. Iaculis urna id volutpat lacus laoreet non curabitur gravida. Lorem donec massa sapien faucibus et molestie ac. Nam aliquam sem et tortor consequat id porta nibh venenatis. Quis blandit turpis cursus in hac. Netus et malesuada fames ac. Risus quis varius quam quisque id diam vel.</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" onchange="navTerms()" id="checkTerm">
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
                        <a class="btn btn-link rounded-pill px-3 mt-3">Don't receive the token. Send again!</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var pinContainer = document.querySelector(".pin-code")
    var pin_holder = document.getElementById('pin-holder')
    var timer = document.getElementById("timer")
    var remain = 900

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

    function formatTime(seconds){
        var minutes = Math.floor(seconds / 60);
        var remainingSeconds = seconds % 60;
        return minutes + ':' + remainingSeconds.toString().padStart(2, '0');
    }

    function controlPin(type) {
        var pins = pin_holder.querySelectorAll('input');
        var result = "";

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

    function validatePin(){
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

    function validateToken(token, username){
        Swal.showLoading()
        $.ajax({
            url: `http://127.0.0.1:2000/api/v1/req/validate/${token}/${username}`,
            dataType: 'json',
            contentType: 'application/json',
            type: "POST",
            beforeSend: function (xhr) {
                // ...
            }
        })
        .done(function (response) {            
            const data = response
            Swal.hideLoading()
            Swal.fire({
                title: data.is_validated ? "Success!" : "Failed!",
                text: data.message,
                icon: data.is_validated ? "success" : "error" 
            });

            $('#register-form').submit()
        })
        .error(function (xhr, ajaxOptions, thrownError) {
            Swal.fire({
                title: "Oops!",
                text: "Something error! Please call admin",
                icon: "error"
            });
        })
    }
    
    function startTimer(duration) {
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

    function navTerms(){
        $(document).ready(function() {
            $('#tnc_indicator').attr('class', 'active')
            $('#user_profile_holder').css({'display':'block'})
        });
        $('#user_profile_holder').html(`
            <h2>User Profile</h2><br>
            <label>Username</label>
            <input name="username" id="username" type="text" onchange="check_avaiability('username')" class="form-control mb-0"/>
            <p class="msg-error-input mb-2" id="username_err_msg"></p>
            <label>Fullname</label>
            <input name="fullname" id="fullname" type="text" class="form-control"/>
            <a class="msg-error-input"></a>
            <label>Email</label>
            <input name="email" id="email" type="email" onchange="check_avaiability('email')" class="form-control"/>
            <a class="msg-error-input"></a>
            <label>Password</label>
            <input name="password" id="password" type="password" class="form-control"/>
            <label>Re-Type Password</label>
            <input name="password_check" id="password_check" type="password" class="form-control"/>
            <div id="btn-regis-holder">
                <a class="btn btn-dark rounded-pill px-3" onclick="navProfile()"><i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Create Account</a>
            </div>
        `)
    }

    function check_avaiability(type){
        const ctx = $(`#${type}`).val()
        $.ajax({
            url: `http://127.0.0.1:2000/api/v1/user/check/${type}/${ctx}`,
            dataType: 'json',
            contentType: 'application/json',
            type: "POST",
            beforeSend: function (xhr) {
                // ...
            }
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

    function navProfile(){
        if(validateInput("text", "username", 36, 4) && validateInput("text", "email", 255, 10) && validateInput("text", "password", 36, 6) && validateInput("text", "password_check", 36, 6)){
            const username = $('#username').val()
            const email = $('#email').val()

            if($('#password').val().trim() == $('#password_check').val().trim()){
                Swal.showLoading()
                $.ajax({
                    url: `http://127.0.0.1:2000/api/v1/req/register/${email}/${username}`,
                    dataType: 'json',
                    contentType: 'application/json',
                    type: "POST",
                    beforeSend: function (xhr) {
                        // ...
                    }
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