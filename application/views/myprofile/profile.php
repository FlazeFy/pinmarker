<form method="POST" action="/myprofilecontroller/edit_profile">
    <label>Username</label>
    <input name="username" id="username" type="text" value="<?= $dt_my_profile->username ?>" class="form-control"/>
    <a class="msg-error-input"></a>
    <label>Fullname</label>
    <input name="fullname" id="fullname" type="text" value="<?= $dt_my_profile->fullname ?>" class="form-control"/>
    <a class="msg-error-input"></a>
    <label>Email</label>
    <input name="email" id="email" type="email" value="<?= $dt_my_profile->email ?>" class="form-control"/>
    <a class="msg-error-input"></a>
    <button class="btn btn-dark rounded-pill w-100 mt-3" type="submit">Save Changes</button>
</form><hr>
<label>Telegram ID</label>
<input name="telegram_user_id" id="telegram_user_id" type="text" value="<?= $dt_my_profile->telegram_user_id ?>" class="form-control"/>
<a class="msg-error-input">
    <?php
        if($dt_my_profile->telegram_is_valid == 0){
            echo "<i class='fa-solid fa-triangle-exclamation'></i> Your telegram ID is not validated yet! 
            <form action='/myprofilecontroller/send_validation_token' method='POST'>
                <button class='btn btn-link' type='submit' style='font-size: var(--textXMD);'><i class='fa-solid fa-arrow-right'></i> Send Validation Token</button>
            </form>";
        }
    ?>
</a>

<script></script>