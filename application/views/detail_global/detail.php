<style>
    .map-board {
        height:30vh;
        border-radius: 15px;
    }
</style>

<?php 
    $view = $this->session->userdata('view_mode_global_list_pin');
?>
<a class="btn btn-dark mb-4 rounded-pill py-3 px-4 me-2" href="/GlobalListController"><i class="fa-solid fa-arrow-left"></i> Back</a>
<div class='pin-box mb-4 no-animation'>
    <span class="d-flex justify-content-between">
        <div>
            <h3><?= $dt_detail->list_name ?></h3>
        </div>
        <div>
            <a class='btn btn-dark rounded-pill px-2 py-1 me-2' href='/GlobalListController'><i class='fa-solid fa-bookmark'></i> Save All to My Pin</a>
            <a class='btn btn-dark rounded-pill px-2 py-1'><i class='fa-solid fa-paper-plane'></i> Share</a>
        </div>
    </span>
    <?php 
        $list_tag = json_decode($dt_detail->list_tag);
        foreach($list_tag as $dt){
            echo "<div class='pin-box-label me-2 mb-1'>#$dt->tag_name</div>";
        }
    ?>
    <br><br>
    <p><?= $dt_detail->list_desc ?></p>
    <?php $this->load->view('detail_global/props'); ?>
    <hr>

    <span class="d-flex justify-content-between">
        <div>
            <h5>List Marker</h5>
        </div>
        <div>
            <form action="/DetailGlobalController/view_global_list_pin/<?= $dt_detail->id ?>" method="POST" class="d-inline">
                <button class='btn btn-dark rounded-pill px-2 py-1'><i class='fa-solid fa-table'></i> See <?php if($view == 'table'){ echo'Catalog'; } else { echo 'Table'; } ?> View</button>
            </form>
            <a class='btn btn-dark rounded-pill px-2 py-1'><i class='fa-solid fa-map'></i> Whole Map</a>
        </div>
    </span>
    <div class="row mt-3 mx-2">
        <?php 
            if($view == 'catalog'){
                foreach($dt_pin_list as $dt){
                    echo "
                        <div class='col-lg-6 col-md-12 col-sm-12 col-12'>
                            <div class='pin-box'>
                                <div id='map-board-$dt->id' class='map-board'></div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        initMap({
                                            coords: {lat: $dt->pin_lat, lng: $dt->pin_long}
                                        }, 'map-board-$dt->id')
                                    })
                                </script>
                                <h3 class='mb-0'>$dt->pin_name</h3>
                                <span class='pin-box-label me-2 mb-3'>$dt->pin_category</span>
                                ";
                                echo "<br>";
                                if($dt->pin_desc){
                                    echo "<p>$dt->pin_desc</p>";
                                } else {
                                    echo "<p class='text-secondary fst-italic'>- No Description -</p>";
                                }
                                    if($dt->pin_call){
                                        echo "
                                            <p class='mt-2 mb-0 fw-bold'>Person In Touch</p>
                                            <p>$dt->pin_call</p>
                                        ";
                                    }
                                    if($dt->pin_address){
                                        echo "
                                            <p class='mt-2 mb-0 fw-bold'>Address</p>
                                            <p>$dt->pin_address</p>
                                        ";
                                    }
                                if(!$is_mobile_device){
                                    echo"
                                    <div class='col-lg-4 col-md-6 col-sm-12'>
                                        <p class='mt-2 mb-0 fw-bold'>Added At</p>
                                        <p class='date-target'>$dt->created_at</p>
                                    </div>";
                                }
                                    echo"
                                <a class='btn btn-dark rounded-pill px-2 py-1 me-2' href='/DetailController/view/$dt->id'><i class='fa-solid fa-bookmark'></i> Save to My Pin</a>
                                <a class='btn btn-dark rounded-pill px-2 py-1'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>
                            </div>
                        </div>
                    ";
                }
            } else {
                    echo "
                        <table class='table table-bordered' id='tb_related_pin_track'>
                            <thead class='text-center'>
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
                                            <td style='width: 200px;'><h6>$dt->pin_name<h6></td>
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
                                                        <p>$dt->pin_address</p>
                                                    ";
                                                }
                                                echo"
                                            </td>
                                            <td>
                                                <p class='mt-2 mb-0 fw-bold'>Category</p>
                                                <p>$dt->pin_category</p>
                                                <p class='mt-2 mb-0 fw-bold'>Description</p>";
                                                if($dt->pin_desc){
                                                    echo "<p>$dt->pin_desc</p>";
                                                } else {
                                                    echo "<p class='text-secondary fst-italic'>- No Description -</p>";
                                                }
                                                if($dt->pin_call){
                                                    echo "
                                                        <p class='mt-2 mb-0 fw-bold'>Person In Touch</p>
                                                        <p>$dt->pin_call</p>
                                                    ";
                                                }
                                                echo"
                                            </td>
                                            <td>
                                                <p class='mt-2 mb-0 fw-bold'>Added At</p>
                                                <p class='date-target'>$dt->created_at</p>
                                            </td>
                                            <td style='width: 160px;'>
                                                <a class='btn btn-dark rounded-pill px-2 py-1 me-2 w-100 mb-2' href='/DetailController/view/$dt->id'><i class='fa-solid fa-bookmark'></i> Save to My Pin</a>
                                                <a class='btn btn-dark rounded-pill px-2 py-1 w-100'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>
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
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

<script>
    function initMap(markerData, mapId) {
        var map = new google.maps.Map(document.getElementById(mapId), {
            center: markerData.coords,
            zoom: 12
        });

        var marker = new google.maps.Marker({
            position: markerData.coords,
            map: map
        });

        if (markerData.content) {
            var infoWindow = new google.maps.InfoWindow({
                content: markerData.content
            });

            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });
        }
    }

    $( document ).ready(function() {
        const date_holder = document.querySelectorAll('.date-target')

        date_holder.forEach(e => {
            const date = new Date(e.textContent);
            e.textContent = getDateToContext(e.textContent, "calendar")
        })
    })
</script>
