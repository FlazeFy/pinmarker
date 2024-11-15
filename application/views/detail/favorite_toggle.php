<form action="/DetailController/favorite_toogle/<?= $dt_detail_pin->id ?>" method="POST" class="d-inline">
    <?php 
        if($dt_detail_pin->is_favorite == '1'){
            echo "<input name='is_favorite' value='0' hidden><button class='btn btn-dark mb-4 btn-menu-main py-3 me-1' style='bottom:calc(4*var(--spaceXLG));' id='toggle-favorite-btn'><i class='fa-solid fa-heart'></i>";
            if(!$is_mobile_device){
                echo " Saved to Favorite";
            }
            echo "</button>";
        } else {
            echo "<input name='is_favorite' value='1' hidden><button class='btn btn-light mb-4 btn-menu-main py-3 me-1' style='bottom:calc(4*var(--spaceXLG));' id='toggle-favorite-btn'><i class='fa-solid fa-heart'></i>";
            if(!$is_mobile_device){
                echo " Add to Favorite";
            }
            echo "</button>";
        }
    ?>
</form>