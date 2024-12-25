<style>
    #map-board {
        height:42.5vh;
        border-radius: 15px;
    }
</style>

<p class='my-2 fw-bold'>Maps History</p>
<div class="position-relative">
    <div id="map-board"></div>
</div>

<script type="text/javascript">
    let map;
    let openInfoWindows = []
    let maxOpenInfoWindows = 2

    function initMap() {
        //Map starter
        var markers = [
            <?php 
                foreach($dt_visit_location as $dt){
                    echo "{
                        coords: {lat: $dt->pin_lat, lng: $dt->pin_long},
                        icon: {
                            url: 'https://maps.google.com/mapfiles/ms/icons/$dt->pin_color.png',
                            scaledSize: new google.maps.Size(40, 40),
                        },
                        content: 
                        `<div>
                            <h6>$dt->pin_name</h6>
                            <span class='bg-dark rounded-pill px-2 py-1 text-white'>$dt->pin_category</span>
                            ";
                            if($dt->is_favorite == 1){
                                echo "<span class='btn bg-success px-2 py-1 text-white' style='font-size:var(--textXSM);'><i class='fa-solid fa-bookmark'></i></span>";
                            }
                            echo "<br><br>";
                            if($dt->pin_desc){
                                echo "<p>$dt->pin_desc</p>";
                            } else {
                                echo "<p class='text-secondary fst-italic'>- No Description -</p>";
                            }
                            echo"
                            <div class='d-flex justify-content-between'>
                                <div>
                                    <p class='mt-2 mb-0 fw-bold'>Last Visit</p>
                                    <p class='date-target'>-</p>
                                    </div>
                                <div>
                                    <p class='mt-2 mb-0 fw-bold'>Total Visit</p>
                                    <p class='mb-0'>$dt->total_visit</p>
                                </div>
                            </div>
                            <a class='btn btn-dark px-2 py-1 me-2 see-detail-btn' style='font-size:12px;' href='/DetailController/view/$dt->id'><i class='fa-solid fa-circle-info'></i> See Detail</a>
                            <a class='btn btn-light px-2 py-1 set-direction-btn' style='font-size:12px;' href='https://www.google.com/maps/dir/My+Location/$dt->pin_lat,$dt->pin_long'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>
                        </div>`
                    },";
                }
            ?>
        ];

        map = new google.maps.Map(document.getElementById("map-board"), {
            center: { lat: <?= $dt_visit_location[0]->pin_lat ?>, lng: <?= $dt_visit_location[0]->pin_long ?>},
            zoom: 12,
        });

        <?php 
            if($dt_visit_location){
                $total = count($dt_visit_location);

                for($i = 0; $i < $total; $i++){
                    echo "addMarker(markers[".$i."]);";
                }
            }
        ?>

        function addMarker(props){
            var marker = new google.maps.Marker({
                position: props.coords,
                map: map,
                icon: props.icon
            });

            if(props.iconImage){
                marker.setIcon(props.iconImage);
            }
            if(props.content){
                var infoWindow = new google.maps.InfoWindow({
                content:props.content
            });
            marker.addListener('click', function(){
                if (openInfoWindows.length >= maxOpenInfoWindows) {
                    var oldestInfoWindow = openInfoWindows.shift()
                    oldestInfoWindow.close()
                }
                infoWindow.open(map, marker)
                openInfoWindows.push(infoWindow)
            });
            }
        }
    }

    window.initMap = initMap;
</script>