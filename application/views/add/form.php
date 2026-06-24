<div class="card">
    <h2 class="card-title">Marker Detail</h2>
    <input hidden id="is_with_dir" name="is_with_dir" value="false">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <input name="pin_name" id="pin_name" type="text" class="form-control form-validated" maxlength="75" required/>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <label>Pin Category</label>
            <select name="pin_category" class="form-select" id="pin_category">
                <?php 
                    foreach($dt_dct_pin_category as $dt){
                        echo "<option value='$dt->dictionary_name'>$dt->dictionary_name</option>";
                    }
                ?>
            </select>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <input name="pin_person" id="pin_person" maxlength="75" type="text" class="form-control form-validated"/>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="row">
                <div class="col-6">
                    <input name="pin_lat" id="pin_lat" type="text" maxlength="144" class="form-control form-validated" onblur="check_nearest_pin(true, true)" required/>
                </div>
                <div class="col-6">
                    <input name="pin_long" id="pin_long" type="text" maxlength="144" class="form-control form-validated" onblur="check_nearest_pin(true, true)" required/>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <input name="pin_village" id="pin_village" maxlength="75" class="form-control form-validated"/>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <input name="pin_suburb" id="pin_suburb" maxlength="75" class="form-control form-validated"/>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <input name="pin_city" id="pin_city" maxlength="75" class="form-control form-validated"/>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <input name="pin_country" id="pin_country" maxlength="75" class="form-control form-validated"/>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <textarea name="pin_desc" id="pin_desc" maxlength="500" rows="5" class="form-control form-validated"></textarea>
            <input name="pin_email" id="pin_email" maxlength="255" type="email" class="form-control form-validated"/>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <textarea name="pin_address" id="pin_address" maxlength="500" rows="5" class="form-control form-validated"></textarea>
            <input name="pin_call" id="pin_call" maxlength="16" type="phone" class="form-control form-validated"/>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="is_favorite">
                <label class="form-check-label" for="is_favorite">Add To My Favorite</label>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <button class="btn btn-outline-primary w-100 py-3 mb-2" id='submit-visit-wdir-btn'><i class="fa-solid fa-location-arrow"></i> Save Marker & Set Direction</button>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <button class="btn btn-success w-100 py-3" id="submit-btn" type="Submit"><i class="fa-solid fa-floppy-disk"></i> Save Marker</button>
        </div>
    </div>
    <div id="add_multiple_pin" style="display:none;">
        <form action="/AddController/add_marker/multiple" method="POST">
            <table class="table table-bordered" id="tb_imported_pin">
                <thead>
                    <tr class="text-center">
                        <th scope="col">Pin Name</th>
                        <th scope="col">Category</th>
                        <th scope="col">Coordinate</th>
                        <th scope="col">Description</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody style="font-size: var(--textSM);"></tbody>
            </table>
            <button class="btn btn-success w-100 py-2 px-3" id="submit-btn"><i class="fa-solid fa-floppy-disk"></i> Save All Marker</button>
        </form>
    </div>

    <div class="modal fade" id="importMarker" tabindex="-1" aria-labelledby="addGalleriesLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGalleriesLabel">Import Marker</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id='close-import-marker-modal-btn'></button>
                </div>
                <div class="modal-body">
                    <a class="btn btn-dark w-100 py-2 px-3 mb-2" id="download-template-btn"><i class="fa-solid fa-download"></i> Download Template</a>
                    <hr>
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Only Accept CSV file <b>(Max : 20 mb)</b></label>
                        <input class="form-control" type="file" id="pin-data"  accept=".csv">
                    </div>
                    <a class="btn btn-success w-100 py-2 px-3" id="generate-form-btn" onclick="generate_form()"><i class="fa-solid fa-plus"></i> Generate Form</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #map-board-imported {
        height:70vh;
        border-radius: 20px;
        margin-bottom: 6px;
        border: 5px solid black;
    }
    table th {
        vertical-align: none !important;
    }
</style>

