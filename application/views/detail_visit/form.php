<style>
    #map-board, #maps-count-distance {
        height:40vh;
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

<form action="/AddVisitController/add_visit" method="POST" id='add-visit-form'>
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
    <div id="add-form-holder">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div id="map-board"></div>
                <div id="pin_name_init_holder">
                    <?php
                        if(count($dt_all_my_pin_name) > 0){ 
                            echo "
                                <label>Pin Name</label>
                                <select name='pin_id' class='form-select' id='pin_id' onchange=''>";
                                foreach($dt_all_my_pin_name as $dt){
                                    echo "<option value='$dt->id/$dt->pin_name' ".($dt_detail_visit->pin_id == $dt->id ? 'selected' : '')." >$dt->pin_name</option>";
                                }
                            echo "</select>";
                        } else {
                            echo "<input name='location_name' id='location_name' type='text' class='form-control form-validated' maxlength='255' required/>";
                        }
                    ?>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-6 col-6">
                        <p class='mt-2 mb-0 fw-bold'>Latitude</p>
                        <p><?= $dt_detail_visit->pin_lat ?></p>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-6 col-6">
                        <p class='mt-2 mb-0 fw-bold'>Longitude</p>
                        <p><?= $dt_detail_visit->pin_long ?></p>
                    </div>
                </div>
                <label>Pin Description</label>
                <?php 
                    if($dt_detail_visit){
                        echo "<p>$dt_detail_visit->pin_desc</p>";
                    } else {
                        echo "<p class='text-secondary fst-italic'>- No Description -</p>";
                    }
                ?>
                <label>Pin Category</label>
                <p><?= $dt_detail_visit->pin_category ?></p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label>Visit By</label>
                <select name="visit_by" class="form-select" id="visit_by">
                    <?php 
                        foreach($dt_dct_visit_by as $dt){
                            echo "<option value='$dt->dictionary_name' ".($dt->dictionary_name == $dt_detail_visit->visit_by ? 'selected' : '').">$dt->dictionary_name</option>";
                        }
                    ?>
                </select>
                <textarea name="visit_desc" id="visit_desc" rows="5" class="form-control form-validated" maxlength='255'><?= $dt_detail_visit->visit_desc ?></textarea>
                <textarea name="visit_with" id="visit_with" rows="5" class="form-control form-validated visit-with" maxlength='500'><?= $dt_detail_visit->visit_with ?></textarea>
                
                <div class="d-flex justify-content-start mb-3">
                    <a class="btn btn-success rounded-pill see-person-btn" data-bs-toggle='modal' data-bs-target='#myContactModel'><i class="fa-solid fa-user-plus"></i> See Persons</a>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <input name="visit_date" id="visit_date" type="date" class="form-control form-validated"  value="<?= date("Y-m-d",strtotime($dt_detail_visit->created_at));?>" required/>                
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <input name="visit_hour" id="visit_hour" type="time" class="form-control form-validated" value="<?= date("H:m",strtotime($dt_detail_visit->created_at));?>" required/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-6 col-md-6 col-sm-12" id="save_visit_n_go">
            <a class="btn btn-light rounded-pill w-100 py-3 mb-2" style="border: 2.5px solid black;" id='submit-visit-wdir-btn'><i class="fa-solid fa-location-arrow"></i> Save Visit & Set Direction</a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12" id='save-visit-btn-holder'>
            <button class="btn btn-success rounded-pill w-100 py-3 mb-2" type="Submit" id='submit-visit-btn'><i class="fa-solid fa-floppy-disk"></i> Save Visit</button>
        </div>
    </div>
</form>

<?php $this->load->view('add_visit/my_contact'); ?>

<?php 
    if($this->session->flashdata('message_error')){
        echo "
            <script>
                Swal.fire({
                    title: 'Failed!',
                    text: '".$this->session->flashdata('message_error')."',
                    icon: 'error'
                });
            </script>
        ";
    }
?>


<script type="text/javascript">

    let map;
    let markers = [
        <?php 
            echo "{
                coords: {lat: $dt_detail_visit->pin_lat, lng: $dt_detail_visit->pin_long},
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/red.png',
                    scaledSize: {width: 40, height: 40}
                }
            }";
        ?>
    ]

    function initMap() {
        map = new google.maps.Map(document.getElementById("map-board"), {
            center: { lat: parseFloat(<?= $dt_detail_visit->pin_lat ?>), lng: parseFloat(<?= $dt_detail_visit->pin_long ?>) },
            zoom: 12,
        });
    }
    window.initMap = initMap
</script>