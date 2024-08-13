<div class="mt-2">
    <?php 
        function calculateDistance($lat1, $lon1, $lat2, $lon2, $unit = 'km') {
            $theta = $lon1 - $lon2;
            $distance = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $distance = acos($distance);
            $distance = rad2deg($distance);
            $distance = $distance * 60 * 1.1515; 

            if ($unit == 'km') {
                $distance = $distance * 1.609344;
            }

            $distance = number_format($distance, 2);
            
            return $distance;
        }

        if(count($dt_my_personal_pin) > 0){
            foreach($dt_my_personal_pin as $dt){
                echo "
                    <div class='p-3 mb-2' style='border: 2px solid black; border-radius: 15px;'>
                        <div class='row'>
                            <div class='col-7'>
                                <h5>$dt->pin_name</h5>";
                                if($dt->pin_desc){
                                    echo "<h6 class='mb-0'>Description</h6><p>$dt->pin_desc</p>";
                                } else {
                                    echo "<p class='text-secondary fst-italic'>- No Description -</p>";
                                }
                            echo "
                            </div>
                            <div class='col-5'>
                                <h6 class='mb-0'>Distance To</h6>
                                <h3>"; echo calculateDistance($dt->pin_lat, $dt->pin_long, $dt_detail_pin->pin_lat, $dt_detail_pin->pin_long, $unit = 'km'); echo" Km</h3>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-4'>
                                <h6 class='mb-0'>Created At</h6>
                                <p class='mb-0 date-target'>$dt->created_at</p>
                            </div>
                            <div class='col-8'>
                                <a class='btn btn-dark rounded-pill px-2 py-1 me-2' href='/DetailController/view/$dt->id'><i class='fa-solid fa-circle-info'></i> See Detail</a>
                                <a class='btn btn-dark rounded-pill px-2 py-1' href='https://www.google.com/maps/dir/$dt_detail_pin->pin_lat,$dt_detail_pin->pin_long/$dt->pin_lat,$dt->pin_long'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>
                            </div>
                        </div>
                    </div>
                ";
            }
        } else {
            echo "
                <div class='text-center text-secondary'>
                    <img class='img img-fluid m-1' style='width:200px;' src='http://127.0.0.1:8080/public/images/empty_item.png'>
                    <h6>No other personal pin found</h6>
                </div>
            ";
        }
    ?>
</div>