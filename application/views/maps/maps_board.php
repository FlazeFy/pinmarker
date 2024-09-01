<style>
    #map-board {
        height:70vh;
        border-radius: 0 0 15px 15px;
    }

    .maps-toolbar {
        border-radius: 20px;
        border: 5px solid black;
        padding: 0 !important;
        background: black;
    }
    .maps-toolbar button {
        margin: 10px !important;
        border-radius: 10px !important;
    }
</style>

<div class="maps-toolbar">
    <div class="d-flex justify-content-end">
        <?php $this->load->view('maps/filter_category'); ?>
        <?php $this->load->view('maps/search'); ?>
    </div>
    <div class="position-relative">
        <div id="map-board"></div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

<script type="text/javascript">
    let map;
    let openInfoWindows = []
    let maxOpenInfoWindows = 2

    function initMap() {
        //Map starter
        var markers = [
            <?php 
                foreach($dt_my_pin as $dt){
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
                                echo "<span class='bg-dark rounded-pill px-2 py-1 text-white'><i class='fa-solid fa-bookmark'></i></span>";
                            }
                            echo "<br><br>";
                            if($dt->pin_desc){
                                echo "<p>$dt->pin_desc</p>";
                            } else {
                                echo "<p class='text-secondary fst-italic'>- No Description -</p>";
                            }
                            if($dt->pin_person){
                                echo "<p class='mt-2 mb-0 fw-bold'>Person In Touch</p>
                                <p>$dt->pin_person</p>";
                            }
                            echo"
                            <p class='mt-2 mb-0 fw-bold'>Created At</p>
                            <p class='date-target'>$dt->created_at</p>
                            <a class='btn btn-dark rounded-pill px-2 py-1 me-2' style='font-size:12px;' href='/DetailController/view/$dt->id'><i class='fa-solid fa-circle-info'></i> See Detail</a>
                            <a class='btn btn-dark rounded-pill px-2 py-1' style='font-size:12px;' href='https://www.google.com/maps/dir/My+Location/$dt->pin_lat,$dt->pin_long'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>
                        </div>`
                    },";
                }
            ?>
        ];

        map = new google.maps.Map(document.getElementById("map-board"), {
            center: <?php 
                $search_pin_name = $this->session->userdata('search_pin_name_key');
                if($search_pin_name != null && $search_pin_name != ""){
                    echo "{ lat: "; echo $dt_my_pin[0]->pin_lat; echo", lng: "; echo $dt_my_pin[0]->pin_long; echo"}";
                } else {
                    echo "{ lat: -6.226838579766097, lng: 106.82157923228753}";
                }
            ?>,
            zoom: 12,
        });

        <?php 
            if($dt_my_pin){
                $total = count($dt_my_pin);

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

    $( document ).ready(function() {
        const date_holder = document.querySelectorAll('.date-target');

        date_holder.forEach(e => {
            const date = new Date(e.textContent);
            e.textContent = getDateToContext(e.textContent, "calendar");
        });
    });

    window.initMap = initMap;
</script>