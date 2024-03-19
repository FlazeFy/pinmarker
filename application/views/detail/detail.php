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
        position: absolute;
        top: -30px;
        right: -15px;
    }
    .gm-control-active span {
        background: white !important;
    }
</style>

<h2 class="text-center" style="font-weight:600;"><?= $dt_detail_pin->pin_name; ?> 
    <span class="bg-dark rounded-pill text-light px-3 py-2" style="font-size: 16px;"><?= $dt_detail_pin->pin_category; ?></span>
</h2>
<a class="btn btn-dark mb-4 rounded-pill py-3 px-4" href="/mapscontroller"><i class="fa-solid fa-arrow-left"></i> Back</a>
<div class="row">
    <div class="col-lg-6 col-md-6 col=sm-12">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <p class='mt-2 mb-0 fw-bold'>Latitude</p>
                <p><?= $dt_detail_pin->pin_lat ?></p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <p class='mt-2 mb-0 fw-bold'>Longitude</p>
                <p><?= $dt_detail_pin->pin_long ?></p>
            </div>
        </div>

        <p class='mt-2 mb-0 fw-bold'>Person In Touch</p>
        <p><?php 
            if($dt_detail_pin->pin_person != null){ 
                echo $dt_detail_pin->pin_person;
            } else {
                echo "-";
            }
        ?></p>

        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <p class='mt-2 mb-0 fw-bold'>Email</p>
                <p><?php 
                    if($dt_detail_pin->pin_email != null){ 
                        echo $dt_detail_pin->pin_email;
                    } else {
                        echo "-";
                    }
                ?></p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <p class='mt-2 mb-0 fw-bold'>Phone Number</p>
                <p><?php 
                    if($dt_detail_pin->pin_call != null){ 
                        echo $dt_detail_pin->pin_call;
                    } else {
                        echo "-";
                    }
                ?></p>
            </div>
        </div>

        <p class='mt-2 mb-0 fw-bold'>Address</p>
        <?php 
            if($dt_detail_pin->pin_address != null){
                echo "<p>$dt_detail_pin->pin_address</p>";
            } else {
                echo '<p>-</p>';
            }
        ?>

        <p class='mt-2 mb-0 fw-bold'>Description</p>
        <?php 
            if($dt_detail_pin->pin_desc != null){
                echo "<p>$dt_detail_pin->pin_desc</p>";
            } else {
                echo '<p class="text-secondary fst-italic">- No Description Provided -</p>';
            }
        ?>

        <p class='mt-2 mb-0 fw-bold'>Visit History</p>
        <ol>
        <?php 
            foreach($dt_visit_history as $dt){
                echo "<li>$dt->visit_desc using "; echo strtolower($dt->visit_by);
                    if($dt->visit_with != null){
                        echo " with $dt->visit_with";
                    }    
                echo " at "; echo date('Y-m-d H:i', strtotime($dt->created_at)); echo"</li>";
            }
        ?>
        </ol>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div id="map-board"></div>

        <p class='mt-2 mb-0 fw-bold'>Distance to My Personal Pin</p>
        <?php $this->load->view('detail/distance'); ?>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

<script type="text/javascript">
    let map;

    function initMap() {
        //Map starter
        var markers = [
            <?php 
                echo "{
                    coords: {lat: $dt_detail_pin->pin_lat, lng: $dt_detail_pin->pin_long},
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/$dt_detail_pin->pin_color.png',
                        scaledSize: new google.maps.Size(40, 40)
                    }
                }";
            ?>
        ];

        map = new google.maps.Map(document.getElementById("map-board"), {
            center: <?= 
                "{ lat: $dt_detail_pin->pin_lat, lng: $dt_detail_pin->pin_long}";
            ?>,
            zoom: 12,
        });

        addMarker(markers[0]);

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
            }
        }
    }

    window.initMap = initMap;
</script>