<form method="POST" action="/MyProfileController/edit_profile">
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
    <label>Username</label>
    <input name="username" id="username" type="text" value="<?= $dt_my_profile->username ?>" class="form-control"/>
    
    <label>Fullname</label>
    <input name="fullname" id="fullname" type="text" value="<?= $dt_my_profile->fullname ?>" class="form-control"/>
    
    <label>Email</label>
    <input name="email" id="email" type="email" value="<?= $dt_my_profile->email ?>" class="form-control"/>
    
    <button class="btn btn-success rounded-pill w-100 mt-3" type="submit">Save Changes</button>
</form><hr>
<div class="d-flex justify-content-between">
    <label>Telegram ID</label>
    <?php if($dt_my_profile->telegram_is_valid == 1){ echo "<label class='text-success'>Validated!</label>"; } ?>
</div>
<input name="telegram_user_id" id="telegram_user_id" oninput="check_telegram_user_id_change()" type="text" value="<?= $dt_my_profile->telegram_user_id ?>" <?php if($dt_active_telegram_user_id_request){ echo "disabled"; } ?> class="form-control"/>
<input name="telegram_user_id_old" id="telegram_user_id_old" value="<?= $dt_my_profile->telegram_user_id ?>" hidden/>
<a class="msg-error-input" id="msg-error-telegram_user_id">
    <?php
        if($dt_my_profile->telegram_user_id){
            if($dt_my_profile->telegram_is_valid == 0 && !$dt_active_telegram_user_id_request){
                echo "<i class='fa-solid fa-triangle-exclamation'></i> Your telegram ID is not validated yet! 
                <form action='/MyProfileController/send_validation_token' method='POST'>
                    <button class='btn btn-link' type='submit' style='font-size: var(--textXMD);'><i class='fa-solid fa-arrow-right'></i> Send Validation Token</button>
                </form>";
            } else if($dt_active_telegram_user_id_request){
                echo "
                <div class='bg-danger-light p-3 rounded'>
                    <i class='fa-solid fa-triangle-exclamation'></i> We have send you Token validation to related user ID. Please fill this token below! 
                    <form action='/MyProfileController/validate_token_telegram' method='POST'>
                        <input class='form-control bg-transparent' id='token' name='token' type='text'>
                        <button class='btn btn-link' type='submit' style='font-size: var(--textXMD);'><i class='fa-solid fa-arrow-right'></i> Validate Token</button>
                    </form>
                </div>";
            }
        }
    ?>
</a>

<span id="submit_telegram_user_id_edit"></span>

<script>
    function check_telegram_user_id_change(){
        const tele_id_old = $("#telegram_user_id_old").val()
        const tele_id_new = $("#telegram_user_id").val()

        if(tele_id_new != tele_id_old){
            $("#msg-error-telegram_user_id").css({
                'display':'none'
            })
            $("#submit_telegram_user_id_edit").empty().append(`
                <form action='/MyProfileController/edit_telegram_id' method='POST'>
                    <input hidden value='${tele_id_new}' name='telegram_user_id' id='telegram_user_id'>
                    <button class='btn btn-success' type='submit' style='font-size: var(--textXMD);'><i class='fa-solid fa-floppy-disk'></i> Save Changes</button>
                </form>
            `)
        } else {
            $("#msg-error-telegram_user_id").css({
                'display':'block'
            })
            $("#submit_telegram_user_id_edit").empty()
        }
    }
</script>