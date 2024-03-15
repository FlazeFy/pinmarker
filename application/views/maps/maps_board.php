<style>
    #map-board {
        height:70vh;
        border-radius: 20px;
        margin-top: 6px;
        margin-bottom: 6px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }
</style>

<div class="position-relative">
    <div id="map-board"></div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

<script type="text/javascript">
    let map;

    function initMap() {
        //Map starter
        var markers = [
            <?php 
                echo '{
                    coords: {lat: 51.514881121099904, lng: -0.09693947143827275},
                    content: `<div><h6>Test</h6></div>`
                },';
            ?>
        ];

        map = new google.maps.Map(document.getElementById("map-board"), {
            center: { lat: -6.969350413790824, lng: 107.62818479205987},
            zoom: 15,
        });

        addMarker();

        function addMarker(props){
            var marker = new google.maps.Marker({
                position:props.coords,
                map:map,
            });

            if(props.iconImage){
                marker.setIcon(props.iconImage);
            }
            if(props.content){
                var infoWindow = new google.maps.InfoWindow({
                content:props.content
            });
            marker.addListener('click', function(){
                infoWindow.open(map, marker);
            });
            }
        }
    }

    window.initMap = initMap;
</script>