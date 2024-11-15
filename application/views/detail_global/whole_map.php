<style>
    #whole-map-board {
        height:70vh;
        border-radius: 0 0 15px 15px;
        width: 100%;
    }
</style>


<a class='btn btn-dark px-3 py-2' id='whole-map-modal-btn' data-bs-toggle="modal" data-bs-target="#wholeMapModal"><i class='fa-solid fa-map'></i> <?php if(!$is_mobile_device){ echo "Whole Map"; } ?></a>

<div class="modal fade" id="wholeMapModal" tabindex="-1" aria-labelledby="addGalleriesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGalleriesLabel">All Location of <b><?= $dt_detail->list_name ?></b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id='close-whole-map-modal-btn'></button>
            </div>
            <div class="modal-body">
                <div id="whole-map-board"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    let whole_map;
    let openInfoWindows = []
    let maxOpenInfoWindows = 2

    function initWholeMap() {
        //Map starter
        var markers = [
            <?php 
                foreach($dt_pin_list as $dt){
                    echo "{
                        coords: {lat: $dt->pin_lat, lng: $dt->pin_long},
                        icon: {
                            url: 'https://maps.google.com/mapfiles/ms/icons/red.png',
                            scaledSize: new google.maps.Size(40, 40),
                        },
                        content: 
                        `<div>
                            <h6>$dt->pin_name</h6>
                            <span class='bg-dark px-2 py-1 text-white rounded-pill'>$dt->pin_category</span>
                            <br><br>";
                            if($dt->pin_desc){
                                echo "<p>$dt->pin_desc</p>";
                            } else {
                                echo "<p class='text-secondary fst-italic'>- No Description -</p>";
                            }
                            echo"
                            <p class='mt-2 mb-0 fw-bold'>Created At</p>
                            <p class='date-target'>$dt->created_at</p>
                            <a class='btn btn-light px-2 py-1' style='font-size:12px;' href='https://www.google.com/maps/dir/My+Location/$dt->pin_lat,$dt->pin_long' id='set-direction-whole-map-btn'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>
                        </div>`
                    },";
                }
            ?>
        ];

        whole_map = new google.maps.Map(document.getElementById("whole-map-board"), {
            center: { lat: -6.226838579766097, lng: 106.82157923228753},
            zoom: 12,
        });

        <?php 
            if($dt_pin_list){
                $total = count($dt_pin_list);

                for($i = 0; $i < $total; $i++){
                    echo "addMarker(markers[".$i."]);";
                }
            }
        ?>

        function addMarker(props){
            var marker = new google.maps.Marker({
                position: props.coords,
                map: whole_map,
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
                infoWindow.open(whole_map, marker)
                openInfoWindows.push(infoWindow)
            });
            }
        }
    }

    window.initMap = initWholeMap;
</script>