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
    <input hidden id="type_add" name="type_add" value="visit">
    <input hidden id="with_dir" name="coordinate_dir">

    <div id="add_pin_form"></div>
    <div id="add-form-holder">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div id="pin_name_init_holder">
                    <?php
                        if(count($dt_all_my_pin_name) > 0){ 
                            echo "
                                <label>Pin Name</label>
                                <select name='pin_id' class='form-select' id='pin_id' onchange=''>";
                                foreach($dt_all_my_pin_name as $dt){
                                    echo "<option value='$dt->id/$dt->pin_name'>$dt->pin_name</option>";
                                }
                            echo "</select>";
                        } else {
                            echo "
                                <label>Location Name</label>
                                <input name='location_name' id='location_name' type='text' class='form-control' required/>
                                <a class='msg-error-input'></a>
                            ";
                        }
                    ?>
                    <a class="msg-error-input"></a>
                    <div class="d-flex justify-content-start mb-3">
                        <?php 
                            if(count($dt_all_my_pin_name) > 0){
                                echo "<a class='btn btn-dark rounded-pill' id='add-custom-btn'><i class='fa-solid fa-map'></i> Custom Location</a>";
                            }
                        ?>
                        <a class="btn btn-dark rounded-pill ms-2" id='add-new-pin-btn'><i class="fa-solid fa-map-location-dot"></i> New Pin</a>
                        <a class="btn btn-dark rounded-pill ms-2 add-form-btn"><i class="fa-solid fa-plus"></i> Add Multiple Visit</a>
                    </div>
                </div>
                <label>Description</label>
                <textarea name="visit_desc" id="visit_desc" rows="5" class="form-control"></textarea>
                <a class="msg-error-input"></a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label>Visit By</label>
                <select name="visit_by" class="form-select" id="visit_by">
                    <?php 
                        foreach($dt_dct_visit_by as $dt){
                            echo "<option value='$dt->dictionary_name'>$dt->dictionary_name</option>";
                        }
                    ?>
                </select>
                <a class="msg-error-input"></a>

                <label>Visit With</label>
                <textarea name="visit_with" id="visit_with" rows="5" class="form-control visit-with"></textarea>
                <a class="msg-error-input"></a>
                <div class="d-flex justify-content-start mb-3">
                    <a class="btn btn-dark rounded-pill see-person-btn" data-bs-toggle='modal' data-bs-target='#myContactModel'><i class="fa-solid fa-user-plus"></i> See Persons</a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label>Visit At Date</label>
                <input name="visit_date" id="visit_date" type="date" class="form-control" required/>
                <a class="msg-error-input"></a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label>Visit At Hour</label>
                <input name="visit_hour" id="visit_hour" type="time" class="form-control" required/>
                <a class="msg-error-input"></a>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-4 col-md-6 col-sm-12" id="save_visit_n_go">
            <a class="btn btn-white rounded-pill w-100 py-3 mb-2" style="border: 2.5px solid black;" id='submit-visit-wdir-btn'><i class="fa-solid fa-location-arrow"></i> Save Visit & Set Direction</a>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <a class="btn btn-white rounded-pill w-100 py-3 mb-2" style="border: 2.5px solid black;"><i class="fa-solid fa-arrow-right-arrow-left"></i> Round Trip</a>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12" id='save-visit-btn-holder'>
            <button class="btn btn-dark rounded-pill w-100 py-3 mb-2" type="Submit" id='submit-visit-btn'><i class="fa-solid fa-floppy-disk"></i> Save Visit</button>
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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

