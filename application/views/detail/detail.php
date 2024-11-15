<?php $is_edit = $this->session->userdata('is_edit_mode'); ?>
<div class="d-flex justify-content-between mt-4">
    <a class="btn btn-danger mb-4 py-3 px-4 me-2" href="/MapsController" id="back-page-btn"><i class="fa-solid fa-arrow-left"></i><?php if (!$is_mobile_device){ echo " Back"; } ?></a>
    <span>
        <a class='btn btn-light mb-4 py-3 px-4 me-1' href="/CustomDocController/view/<?= $dt_detail_pin->id ?>"><i class='fa-solid fa-print'></i><?php if(!$is_mobile_device){ echo " Custom Print"; } else { echo " Custom"; }?></a>
        <?php $this->load->view('detail/print'); ?>
        <?php $this->load->view('detail/edit_toggle'); ?>
        <?php $this->load->view('detail/favorite_toggle'); ?>
        <?php $this->load->view('detail/delete'); ?>
    </span>
</div>

<form action="/DetailController/edit_marker/<?= $dt_detail_pin->id ?>" method="POST">
<?php 
    if($this->session->flashdata('validation_error')){
        echo "
            <div class='alert alert-danger' role='alert'>
                <h5><i class='fa-solid fa-triangle-exclamation'></i> Error</h5>
                ".$this->session->flashdata('validation_error')."
            </div>
        "; 
    }
?>

<?php 
    if($is_edit){
        echo "
            <div class='row'>
                <div class='col-lg-6 col-md-6 col-sm-12'>
                    <p class='mt-2 mb-0 fw-bold'>Pin Name</p>
                    <input name='pin_name' id='pin_name' type='text' class='form-control' value='$dt_detail_pin->pin_name' required/>
                </div>
                <div class='col-lg-6 col-md-6 col-sm-12'>
                    <p class='mt-2 mb-0 fw-bold'>Pin Category</p>
                    <select name='pin_category' class='form-select' id='pin_category'>";
                        foreach($dt_dct_pin_category as $dt){
                            echo "<option value='$dt->dictionary_name-$dt->dictionary_color'";
                            if($dt->dictionary_name == $dt_detail_pin->pin_category){
                                echo " selected>$dt->dictionary_name</option>";
                            } else {
                                echo ">$dt->dictionary_name</option>";
                            }
                        }
                    echo"</select>
                </div>
            </div>";
    } else {
        echo "<h2 class='text-center' style='font-weight:600;'>$dt_detail_pin->pin_name 
            <span class='bg-dark text-light px-3 py-2 rounded-pill' style='font-size: 16px;'>$dt_detail_pin->pin_category</span>
        </h2>";
    }
?>