<script>
    const pin_lat_input = document.getElementById('pin_lat')
    const pin_long_input = document.getElementById('pin_long')

    const postCreatePin = () => {
        Swal.showLoading()

        const isScheduleActive = $('#schedule-toggle').is(':checked')
        const data = {
            pin_name: $('#pin_name').val().trim(),
            pin_desc: $('#pin_desc').val().trim(),
            pin_lat: $('#pin_lat').val().trim(),
            pin_long: $('#pin_long').val().trim(),
            pin_village: $('#pin_village').val().trim(),
            pin_suburb: $('#pin_suburb').val().trim(),
            pin_city: $('#pin_city').val().trim(),
            pin_country: $('#pin_country').val().trim(),
            pin_category: $('#pin_category').val(),
            pin_person: $('#pin_person').val().trim(),
            pin_call: $('#pin_call').val().trim(),
            pin_email: $('#pin_email').val().trim(),
            pin_address: $('#pin_address').val().trim(),
            is_favorite: $('#is_favorite').is(':checked') ? 1 : 0,
            is_with_dir: $('#is_with_dir').val(),
            schedules: isScheduleActive ? buildSchedulePayload() : []
        }

        $.ajax({
            url: '/api/v1/pin/create',
            method: 'POST',
            data: data,
            headers: {
                'Authorization': `Bearer ${tokenKey}`
            },
            success: (response) => {
                Swal.hideLoading()

                if (data.is_with_dir === 'true') {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success'
                    }).then(() => {
                        window.open(`https://www.google.com/maps/dir/?api=1&destination=${data.pin_lat},${data.pin_long}`, '_blank')
                        window.location.href = '/DashboardController'
                    })
                } else {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success'
                    }).then(() => {
                        window.location.href = '/DashboardController'
                    })
                }
            },
            error: (response) => {
                if (response.status === 401) return failedAuth()
                Swal.hideLoading()

                const message = response.responseJSON?.message ?? 'Something went wrong.'

                if (response.status === 400) {
                    Swal.fire({
                        title: 'Failed!',
                        html: message,
                        icon: 'warning'
                    })
                } else {
                    unknownErrorSwal()
                }
            }
        })
    }

    $(document).ready(function () {
        // Submit marker
        $(document).on('click', '#submit-btn', function (e) {
            e.preventDefault()
            postCreatePin()
        })

        // Submit marker with direction
        $(document).on('click', '#submit-visit-wdir-btn', function () {
            $('#is_with_dir').val('true')
            postCreatePin()
        })
    })

    const read_pin_data_url = () => {
        const data_from_url = read_url_params('add pin')
        if(data_from_url){
            $('#pin_name').val(data_from_url.pin_name)
            $('#pin_desc').val(data_from_url.pin_desc)
            $('#pin_lat').val(data_from_url.pin_lat)
            $('#pin_long').val(data_from_url.pin_long)
            $('#pin_address').val(data_from_url.pin_address)
            $('#pin_call').val(data_from_url.pin_call)
            const pin_category = data_from_url.pin_category
            const matchingOption = $('#pin_category option').filter(function () {
                return $(this).val().includes(pin_category)
            });
            if (matchingOption.length) {                
                $('#pin_category').val(matchingOption.val()).change()
            } else {
                Swal.fire({
                    title: 'Failed!',
                    text: `Failed to fill the category. You dont have category for ${pin_category}. Try create one!`,
                    icon: 'error'
                });
            }
            if(data_from_url.pin_lat != "" && data_from_url.pin_long != ""){
                const coor = { lat: parseFloat(data_from_url.pin_lat), lng: parseFloat(data_from_url.pin_long) }
                initMap()
                placeMarkerAndPanTo(coor, map)
            }
            Swal.fire({
                title: 'Success!',
                text: 'Successfully passing the data to the form',
                icon: 'success'
            });
        } 
    }

    $(document).ready(function() {
        read_pin_data_url()

        $(document).on('click', '#submit-visit-wdir-btn', function() {
            $('#is_with_dir').val('true')
            $('#submit-btn').click()
        })
    })

    const set_disabled_submit = (val) => {
        $('#submit-visit-wdir-btn').prop('disabled', val)
        $('#submit-btn').prop('disabled', val)
        $('#pin_name').prev('label').addClass('d-inline-block')
        $('#pin_name').prevAll('.pin-name-status').remove()
        $('#pin_name').before(!val ? `<span class="pin-name-status tag bg-success ms-2">Valid</span>` : `<span class="pin-name-status tag bg-danger ms-2">Duplicated!</span>`)
    }

    $('#pin_name').on('blur', function () {
        const pinName = $(this).val().trim()
        pinName !== "" ? check_pin_name_availability(pinName, (res) => set_disabled_submit(res)) : $('.pin-name-status').remove()
    })

    const delete_imported_pin = (idx) => {
        $(`#pin_section_${idx}`).remove()
        Swal.fire({ 
            title: 'Success!', 
            text: `Pin removed`, 
            icon: 'success' 
        });
    };

    const copy_all_cat = (idx) => {
        const val = $('select[name="pin_category[]"]').eq(idx-1).val()
        $('select[name="pin_category[]"]').slice(idx).val(val)

        Swal.fire({ 
            title: 'Success!', 
            text: `Category has been copy ${val}`, 
            icon: 'success' 
        });
    }

    const dt_search_config = (idx, target, el) => {
        $(`#${target}_dt_holder_${idx}`).text(el.value)
    }
</script>
