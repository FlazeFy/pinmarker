<div class="row mb-2 text-center">
    <div class="col-lg-4 col-6 p-2">
        <h1 style="font-size: 60px; font-weight:bold;"><?= $dt_count_my_pin->total; ?></h1>
        <h4>Total Marker</h4>
    </div>
    <div class="col-lg-4 col-6 p-2">
        <h1 style="font-size: 60px; font-weight:bold;"><?= $dt_count_my_fav_pin->total; ?></h1>
        <h4>Total Favorite Pin</h4>
    </div>
    <div class="col-lg-4 col-6 p-2">
        <h2 style="font-weight:bold;"><?= $dt_get_last_visit->pin_name ?? '-'; ?></h2>
        <h4>Last Visit</h4>
    </div>

    <div class="col-lg-4 col-6 p-2">
        <h2 style="font-weight:bold;">
            <?php 
                if($dt_get_most_visit){ 
                    echo "($dt_get_most_visit->total) $dt_get_most_visit->context"; 
                } else { 
                    echo "-"; 
                }    
            ?>
        </h2>
        <h4>Most Visit</h4>
    </div>
    <div class="col-lg-4 col-6 p-2">
        <h2 style="font-weight:bold;">
        <?php  
            if($dt_get_most_category){
                echo "($dt_get_most_category->total) $dt_get_most_category->context";
            } else {
                echo "-"; 
            } 
        ?>
        </h2>
        <h4>Most Category</h4>
    </div>
    <div class="col-lg-4 col-6 p-2">
        <h2 style="font-weight:bold;"><?= $dt_get_latest_pin->pin_name ?? "-"; ?></h2>
        <h4>Last Added</h4>
    </div>
    <?php if ($this->session->userdata('role_key') == 0): ?>
        <div class="col-lg-4 col-6 p-2">
            <h1 style="font-size: 60px; font-weight:bold;"><?= $dt_total_user->total ?? "-"; ?></h1>
            <h4>Total User</h4>
        </div>
        <div class="col-lg-4 col-6 p-2">
            <h1 style="font-size: 60px; font-weight:bold;"><?= $dt_avg_gallery_pin->average ?? "-"; ?></h1>
            <h4>Average Gallery / Pin</h4>
        </div>
        <div class="col-lg-4 col-6 p-2">
            <h1 style="font-size: 60px; font-weight:bold;"><?= $dt_avg_pin_user->average ?? "-"; ?></h1>
            <h4>Average Pin / User</h4>
        </div>
        <div class="col-lg-4 col-6 p-2 mx-auto">
            <h1 style="font-size: 60px; font-weight:bold;"><?= $dt_avg_visit_pin->average ?? "-"; ?></h1>
            <h4>Average Visit / Pin</h4>
        </div>
    <?php endif; ?>
</div>