<div class="row mt-4">
    <div class="col-lg-6 col-md-6 col=sm-12">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-6 col-6">
                <p class='mt-2 mb-0 fw-bold'>Latitude</p>
                <?php 
                    if($is_edit){
                        echo "<input name='pin_lat' id='pin_lat' type='text' class='form-control' value='$dt_detail_pin->pin_lat' required/>";
                    } else {
                        echo "<p>$dt_detail_pin->pin_lat</p>";
                    }
                ?>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-6 col-6">
                <p class='mt-2 mb-0 fw-bold'>Longitude</p>
                <?php 
                    if($is_edit){
                        echo "<input name='pin_long' id='pin_long' type='text' class='form-control' value='$dt_detail_pin->pin_long' required/>";
                    } else {
                        echo "<p>$dt_detail_pin->pin_long</p>";
                    }
                ?>
            </div>
        </div>

        <p class='mt-2 mb-0 fw-bold'>Person In Touch</p>
        <?php 
            if($is_edit){
                echo "<input name='pin_person' id='pin_person' type='text' class='form-control' value='$dt_detail_pin->pin_person'/>";
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
                        echo "<input name='pin_email' id='pin_email' type='email' class='form-control' value='$dt_detail_pin->pin_email'/>";
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
                        echo "<input name='pin_call' id='pin_call' type='phone' class='form-control' value='$dt_detail_pin->pin_call'/>";
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
                echo "<textarea name='pin_address' id='pin_address' rows='5' class='form-control'>$dt_detail_pin->pin_address</textarea>";
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
                echo "<textarea name='pin_desc' id='pin_desc' rows='5' class='form-control'>$dt_detail_pin->pin_desc</textarea>";
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
                echo "<button class='btn btn-success w-100 py-3 my-4' type='Submit' id='submit-btn'><i class='fa-solid fa-floppy-disk'></i> Save Changes</button>";
            } 
        ?>
    </form>

        <?php if (!$is_edit): ?>
        <hr><p class='mt-2 mb-0 fw-bold'>Visit History</p>
        <ol>
        <?php 
            $show_page = false;
            if(count($dt_visit_history['data']) > 0){
                $show_page = true;
                foreach($dt_visit_history['data'] as $dt){
                    echo "<li>$dt->visit_desc using "; echo strtolower($dt->visit_by);
                        if($dt->visit_with != null){
                            echo " with $dt->visit_with";
                        }    
                    echo " at <span class='date-target'>$dt->created_at</span></li>";
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
        <?php 
            if($show_page){
                echo "<div class='d-inline-block'>
                <h6>Page History</h6>";

                $active = 0;
                if($this->session->userdata('page_detail_history')){
                    $active = $this->session->userdata('page_detail_history');
                }

                for($i = 0; $i < $dt_visit_history['total_page']; $i++){
                    $page = $i + 1;
                    echo "
                        <form method='POST' class='d-inline' action='/DetailController/navigate/$dt_detail_pin->id/$i'>
                            <button class='btn btn-page"; 
                            if($active == $i){echo " active";}
                            echo" me-1' type='submit'>$page</button>
                        </form>
                    ";
                }

                echo "</div>";
            }
        ?>
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
        <?php endif;?>

        <div class="d-flex justify-content-between mt-2 mb-0">
            <p class='fw-bold mt-1'>Galleries</p>
            <?php $this->load->view('detail/add_galleries'); ?>
        </div>
        <?php $this->load->view('detail/galleries'); ?>
        <hr>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div id="map-board"></div>

        <?php if (!$is_edit): ?>
            <hr>
            <p class='mt-2 mb-0 fw-bold'>Distance to My Personal Pin</p>
            <?php $this->load->view('detail/distance'); ?>
            <hr>
            <p class='mt-2 mb-0 fw-bold'>Tracked Activity Around</p>
            <?php $this->load->view('detail/tracker_activity_around'); ?>
        <?php else: ?>
            <?php $this->load->view('detail/props'); ?>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        $(document).on('click', '#delete-pin-btn', function() {
            Swal.fire({
                title: "Are you sure?",
                html: `Want to delete this pin?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $('#delete-pin-form').submit()
                }
            });
        })
    });

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

        map.addListener("click", (e) => {
            <?php 
                if($is_edit){
                    echo "const coor = e.latLng.toJSON()
                    markers[1] = {
                        coords: {lat: parseFloat(coor['lat']), lng: parseFloat(coor['lng'])},
                        icon: {
                            url: 'https://maps.google.com/mapfiles/ms/icons/red.png',
                            scaledSize: {width: 40, height: 40}
                        }
                    }
                    addMarker(markers)
                    initMap()
                    addContentCoor(e.latLng, 'pin_lat', 'pin_long')";
                } else {
                    echo "
                    const coor = e.latLng.toJSON()
                    markers[1] = {
                        coords: {lat: parseFloat(coor['lat']), lng: parseFloat(coor['lng'])},
                        icon: {
                            url: 'https://maps.google.com/mapfiles/ms/icons/red.png',
                            scaledSize: {width: 40, height: 40}
                        }
                    }
                    addMarker(markers)
                    initMap()
                    Swal.fire({
                        title: `Are you want to travel to from this location?`,
                        html: `Let me help you set the direction?`,
                        icon: `question`,
                        showCancelButton: true,
                        confirmButtonText: `Yes, show me!`
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            window.open(`https://www.google.com/maps/dir/`+coor['lat']+`,`+coor['lng']+`/$dt_detail_pin->pin_lat,$dt_detail_pin->pin_long`, '_blank')
                        }
                    });";
                }
            ?>
        });
       
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
