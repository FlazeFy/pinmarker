<form action="/DetailController/print_detail/<?= $dt_detail_pin->id ?>" method="POST" class="d-inline">
    <button class='btn btn-light mb-4 py-3 px-4 me-1' id='preview-doc-btn'><i class='fa-solid fa-print'></i><?php
        if(!$is_mobile_device){
            echo " Print Detail";
        }
    ?></button>
</form>