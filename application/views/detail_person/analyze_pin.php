<p class='mt-2 mb-0 fw-bold'>Found On Pin</p>
<?php 
    if(count($dt_pin_by_person) > 0){
        foreach($dt_pin_by_person as $dt){    
            echo "
                <div class='pin-box solid'>
                    <h3>$dt->pin_name</h3>
                    <span class='bg-dark rounded-pill px-3 py-2 text-white'>$dt->pin_category</span>
                    ";
                    if($dt->is_favorite == 1){
                        echo "<span class='btn bg-success px-3 py-2 text-white' style='font-size:var(--textXSM);'><i class='fa-solid fa-bookmark'></i></span>";
                    }
                    echo "<br><br>";
                    if($dt->pin_desc){
                        echo "<p>$dt->pin_desc</p>";
                    } else {
                        echo "<p class='text-secondary fst-italic'>- No Description -</p>";
                    }
                    echo "<div class='row py-0 my-0'>";
                    if($dt->pin_person){
                        echo "<div class='col-lg-4 col-md-6 col-sm-12'><p class='mt-2 mb-0 fw-bold'>Person In Touch</p>
                        <p>$dt->pin_person</p></div>";
                    }
                    if($dt->pin_call && !$is_mobile_device){
                        echo "<div class='col-lg-4 col-md-6 col-sm-12'><p class='mt-2 mb-0 fw-bold'>Phone Number</p>
                        <p>$dt->pin_call</p></div>";
                    }
                    if($dt->pin_email && !$is_mobile_device){
                        echo "<div class='col-lg-4 col-md-6 col-sm-12'><p class='mt-2 mb-0 fw-bold'>Email</p>
                        <p>$dt->pin_email</p></div>";
                    }
                    echo"
                    </div>
                    <div class='row py-0 my-0'>";

                    if(!$is_mobile_device){
                        echo"
                        <div class='col-lg-4 col-md-6 col-sm-12'>
                            <p class='mt-2 mb-0 fw-bold'>Created At</p>
                            <p class='date-target'>$dt->created_at</p>
                        </div>
                        <div class='col-lg-4 col-md-6 col-sm-12'>
                            <p class='mt-2 mb-0 fw-bold'>Total Visit</p>
                            <p>$dt->total_visit</p>
                        </div>";
                    }
                        echo"
                        <div class='col-lg-4 col-md-6 col-sm-12'>
                            <p class='mt-2 mb-0 fw-bold'>Last Visit</p>
                            <p class='date-target'>$dt->last_visit</p>
                        </div>
                    </div>
                    <a class='btn btn-dark px-2 py-1 me-2 see-detail-btn' href='/DetailController/view/$dt->id'><i class='fa-solid fa-circle-info'></i> See Detail</a>
                    <a class='btn btn-light px-2 py-1 set-direction-btn' href='https://www.google.com/maps/dir/My+Location/$dt->pin_lat,$dt->pin_long'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>
                </div>
            ";
        }
    } else {
        echo "
            <div class='text-center my-3'>
                <img src='http://127.0.0.1:8080/public/images/pin.png' class='img nodata-icon'>
                <h5>No pin found for this person</h5>
            </div>
        ";
    }
?>