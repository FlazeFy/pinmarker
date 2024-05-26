<style>
    .avatar_dashboard {
        width: 20vh;
        border: 3px solid black;
        border-radius: 100%;
        display: block;
        margin-inline: auto;
    }
</style>
<br>
<div class="my-4 text-center">
    <?php 
        $img_url = $this->session->userdata('user_img_url');
        if($img_url == null){
            echo "<img class='avatar_dashboard' src='http://127.0.0.1:8080/public/images/avatar_man_1.png'>";
        } else {
            echo "<img class='avatar_dashboard' src='$img_url' alt='$img_url'>";
        }
    ?>
    <h1 style="font-size: 80px;">Welcome, <?= $dt_my_profile->username ?></h1>
</div>