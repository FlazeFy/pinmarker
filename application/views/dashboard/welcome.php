<style>
    .avatar_dashboard {
        width: 20vh;
        border: 3px solid black;
        border-radius: 100%;
        display: block;
        margin-inline: auto;
    }
    .dash-welcome {
        margin: var(--spaceXJumbo) 0;
        text-align:center;
    }

    /* Mobile & Tablet style */
    @media screen and (max-width: 1023px) {
        .avatar_dashboard {
            width: 80px;
            margin-left: 0;
            margin-right: var(--textLG);
        }
        h1 {
            font-size: var(--textXJumbo) !important;
        }
        .dash-welcome {
            text-align: start;
        }
    }
</style>
<br>
<div class="dash-welcome">
    <?php 
        if($is_mobile_device){
            echo "<div class='d-flex justify-content-center mx-auto mb-4'>";
        }

        if($this->session->userdata('role_key') == 1){
            $img_url = $this->session->userdata('user_img_url');
            if($img_url == null){
                echo "<img class='avatar_dashboard' src='http://127.0.0.1:8080/public/images/avatar_man_1.png'>";
            } else {
                echo "<img class='avatar_dashboard' src='$img_url' alt='$img_url'>";
            }
        }
    ?>
    <h1 style="font-size: 80px;">Welcome, <?php 
        if($is_mobile_device){
            echo "<br>";
        }
        echo $dt_my_profile->username; 
    ?></h1>
    <?php 
        if($is_mobile_device){
            echo "</div>";
        }
    ?>
</div>