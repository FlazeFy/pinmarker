<style>
    #map-board {
        height:50vh;
        border-radius: 20px;
        margin-bottom: 6px;
        border: 5px solid black;
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
    }
    .gm-control-active span {
        background: white !important;
    }
</style>

<div class="position-relative">
    <div id="map-board"></div>
</div>

<script type="text/javascript">
    let map
    let selected_color = ''

    function initMap() {
        map = new google.maps.Map(document.getElementById("map-board"), {
            center: { lat: -6.226838579766097, lng: 106.82157923228753},
            zoom: 12,
        });

        map.addListener("click", (e) => {
            initMap()
            placeMarkerAndPanTo(e.latLng, map)
            addContentCoor(e.latLng)
        });
    }

    function placeMarkerAndPanTo(latLng, map) {
        if(selected_color == ''){
            const val = document.getElementById("pin_category").value
            const split_val = val.split('-')
            const color = split_val[1]
            selected_color = color
        }

        new google.maps.Marker({
            position: latLng,
            map: map,
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/'+selected_color+'-dot.png',
                scaledSize: new google.maps.Size(40, 40),
            }
        });
        map.panTo(latLng)
    }

    function addContentCoor(coor){
        coor = coor.toJSON()
        document.getElementById('pin_lat').value = coor['lat']
        document.getElementById('pin_long').value = coor['lng']
    }

    window.initMap = initMap;
</script>