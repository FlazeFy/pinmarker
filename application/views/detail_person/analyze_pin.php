<p class='my-2 fw-bold'>Found On Pin</p>
<?php 
    if(count($dt_pin_by_person) > 0){
        foreach($dt_pin_by_person as $dt){    
            echo "
                <div class='pin-box solid'>
                    <div class='pin_info-holder'>
                        <span class='pin-category me-1'>$dt->pin_category</span>";
                        if($dt->is_favorite == 1){
                            echo "<span class='is-favorite'><i class='fa-solid fa-heart'></i></span>";
                        }
                        if($dt->pin_person){
                            echo "<button class='pin-person ms-1' data-bs-toggle='popover' title='Pin Person' data-bs-content='$dt->pin_person'><i class='fa-solid fa-user'></i></button>";
                        }
                        if($dt->pin_call){
                            echo "<button class='pin-person ms-1' data-bs-toggle='popover' title='Pin Call' data-bs-content='$dt->pin_call'><i class='fa-solid fa-phone'></i></button>";
                        }
                        if($dt->pin_email){
                            echo "<button class='pin-person ms-1' data-bs-toggle='popover' title='Pin Email' data-bs-content='$dt->pin_email'><i class='fa-solid fa-envelope'></i></button>";
                        }
                    echo"
                    </div>
                    <h3>$dt->pin_name</h3>
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
                    <a class='btn btn-primary px-2 py-1 me-2 see-detail-btn' href='/DetailController/view/$dt->id'><i class='fa-solid fa-circle-info'></i> See Detail</a>
                    <a class='btn btn-primary-outline px-2 py-1 set-direction-btn' href='https://www.google.com/maps/dir/My+Location/$dt->pin_lat,$dt->pin_long'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>
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