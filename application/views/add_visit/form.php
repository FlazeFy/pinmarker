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
                            echo "<input name='location_name' id='location_name' type='text' class='form-control form-validated' maxlength='255' required/>";
                        }
                    ?>
                    
                    <div class="d-flex justify-content-start mb-3">
                        <?php 
                            if(count($dt_all_my_pin_name) > 0){
                                echo "<a class='btn btn-dark ' id='add-custom-btn'><i class='fa-solid fa-map'></i>"; 
                                if(!$is_mobile_device){
                                    echo " Custom Location";
                                } else {
                                    echo " Custom";
                                }
                                echo"</a>";
                            }
                        ?>
                        <a class="btn btn-dark ms-2" id='add-new-pin-btn'><i class="fa-solid fa-map-location-dot"></i> New Pin</a>
                        <a class="btn btn-success ms-2 add-form-btn"><i class="fa-solid fa-plus"></i>
                        <?php
                            if(!$is_mobile_device){
                                echo " Add Multiple Visit";
                            } else {
                                echo " Visit";
                            }
                        ?></a>
                    </div>
                </div>
                <textarea name="visit_desc" id="visit_desc" rows="5" class="form-control form-validated" maxlength='255'></textarea>
                
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
                <textarea name="visit_with" id="visit_with" rows="5" class="form-control form-validated visit-with" maxlength='500'></textarea>
                <div class="d-flex justify-content-start mb-3">
                    <a class="btn btn-success see-person-btn" data-bs-toggle='modal' data-bs-target='#myContactModel'><i class="fa-solid fa-user-plus"></i> See Persons</a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                <input name="visit_date" id="visit_date" type="date" class="form-control form-validated" required/>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                <input name="visit_hour" id="visit_hour" type="time" class="form-control form-validated" required/>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-4 col-md-6 col-sm-12" id="save_visit_n_go">
            <a class="btn btn-light w-100 py-3 mb-2" style="border: 2.5px solid black;" id='submit-visit-wdir-btn'><i class="fa-solid fa-location-arrow"></i> Save Visit & Set Direction</a>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <a class="btn btn-light w-100 py-3 mb-2" style="border: 2.5px solid black;" id='round-trip-btn'><i class="fa-solid fa-arrow-right-arrow-left"></i> Round Trip</a>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12" id='save-visit-btn-holder'>
            <button class="btn btn-success w-100 py-3 mb-2" type="Submit" id='submit-visit-btn'><i class="fa-solid fa-floppy-disk"></i> Save Visit</button>
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
        const btn_submit_el = `<button class="btn btn-success w-100 py-3" type="Submit" id='submit-visit-btn'><i class="fa-solid fa-floppy-disk"></i> Save Visit</button>`
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
                        <h4 class="mb-2">Visit Form</h4>  
                        <a class='btn btn-light py-2 px-4 remove-multi-form-idx-btn'><i class="fa-solid fa-trash"></i> Remove Form</a>
                    </div>
                </span>
            `)
            $('#add-form-holder').append(target)

            $('input, textarea, select').each(function() {
                let name = $(this).attr('name')

                if (name && name !== 'type_add' && name !== 'coordinate_dir' && !name.endsWith('[]')) {
                    $(this).attr('name', name + '[]')
                }
            });

            $('#save-visit-btn-holder').html(`
                <button class="btn btn-success w-100 py-3" type="Submit" id='submit-visit-btn'><i class="fa-solid fa-floppy-disk"></i> Save ${count_multi_visit} Visit</button>
            `)
            formValidation()
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
                        <input name="pin_name" id="pin_name" type="text" class="form-control form-validated" maxlength='75' required/>
                        
                        <div class="d-flex justify-content-start mb-3">
                            <a class="btn btn-dark " id="custom-loc-btn"><i class="fa-solid fa-map"></i> Custom Location</a>
                            <a class="btn btn-dark ms-2" id="visit-only-btn" onclick="resetForm()"><i class="fa-solid fa-house"></i> Visit Only</a>
                            <a class="btn btn-success ms-2" id="add-multiple-visit-btn"><i class="fa-solid fa-plus"></i> Add Multiple Visit</a>
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
                        
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label>Maps</label>
                        <div class="position-relative">
                            <div id="map-board"></div>
                        </div>
                        
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <input name="pin_lat" id="pin_lat" type="text" class="form-control form-validated" maxlength='144' onchange="select_map()" required/>
                        

                        <input name="pin_long" id="pin_long" type="text" class="form-control form-validated" maxlength='144' onchange="select_map()" required/>
                        

                        <textarea name="pin_desc" id="pin_desc" rows="5" class="form-control form-validated" maxlength='500'></textarea>
                        

                        <textarea name="pin_address" id="pin_address" rows="5" class="form-control form-validated" maxlength='500'></textarea>
                        
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <input name="pin_person" id="pin_person" type="text" class="form-control form-validated" maxlength='75'/>
                        
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <input name="pin_phone" id="pin_phone" type="text" class="form-control form-validated" maxlength='16'/>
                        
                    </div>
                </div>
                <hr>
                <h4 class="mb-2">Visit Form</h4>
            `)
            initMap() 
            formValidation()
        })
        $(document).on('click', '#add-custom-btn', function() { 
            $('#type_add').val('visit_custom')
            $('#add-form-holder .row').not(':first').remove()
            $('#save-visit-btn-holder').html(btn_submit_el)

            $('#pin_name_init_holder').html(`
                <input name="location_name" id="location_name" type="text" class="form-control form-validated" maxlength='255'/>
                
                <div class="d-flex justify-content-start mb-3">
                    <a class="btn btn-dark " id="saved-pin-btn" onclick="resetForm()"><i class="fa-solid fa-location-dot"></i> Saved Pin</a>
                    <a class="btn btn-dark ms-2" id="new-pin-btn" onclick="addCreatePinForm()"><i class="fa-solid fa-map-location-dot"></i> New Pin</a>
                    <a class="btn btn-success ms-2" id="add-multiple-visit-btn"><i class="fa-solid fa-plus"></i> Add Multiple Visit</a>
                </div>
            `)
            $('#save_visit_n_go').empty()
        })

        $(document).on('click', '.remove-multi-form-idx-btn', function() { 
            const idx = $(this).index('.remove-multi-form-idx-btn')

            $('#add-form-holder .row').eq(idx+1).remove()

            let count_visit = $('#add-form-holder .row').length
            if(count_visit == 1){
                count_visit = ''
            }
            $('#save-visit-btn-holder').html(`
                <button class="btn btn-success w-100 py-3" type="Submit" id='submit-btn'><i class="fa-solid fa-floppy-disk"></i> Save ${count_visit}Visit</button>
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
            
            <div class="d-flex justify-content-start mb-3">
                <a class="btn btn-dark " id='add-custom-btn'><i class="fa-solid fa-map"></i> Custom Location</a>
                <a class="btn btn-success ms-2" id='add-new-pin-btn'><i class="fa-solid fa-map-location-dot"></i> New Pin</a>
                <a class="btn btn-success ms-2 add-form-btn"><i class="fa-solid fa-plus"></i> Add Multiple Visit</a>
            </div>
        `)
        $('#save_visit_n_go').html(`
            <a class="btn btn-success w-100 py-3" style="border: 2.5px solid black;" id='submit-visit-wdir-btn'><i class="fa-solid fa-location-arrow"></i> Save Visit & Set Direction</a>
        `)
    } 

    window.onload = function() {
        initMap() 
    }
</script>