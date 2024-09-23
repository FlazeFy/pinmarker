<style>
    #map-board {
        height:92vh;
    }
    .maps-toolbar {
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
    <div class="d-flex justify-content-between">
        <a class="btn btn-danger rounded-pill py-2 px-4 m-2" href="<?php if($dt_active_search){ echo"/LoginController/view/$dt_active_search"; } else { echo"/"; }?>" id="back-page-btn" style="height:50px;"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <h3 class="text-white mt-3 ms-3">Global Maps</h3>
        <div class="d-flex justify-content-end">
            <?php $this->load->view('maps/filter_category'); ?>
        </div>
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
        var markers = [];

        map = new google.maps.Map(document.getElementById("map-board"), {
            center: { lat: -6.226838579766097, lng: 106.82157923228753},
            zoom: 12,
        });

        addMarker(markers[".$i."])

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