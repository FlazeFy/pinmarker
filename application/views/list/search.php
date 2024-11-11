<div class="position-relative w-100 me-2">
    <form  action="/MapsController/search_pin_name" method="POST">
        <input hidden name="page" value="List">
        <div class="form-floating mt-1">
            <input id="pin_name" type="text" class="form-control" name="pin_name" value="<?= $this->session->userdata('search_pin_name_key') ?? "";?>"
                value="<?php 
                    $search_pin_name = $this->session->userdata('search_pin_name_key');
                    if($search_pin_name != null && $search_pin_name != ""){
                        echo $search_pin_name;
                    } 
                ?>" required>
            <label for="floatingSelect" class="fw-normal">Filter By Name</label>
        </div>
        <button class="btn btn-success m-0 position-absolute" style="<?php if(!$is_mobile_device){ echo "top: 10px;";} else { echo "top: 15px;"; } ?> right: 10px;" id='search-btn' type="submit"><i class="fa-solid fa-magnifying-glass"></i><?php if(!$is_mobile_device){ echo " Search";} ?></button>
    </form>
    <form action="/MapsController/reset_search_pin_name" method="POST">
        <input hidden name="page" value="List">
        <button class="btn btn-danger m-0 position-absolute" id='reset-search-btn' style="<?php if(!$is_mobile_device){ echo "top: 10px;";} else { echo "top: 15px;"; } ?> right: <?php if(!$is_mobile_device){ echo "115px;";} else { echo "60px"; } ?>" type="submit" title="Reset search"><i class="fa-solid fa-rotate-left"></i></button>
    </form>
</div>