<script>
    let map
    let selected_color = ''

    $(document).ready(function() {
        const btn_submit_el = `<button class="btn btn-dark rounded-pill w-100 py-3" type="Submit" id='submit-visit-btn'><i class="fa-solid fa-floppy-disk"></i> Save Visit</button>`
        let count_multi_visit = 1

        $(document).on('click', '.add-form-btn', function() { 
            count_multi_visit++

            $('#type_add').val('multi')
            let target = $('#add-form-holder .row').last().clone()

            target.find('.diff-hr').remove()
            target.find('#add-new-pin-btn').remove()
            target.find('#add-custom-btn').remove()

            target.prepend(`
                <span class='diff-hr'>
                    <hr>
                    <div class='d-flex justify-content-between'>
                        <h4 class="mb-2">Visit Form #${count_multi_visit}</h4>  
                        <a class='btn btn-light rounded-pill py-2 px-4 remove-multi-form-idx-btn'><i class="fa-solid fa-trash"></i> Remove Form</a>
                    </div>
                </span>
            `)
            $('#add-form-holder .row').append(target)

            $('input, textarea, select').each(function() {
                let name = $(this).attr('name')

                if (name && name !== 'type_add' && name !== 'coordinate_dir' && !name.endsWith('[]')) {
                    $(this).attr('name', name + '[]')
                }
            });

            $('#save-visit-btn-holder').html(`
                <button class="btn btn-dark rounded-pill w-100 py-3" type="Submit" id='submit-visit-btn'><i class="fa-solid fa-floppy-disk"></i> Save ${count_multi_visit} Visit</button>
            `)
        })
        $(document).on('click', '#add-new-pin-btn', function() { 
            $('#type_add').val('pin_visit')
            $('#pin_name_init_holder').empty()
            $('#add-form-holder .row').not(':first').remove()
            $('#save-visit-btn-holder').html(btn_submit_el)

            $('#add_pin_form').html(`
                <h4 class="mb-2">Pin Form</h4>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label>Pin Name</label>
                        <input name="pin_name" id="pin_name" type="text" class="form-control" required/>
                        <a class="msg-error-input"></a>
                        <div class="d-flex justify-content-start mb-3">
                            <a class="btn btn-dark rounded-pill"><i class="fa-solid fa-map"></i> Custom Location</a>
                            <a class="btn btn-dark rounded-pill ms-2" onclick="resetForm()"><i class="fa-solid fa-house"></i> Visit Only</a>
                            <a class="btn btn-dark rounded-pill ms-2"><i class="fa-solid fa-plus"></i> Add Multiple Visit</a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label>Pin Category</label>
                        <select name="pin_category" class="form-select" id="pin_category">
                            <?php 
                                foreach($dt_dct_pin_category as $dt){
                                    echo "<option value='$dt->dictionary_name-$dt->dictionary_color'>$dt->dictionary_name</option>";
                                }
                            ?>
                        </select>
                        <a class="msg-error-input"></a>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label>Maps</label>
                        <div class="position-relative">
                            <div id="map-board"></div>
                        </div>
                        <a class="msg-error-input"></a>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label>Latitude</label>
                        <input name="pin_lat" id="pin_lat" type="text" class="form-control" onchange="select_map()" required/>
                        <a class="msg-error-input"></a>

                        <label>Longitude</label>
                        <input name="pin_long" id="pin_long" type="text" class="form-control" onchange="select_map()" required/>
                        <a class="msg-error-input"></a>

                        <label>Description</label>
                        <textarea name="pin_desc" id="pin_desc" rows="5" class="form-control"></textarea>
                        <a class="msg-error-input"></a>

                        <label>Address</label>
                        <textarea name="pin_address" id="pin_address" rows="5" class="form-control"></textarea>
                        <a class="msg-error-input"></a>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label>Person In Contact</label>
                        <input name="pin_person" id="pin_person" type="text" class="form-control"/>
                        <a class="msg-error-input"></a>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label>Phone Number</label>
                        <input name="pin_phone" id="pin_phone" type="text" class="form-control"/>
                        <a class="msg-error-input"></a>
                    </div>
                </div>
                <hr>
                <h4 class="mb-2">Visit Form</h4>
            `)
            initMap() 
        })
        $(document).on('click', '#add-custom-btn', function() { 
            $('#type_add').val('visit_custom')
            $('#add-form-holder .row').not(':first').remove()
            $('#save-visit-btn-holder').html(btn_submit_el)

            $('#pin_name_init_holder').html(`
                <label>Location Name</label>
                <input name="location_name" id="location_name" type="text" class="form-control"/>
                <a class="msg-error-input"></a>
                <div class="d-flex justify-content-start mb-3">
                    <a class="btn btn-dark rounded-pill" onclick="resetForm()"><i class="fa-solid fa-location-dot"></i> Saved Pin</a>
                    <a class="btn btn-dark rounded-pill ms-2" onclick="addCreatePinForm()"><i class="fa-solid fa-map-location-dot"></i> New Pin</a>
                    <a class="btn btn-dark rounded-pill ms-2"><i class="fa-solid fa-plus"></i> Add Multiple Visit</a>
                </div>
            `)
            $('#save_visit_n_go').empty()
        })

        $(document).on('click', '.remove-multi-form-idx-btn', function() { 
            const idx = $(this).index('.remove-multi-form-idx-btn')
            $('#add-form-holder .row').eq(idx).remove()

            let count_visit = $('#add-form-holder .row').length
            if(count_visit == 1){
                count_visit = ''
            }
            $('#save-visit-btn-holder').html(`
                <button class="btn btn-dark rounded-pill w-100 py-3" type="Submit"><i class="fa-solid fa-floppy-disk"></i> Save ${count_visit}Visit</button>
            `)
        })

        $(document).on('click', '#submit-visit-wdir-btn', function() {
            if($('#type_add').val() == 'visit'){
                $('#with_dir').val($('#pin_id').val().split('/')[1])
            } else if($('#type_add').val() == 'pin_visit'){
                $('#with_dir').val(`${$('#pin_lat').val()},${$('#pin_long').val()}`)
            } else if($('#type_add').val() == 'multi'){
                $('#with_dir').val('multi')
            }

            $('#submit-visit-btn').click()
        })
    })

    function initMap() {
        map = new google.maps.Map(document.getElementById("map-board"), {
            center: { lat: -6.226838579766097, lng: 106.82157923228753 },
            zoom: 12,
        });

        map.addListener("click", (e) => {
            initMap()
            placeMarkerAndPanTo(e.latLng, map)
            addContentCoor(e.latLng)
        });
    }

    function placeMarkerAndPanTo(latLng, map) {
        if (selected_color == '') {
            const val = document.getElementById("pin_category").value
            const split_val = val.split('-')
            const color = split_val[1]
            selected_color = color
        }

        new google.maps.Marker({
            position: latLng,
            map: map,
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/' + selected_color + '-dot.png',
                scaledSize: new google.maps.Size(40, 40),
            }
        });
        map.panTo(latLng)
    }

    function addContentCoor(coor) {
        coor = coor.toJSON()
        document.getElementById('pin_lat').value = coor['lat']
        document.getElementById('pin_long').value = coor['lng']
    }

    function resetForm(){
        $('#type_add').val('visit')
        $('#add_pin_form').empty()
        $('#pin_name_init_holder').html(`
            <label>Pin Name</label>
            <select name="pin_id" class="form-select" id="pin_id" onchange="">
                <?php 
                    foreach($dt_all_my_pin_name as $dt){
                        echo "<option value='$dt->id/$dt->pin_name'>$dt->pin_name</option>";
                    }
                ?>
            </select>
            <a class="msg-error-input"></a>
            <div class="d-flex justify-content-start mb-3">
                <a class="btn btn-dark rounded-pill" id='add-custom-btn'><i class="fa-solid fa-map"></i> Custom Location</a>
                <a class="btn btn-dark rounded-pill ms-2" id='add-new-pin-btn'><i class="fa-solid fa-map-location-dot"></i> New Pin</a>
                <a class="btn btn-dark rounded-pill ms-2 add-form-btn"><i class="fa-solid fa-plus"></i> Add Multiple Visit</a>
            </div>
        `)
        $('#save_visit_n_go').html(`
            <a class="btn btn-white rounded-pill w-100 py-3" style="border: 2.5px solid black;" id='submit-visit-wdir-btn'><i class="fa-solid fa-location-arrow"></i> Save Visit & Set Direction</a>
        `)
    } 

    window.onload = function() {
        initMap() 
    }
</script>