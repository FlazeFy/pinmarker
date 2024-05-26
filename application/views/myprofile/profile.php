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
</form>
