<style>
    #map-board {
        height:70vh;
        border-radius: 0 0 15px 15px;
    }

    /* Maps Dialog */
    .gm-ui-hover-effect {
        background: black !important;
        border-radius: 100%;
        position: absolute !important;
        right: 6px !important;
        top: 6px !important;
    }
    .gm-ui-hover-effect span {
        color: white !important;
    }
    .gm-control-active {
        background: black !important;
        border: 1.75px solid white !important;
        border-radius: 10px !important;
        margin-bottom: 10px !important;
    }
    .gmnoprint div{
        background: transparent !important;
        box-shadow: none !important;
        position: absolute;
        top: -30px;
        right: -15px;
    }
    .gm-control-active span {
        background: white !important;
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
        <?php $this->load->view('maps/search'); ?>
    </div>
    <div class="position-relative">
        <div id="map-board"></div>
    </div>
</div>

<script type="text/javascript">
    let map
    let markers = []
    let polyline
    let is_show_path = <?php if($this->session->userdata('filter_date_track') != null){ echo 'true;'; } else { echo 'false;'; } ?>
    let is_live_tracked = <?php if($this->session->userdata('view_mode_track') == 'track'){ echo 'true;'; } else { echo 'false;'; } ?>
    let is_show_related = <?php if($this->session->userdata('view_mode_track') == 'related_pin'){ echo 'true;'; } else { echo 'false;'; } ?>

    function initMap() {
        // Initialize the map
        map = new google.maps.Map(document.getElementById("map-board"), {
            center: { lat: -6.226838579766097, lng: 106.82157923228753 },
            zoom: 12,
        });

        if (is_show_path == false) {
            getUpdateMarkers()
            setInterval(getUpdateMarkers, 3000)
        }
    }

    function getUpdateMarkers() {
        $.ajax({
            url: `http://127.0.0.1:8000/api/v1/track/journey/<?= $this->session->userdata('user_id'); ?>`,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                // ...
            }
        })
        .done(function (response) {
            let data_track = response.data
            const data_pin = [<?php 
            if($this->session->userdata('view_mode_track') == 'related_pin'){
                foreach($dt_my_pin as $dt){
                    echo "{
                        track_lat: $dt->pin_lat,
                        track_long: $dt->pin_long,
                        name: '$dt->pin_name',
                        color: '$dt->pin_color',
                    },";
                }    
            }
            ?>]

            if(is_show_related == true){
                data_track = data_track.concat(data_pin)
            }
            
            updateMarkers(data_track)
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            // Do something
        });
    }

    function drawPolyline() {
        const pathCoordinates = markers.map(marker => marker.position)

        if (polyline) {
            polyline.setMap(null)
        }

        polyline = new google.maps.Polyline({
            path: pathCoordinates,
            geodesic: true,
            strokeColor: '#F02273',
            strokeOpacity: 1.0,
            strokeWeight: 4,
        });

        polyline.setMap(map)
    }

    function updateMarkers(data) {
        markers.forEach(marker => marker.setMap(null))
        markers = []
        let iconProps = {
            scaledSize: new google.maps.Size(40, 40),
        }

        if (is_show_path) {
            iconProps = { scaledSize: new google.maps.Size(10, 10) }
        }

        console.log(data)

        data.forEach((el, idx) => {
            label = null
            if(is_live_tracked == true){
                if(idx == 0 || idx == data.length - 1){
                    iconProps = {
                        scaledSize: new google.maps.Size(40, 40),
                    } 
                    label = {
                        text: idx == 0 ? 'Start' : 'End',
                        color: '#FFF', 
                        fontSize: '14px', 
                        fontWeight: 'bold',
                    }
                } else {
                    iconProps = {
                        path: google.maps.SymbolPath.CIRCLE,
                        fillColor: '#FF0000',
                        fillOpacity: 1,
                        scale: 3, 
                        strokeColor: '#FF0000',
                        strokeWeight: 1
                    };         
                    label = null
                }
            }
            if(el.color != null){
                iconProps = { 
                    url: `https://maps.google.com/mapfiles/ms/icons/${el.color}.png`,
                    scaledSize: new google.maps.Size(40, 40),
                } 
                label = { 
                    text: el.name,
                    color: '#FFF', 
                    fontSize: '14px', 
                    fontWeight: 'bold',
                }
            }

            const marker = new google.maps.Marker({
                position: { lat: el.track_lat, lng: el.track_long },
                map: map,
                icon: iconProps,
                label: label
            });

            if (el.content) {
                const infoWindow = new google.maps.InfoWindow({
                    content: `<div>
                                <h6>Battery Status : ${el.battery_indicator}%</h6>
                                <p style='font-style: italic;'>Capture at ${el.created_at}</p>
                            </div>`
                });
                marker.addListener('click', function () {
                    infoWindow.open(map, marker)
                });
            }

            markers.push(marker)
        });

        if (is_show_path || is_live_tracked == true) {
            drawPolyline()
        }
    }

    window.initMap = initMap
</script>
