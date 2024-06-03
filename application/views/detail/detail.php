<style>
    #map-board, #maps-count-distance {
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

<?php $is_edit = $this->session->userdata('is_edit_mode'); ?>

<div class="d-flex justify-content-between mt-4">
    <a class="btn btn-dark mb-4 rounded-pill py-3 px-4 me-2" href="/mapscontroller"><i class="fa-solid fa-arrow-left"></i> Back</a>
    <span>
        <form action="/detailcontroller/edit_toogle/<?= $dt_detail_pin->id ?>" method="POST" class="d-inline">
            <?php 
                if($this->session->userdata('is_edit_mode') == false){
                    echo "<button class='btn btn-light mb-4 rounded-pill py-3 px-4' style='border: 2px solid black;'><i class='fa-solid fa-pen-to-square'></i> Switch to Edit Mode</button>";
                } else {
                    echo "<button class='btn btn-dark mb-4 rounded-pill py-3 px-4'><i class='fa-solid fa-pen-to-square'></i> Switch to View Mode</button>";
                }
            ?>
        </form>
        <form action="/detailcontroller/favorite_toogle/<?= $dt_detail_pin->id ?>" method="POST" class="d-inline">
            <?php 
                if($dt_detail_pin->is_favorite == '1'){
                    echo "<input name='is_favorite' value='0' hidden><button class='btn btn-dark mb-4 rounded-pill py-3 px-4 me-2'><i class='fa-solid fa-heart'></i> Saved to Favorite</button>";
                } else {
                    echo "<input name='is_favorite' value='1' hidden><button class='btn btn-light mb-4 rounded-pill py-3 px-4' style='border: 2px solid black;'><i class='fa-solid fa-heart'></i> Add To Favorite</button>";
                }
            ?>
        </form>
    </span>
</div>

<?php 
    if($is_edit){
        echo "
            <div class='row'>
                <div class='col-lg-6 col-md-6 col-sm-12'>
                    <p class='mt-2 mb-0 fw-bold'>Pin Name</p>
                    <input name='pin_name' id='pin_name' type='text' class='form-control' value="; echo '"'.$dt_detail_pin->pin_name.'"'; echo" required/>
                    <a class='msg-error-input'></a>
                </div>
                <div class='col-lg-6 col-md-6 col-sm-12'>
                    <p class='mt-2 mb-0 fw-bold'>Pin Category</p>
                    <select name='pin_category' class='form-select' id='pin_category'>";
                        foreach($dt_dct_pin_category as $dt){
                            echo "<option value='$dt->dictionary_name-$dt->dictionary_color'";
                            if($dt->id == $dt_detail_pin->id){
                                echo " selected>$dt->dictionary_name</option>";
                            } else {
                                echo ">$dt->dictionary_name</option>";
                            }
                        }
                    echo"</select>
                    <a class='msg-error-input'></a>
                </div>
            </div>";
    } else {
        echo "<h2 class='text-center' style='font-weight:600;'>$dt_detail_pin->pin_name 
            <span class='bg-dark rounded-pill text-light px-3 py-2' style='font-size: 16px;'>$dt_detail_pin->pin_category</span>
        </h2>";
    }
?>

