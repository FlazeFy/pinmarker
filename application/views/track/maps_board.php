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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

<script type="text/javascript">
    let map;
    let markers = [];

    function initMap() {
        // Initialize the map
        map = new google.maps.Map(document.getElementById("map-board"), {
            center: { lat: -6.226838579766097, lng: 106.82157923228753 },
            zoom: 12,
        })

        getUpdateMarkers()
        setInterval(getUpdateMarkers, 3000)
    }

    function getUpdateMarkers() {
        $.ajax({
            url: `http://127.0.0.1:2000/api/v1/track/journey/<?= $this->session->userdata('user_id'); ?>`,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                // ...
            }
        })
        .done(function (response) {
            let data = response.data
            updateMarkers(data)
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            // Do something
        });
    }

    function updateMarkers(data) {
        markers.forEach(marker => marker.setMap(null));
        markers = [];

        data.forEach(el => {
            const marker = new google.maps.Marker({
                position: { lat: el.track_lat, lng: el.track_long },
                map: map,
                icon: {
                    scaledSize: new google.maps.Size(40, 40),
                },
            });

            if (el.content) {
                const infoWindow = new google.maps.InfoWindow({
                    content: `<div>
                                <h6>Battery Status : ${el.battery_indicator}%</h6>
                                <p style='font-style: italic;'>Capture at ${el.created_at}%</p>
                              </div>`
                });
                marker.addListener('click', function () {
                    infoWindow.open(map, marker)
                });
            }

            markers.push(marker)
        });
    }

    window.initMap = initMap
</script>