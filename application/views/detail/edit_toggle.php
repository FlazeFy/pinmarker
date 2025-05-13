<form action="/DetailController/edit_toogle/<?= $dt_detail_pin->id ?>" method="POST" class="d-inline">
    <button class='btn btn-dark mb-4 btn-menu-main py-3 me-1' id='toggle-edit-btn'><i class='fa-solid fa-pen-to-square'></i>
    <?php 
        if($this->session->userdata('is_edit_mode') == false){
            if(!$is_mobile_device){
                echo " Switch to Edit Mode";
            }
        } else {
            if(!$is_mobile_device){
                echo " Back to View Mode";
            }
        }
    ?>
    </button>
</form>