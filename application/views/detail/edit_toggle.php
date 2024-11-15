<form action="/DetailController/edit_toogle/<?= $dt_detail_pin->id ?>" method="POST" class="d-inline">
    <?php 
        if($this->session->userdata('is_edit_mode') == false){
            echo "<button class='btn btn-light mb-4 btn-menu-main py-3 me-1' id='toggle-edit-btn'><i class='fa-solid fa-pen-to-square'></i>";
            if(!$is_mobile_device){
                echo " Switch to Edit Mode";
            }
            echo "</button>";
        } else {
            echo "<button class='btn btn-danger mb-4 p py-3 me-1' id='toggle-edit-btn'><i class='fa-solid fa-pen-to-square'></i>";
            if(!$is_mobile_device){
                echo " Back to View Mode";
            }
            echo "</button>";
        }
    ?>
</form>