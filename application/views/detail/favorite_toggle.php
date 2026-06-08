<form action="/DetailController/favorite_toogle/<?= $dt_detail_pin->id ?>" method="POST" class="d-inline">
    <?php 
        if($dt_detail_pin->is_favorite == '1'){
            echo "<input name='is_favorite' value='0' hidden><button class='btn btn-danger' id='toggle-favorite-btn'><i class='fa-solid fa-heart'></i></button>";
        } else {
            echo "<input name='is_favorite' value='1' hidden><button class='btn btn-outline-danger' id='toggle-favorite-btn'><i class='fa-solid fa-heart'></i></button>";
        }
    ?>
</form>