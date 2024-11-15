<form action="/DetailController/delete_pin/<?= $dt_detail_pin->id ?>" method="POST" class="d-inline" id="delete-pin-form">
    <a class='btn btn-danger mb-4 py-3 px-4' id="delete-pin-btn"><i class='fa-solid fa-trash'></i>
    <?php
        if(!$is_mobile_device){
            echo " Delete";
        }
    ?>
    </a>
</form>