<div class="row mt-4">
    <div class="col-lg-6 col-md-6 col=sm-12">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <p class='mt-2 mb-0 fw-bold'>Latitude</p>
                <?php 
                    if($is_edit){
                        echo "<input name='pin_lat' id='pin_lat' type='text' class='form-control' value='$dt_detail_pin->pin_lat' required/>
                            <a class='msg-error-input'></a>";
                    } else {
                        echo "<p>$dt_detail_pin->pin_lat</p>";
                    }
                ?>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <p class='mt-2 mb-0 fw-bold'>Longitude</p>
                <?php 
                    if($is_edit){
                        echo "<input name='pin_long' id='pin_long' type='text' class='form-control' value='$dt_detail_pin->pin_long' required/>
                            <a class='msg-error-input'></a>";
                    } else {
                        echo "<p>$dt_detail_pin->pin_long</p>";
                    }
                ?>
            </div>
        </div>

        <p class='mt-2 mb-0 fw-bold'>Person In Touch</p>
        <?php 
            if($is_edit){
                echo "<input name='pin_name' id='pin_name' type='text' class='form-control' value='$dt_detail_pin->pin_person' required/>
                    <a class='msg-error-input'></a>";
            } else {
                if($dt_detail_pin->pin_person != null){ 
                    echo "<p>$dt_detail_pin->pin_person</p>";
                } else {
                    echo "<p>-</p>";
                }
            }
        ?>

        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <p class='mt-2 mb-0 fw-bold'>Email</p>
                <?php 
                    if($is_edit){
                        echo "<input name='pin_email' id='pin_email' type='email' class='form-control' value='$dt_detail_pin->pin_email'/>
                            <a class='msg-error-input'></a>";
                    } else {
                        if($dt_detail_pin->pin_email != null){ 
                            echo "<p>$dt_detail_pin->pin_email</p>";
                        } else {
                            echo "<p>-</p>";
                        }
                    }
                ?>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <p class='mt-2 mb-0 fw-bold'>Phone Number</p>
                <?php 
                    if($is_edit){
                        echo "<input name='pin_call' id='pin_call' type='phone' class='form-control' value='$dt_detail_pin->pin_call'/>
                            <a class='msg-error-input'></a>";
                    } else {
                        if($dt_detail_pin->pin_call != null){ 
                            echo "<p>$dt_detail_pin->pin_call</p>";
                        } else {
                            echo "<p>-</p>";
                        }
                    }
                ?>
            </div>
        </div>

        <p class='mt-2 mb-0 fw-bold'>Address</p>
        <?php 
            if($is_edit){
                echo "<textarea name='pin_address' id='pin_address' rows='5' class='form-control'>$dt_detail_pin->pin_address</textarea>
                    <a class='msg-error-input'></a>";
            } else {
                if($dt_detail_pin->pin_address != null){
                    echo "<p>$dt_detail_pin->pin_address</p>";
                } else {
                    echo '<p>-</p>';
                }
            }
        ?>

        <p class='mt-2 mb-0 fw-bold'>Description</p>
        <?php 
            if($is_edit){
                echo "<textarea name='pin_desc' id='pin_desc' rows='5' class='form-control'>$dt_detail_pin->pin_desc</textarea>
                    <a class='msg-error-input'></a>";
            } else {
                if($dt_detail_pin->pin_desc != null){
                    echo "<p>$dt_detail_pin->pin_desc</p>";
                } else {
                    echo '<p class="text-secondary fst-italic">- No Description Provided -</p>';
                }
            }
        ?>

        <?php 
            if($is_edit){
                echo "<button class='btn btn-dark rounded-pill w-100 py-3 my-4' type='Submit'><i class='fa-solid fa-floppy-disk'></i> Save Marker</button>";
            } 
        ?>

        <hr><p class='mt-2 mb-0 fw-bold'>Visit History</p>
        <ol>
        <?php 
            if(count($dt_visit_history) > 0){
                foreach($dt_visit_history as $dt){
                    echo "<li>$dt->visit_desc using "; echo strtolower($dt->visit_by);
                        if($dt->visit_with != null){
                            echo " with $dt->visit_with";
                        }    
                    echo " at "; echo date('Y-m-d H:i', strtotime($dt->created_at)); echo"</li>";
                }
            } else {
                echo "
                    <div class='text-center text-secondary'>
                        <img class='img img-fluid m-1' style='width:200px;' src='http://127.0.0.1:8080/public/images/empty_item.png'>
                        <h6>No History Visit found on this Pin</h6>
                    </div>
                ";
            }
        ?>
        </ol>
        <hr>

        <?php
            $stats['data'] = $dt_total_visit_by_by_pin;
            $stats['ctx'] = 'visit_using_stats';
            $this->load->view('others/pie_chart', $stats);
        ?>
        <hr>

        <p class='mt-2 mb-0 fw-bold'>Count Distance to Other Pin</p>
        <?php $this->load->view('detail/count_distance'); ?>
        <hr>

        <div class="d-flex justify-content-between mt-2 mb-0">
            <p class='fw-bold mt-1'>Galleries</p>
            <?php $this->load->view('detail/add_galleries'); ?>
        </div>
        <?php $this->load->view('detail/galleries'); ?>
        <hr>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div id="map-board"></div>
        <hr>
        <p class='mt-2 mb-0 fw-bold'>Distance to My Personal Pin</p>
        <?php $this->load->view('detail/distance'); ?>
    </div>
</div>

<script type="text/javascript">
    let map;
    let markers = [
        <?php 
            echo "{
                coords: {lat: $dt_detail_pin->pin_lat, lng: $dt_detail_pin->pin_long},
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/$dt_detail_pin->pin_color.png',
                    scaledSize: {width: 40, height: 40}
                }
            }";
        ?>
    ]

    function initMap() {
        map = new google.maps.Map(document.getElementById("map-board"), {
            center: { lat: parseFloat(<?= $dt_detail_pin->pin_lat ?>), lng: parseFloat(<?= $dt_detail_pin->pin_long ?>) },
            zoom: 12,
        });

        for (let i = 0; i < markers.length; i++) {
            addMarker(markers[i])
        }

        if (markers.length > 1) {
            drawPolyline()
        }
    }

    function drawPolyline() {
        const pathCoordinates = markers.map(marker => marker.coords)

        const polyline = new google.maps.Polyline({
            path: pathCoordinates,
            geodesic: true,
            strokeColor: '#F02273',
            strokeOpacity: 1.0,
            strokeWeight: 4,
        })

        polyline.setMap(map)
    }

    function addMarker(props) {
        let marker = new google.maps.Marker({
            position: props.coords,
            map: map,
            icon: props.icon
        });

        if (props.iconImage) {
            marker.setIcon(props.iconImage)
        }

        if (props.content) {
            let infoWindow = new google.maps.InfoWindow({
                content: props.content
            });

            marker.addListener('click', function() {
                infoWindow.open(map, marker)
            });
        }
    }

    window.initMap = initMap
</script>
