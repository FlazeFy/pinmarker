<style>
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
<div class="dash-welcome">
    <div class="row">
        <div class="col-md-6">

        </div>
        <div class="col-md-6 text-start">
            <h1 class="fw-bold" style="font-size: 56px;">Hello, <?= $dt_my_profile->username ?></h1>
            <h5 class="text-secondary fw-normal mb-3">Here's what we gathered from your mark list and visit history:
                Your last visit was to <b><?php 
                    if($dt_get_last_visit && $dt_get_last_visit->pin_name){
                        echo $dt_get_last_visit->pin_name;
                    } else if($dt_get_last_visit && $dt_get_last_visit->visit_desc){
                        echo $dt_get_last_visit->visit_desc;
                    } else {
                        echo "-";
                    } 
                ?></b>, and your most frequently visited location is <b><?php 
                    if($dt_get_most_visit){ 
                        echo $dt_get_most_visit->context; 
                    } else { 
                        echo "-"; 
                    }    
                ?></b>, with a total of <b><?php 
                    if($dt_get_most_visit){ 
                        echo $dt_get_most_visit->total; 
                    } else { 
                        echo "-"; 
                    }    
                ?></b> visits.
                According to your mark list, most of your marks are categorized as <b><?php  
                    if($dt_get_most_category){
                        echo "($dt_get_most_category->total) $dt_get_most_category->context";
                    } else {
                        echo "-"; 
                    } 
                ?></b>, and the last location you marked was <b><?= $dt_get_latest_pin->pin_name ?? "-"; ?></b>.
            </h5>
            <div class="row mb-3">
                <div class="col-lg-4 col-6 p-2">
                    <div class="container-fluid text-center shadow">
                        <h1 style="font-size: 40px; font-weight:bold;"><?= $dt_count_my_pin->total; ?></h1>
                        <h5 class="text-secondary fw-normal">Total Marker</h5>
                    </div>
                </div>
                <div class="col-lg-4 col-6 p-2">
                    <div class="container-fluid text-center shadow">
                        <h1 style="font-size: 40px; font-weight:bold;"><?= $dt_count_my_fav_pin->total; ?></h1>
                        <h5 class="text-secondary fw-normal">Favorite Pin</h5>
                    </div>
                </div>
            </div>
            <a class="btn btn-link px-3" style="width:fit-content;" href="#statistic-holder"><i class="fa-solid fa-arrow-down"></i> See More Stats</a>
        </div>
    </div>
</div>