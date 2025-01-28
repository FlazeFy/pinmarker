<div class="row mt-3 <?php if($view == 'catalog'){ echo 'grid';} ?>">
    <?php 
        if($view == 'catalog'){
            foreach($dt_pin_list as $dt){
                echo "
                    <div class='col-lg-6 col-md-12 col-sm-12 col-12 grid-item'>
                        <input hidden class='pin-lat' value='$dt->pin_lat'>
                        <input hidden class='pin-long' value='$dt->pin_long'>
                        <div class='pin-box solid'>
                            <div id='map-board-$dt->id' class='map-board'></div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    initMap({
                                        coords: {lat: $dt->pin_lat, lng: $dt->pin_long}
                                    }, 'map-board-$dt->id')
                                })
                            </script>
                            <h3 class='mb-0 pin-name'>$dt->pin_name</h3>
                            <span class='pin-box-label me-2 mb-3 pin-category'>$dt->pin_category</span>
                            ";
                            echo "<br>";
                            if($dt->pin_desc){
                                echo "<p class='pin-desc'>$dt->pin_desc</p>";
                            } else {
                                echo "<p class='text-secondary fst-italic pin-desc'>- No Description -</p>";
                            }
                                if($dt->pin_call){
                                    echo "
                                        <p class='mt-2 mb-0 fw-bold'>Contact</p>
                                        <p class='pin-call'>$dt->pin_call</p>
                                    ";
                                }
                                if($dt->pin_address){
                                    echo "
                                        <p class='mt-2 mb-0 fw-bold'>Address</p>
                                        <p class='pin-address'>$dt->pin_address</p>
                                    ";
                                }
                            echo"
                                <p class='mt-2 mb-0 fw-bold'>Added At</p>
                                <p>
                                    <span class='date-target'>$dt->created_at</span> 
                                    <span>by <button class='btn-account-attach'>@$dt_detail->created_by</button></span>
                                </p>
                            ";

                            $data['dt'] = $dt;
                            $this->load->view('detail_global/gallery',$data);

                            if($is_signed){
                                echo "<a class='btn btn-success px-3 py-2 me-1 save-to-my-pin-btn'><i class='fa-solid fa-bookmark'></i> "; if(!$is_mobile_device){ echo "Save to My Pin"; } echo"</a>";
                            }
                            if($is_editable){
                                echo "<a class='btn btn-danger px-3 py-2 me-1 remove-pin-btn' onclick='remove_pin("; echo '"'; echo $dt->id; echo '"'; echo")'><i class='fa-solid fa-trash'></i> "; if(!$is_mobile_device){ echo "Remove"; } echo"</a>";
                            }
                            echo"
                            <a class='btn btn-light px-3 py-2 me-1 set-direction-btn' href='https://www.google.com/maps/dir/My+Location/$dt->pin_lat,$dt->pin_long'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>
                        </div>
                    </div>
                ";
            }
        } else {
                echo "
                    <table class='table table-bordered' id='tb_related_pin_track'>
                        <thead>
                            <tr>
                                <th scope='col'>Name</th>
                                <th scope='col'>Location</th>
                                <th scope='col'>Context</th>
                                <th scope='col'>Props</th>
                                <th scope='col'>Action</th>
                            </tr>
                        </thead>
                        <tbody>";
                            foreach($dt_pin_list as $dt){
                                echo "
                                    <tr>
                                        <td style='width: 200px;'><h6 class='pin-name'>$dt->pin_name<h6></td>
                                        <td style='width: 450px;'>
                                            <div id='map-board-$dt->id' class='map-board'></div>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    initMap({
                                                        coords: {lat: $dt->pin_lat, lng: $dt->pin_long}
                                                    }, 'map-board-$dt->id')
                                                })
                                            </script>";
                                            if($dt->pin_address){
                                                echo "
                                                    <p class='mt-2 mb-0 fw-bold'>Address</p>
                                                    <p class='pin-address'>$dt->pin_address</p>
                                                ";
                                            }

                                            $data['dt'] = $dt;
                                            $this->load->view('detail_global/gallery',$data);
                                            
                                            echo"
                                        </td>
                                        <td>
                                            <input hidden class='pin-lat' value='$dt->pin_lat'>
                                            <input hidden class='pin-long' value='$dt->pin_long'>
                                            <p class='mt-2 mb-0 fw-bold'>Category</p>
                                            <p class='pin-category'>$dt->pin_category</p>
                                            <p class='mt-2 mb-0 fw-bold'>Description</p>";
                                            if($dt->pin_desc){
                                                echo "<p class='pin-desc'>$dt->pin_desc</p>";
                                            } else {
                                                echo "<p class='text-secondary pin-desc fst-italic'>- No Description -</p>";
                                            }
                                            if($dt->pin_call){
                                                echo "
                                                    <p class='mt-2 mb-0 fw-bold'>Contact</p>
                                                    <p class='pin-call'>$dt->pin_call</p>
                                                ";
                                            }
                                            echo"
                                        </td>
                                        <td>
                                            <p class='mt-2 mb-0 fw-bold'>Added At</p>
                                            <p class='date-target'>$dt->created_at</p>
                                            <p class='mt-2 mb-0 fw-bold'>Added By</p>
                                            <button class='btn-account-attach'>@$dt_detail->created_by</button>
                                        </td>
                                        <td style='width: 160px;'>";
                                            if($is_signed){
                                                echo "<a class='btn btn-success px-2 py-1 me-2 w-100 mb-2 save-to-my-pin-btn'><i class='fa-solid fa-bookmark'></i> Save to My Pin</a>";
                                            }
                                            echo "
                                            <a class='btn btn-light px-2 py-1 w-100 mb-2 set-direction-btn' href='https://www.google.com/maps/dir/My+Location/$dt->pin_lat,$dt->pin_long'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>";
                                            if($is_editable){
                                                echo "<a class='btn btn-danger px-2 py-1 w-100 remove-pin-btn' onclick='remove_pin("; echo '"'; echo $dt->id; echo '"'; echo")'><i class='fa-solid fa-trash'></i> Remove</a>";
                                            }
                                            echo "
                                        </td>
                                    </tr>
                                "; 
                            }
                        echo"</tbody>
                    </table>
                ";
        }
    ?>
</div>