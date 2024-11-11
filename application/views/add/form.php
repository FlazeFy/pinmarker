<div id="add_pin">
    <form action="/AddController/add_marker/single" method="POST">
        <input hidden id="is_with_dir" name="is_with_dir" value="false">
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
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <input name="pin_name" id="pin_name" type="text" class="form-control form-validated" maxlength="75" required/>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label>Pin Category</label>
                <select name="pin_category" class="form-select" id="pin_category" onchange="select_color_marker(this.value)">
                    <?php 
                        foreach($dt_dct_pin_category as $dt){
                            echo "<option value='$dt->dictionary_name-$dt->dictionary_color'>$dt->dictionary_name</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label>Maps</label>
                <?php $this->load->view('add/maps_select'); ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="row">
                    <div class="col-6">
                        <input name="pin_lat" id="pin_lat" type="text" maxlength="144" class="form-control form-validated" onchange="select_map()" onblur="check_nearest_pin()" required/>
                    </div>
                    <div class="col-6">
                        <input name="pin_long" id="pin_long" type="text" maxlength="144" class="form-control form-validated" onchange="select_map()" onblur="check_nearest_pin()" required/>
                    </div>
                </div>

                <textarea name="pin_desc" id="pin_desc" maxlength="500" rows="5" class="form-control form-validated"></textarea>
                <textarea name="pin_address" id="pin_address" maxlength="500" rows="5" class="form-control form-validated"></textarea>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <input name="pin_person" id="pin_person" maxlength="75" type="text" class="form-control form-validated"/>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <input name="pin_call" id="pin_call" maxlength="16" type="phone" class="form-control form-validated"/>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <input name="pin_email" id="pin_email" maxlength="255" type="email" class="form-control form-validated"/>
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
                <a class="btn btn-light w-100 py-3 mb-2" style="border: 2.5px solid black;" id='submit-visit-wdir-btn'><i class="fa-solid fa-location-arrow"></i> Save Marker & Set Direction</a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <button class="btn btn-success w-100 py-3" id="submit-btn" type="Submit"><i class="fa-solid fa-floppy-disk"></i> Save Marker</button>
            </div>
        </div>
    </form>
</div>
<div id="add_multiple_pin" style="display:none;">
    <form action="/AddController/add_marker/multiple" method="POST">
        <table class="table table-bordered" id="tb_imported_pin">
            <thead style="font-size: var(--textMD);">
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
<div class="modal fade" id="importMarkerMap" tabindex="-1" aria-labelledby="addGalleriesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGalleriesLabel">Imported Marker Map</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id='close-import-map-modal-btn'></button>
            </div>
            <div class="modal-body">
                <div class="position-relative">
                    <div id="map-board-imported"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const pin_lat_input = document.getElementById('pin_lat')
    const pin_long_input = document.getElementById('pin_long')

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

        $(document).on('click', '#download-template-btn', function() {
            const fileURL = 'public/file/template_import_pin.csv'
            const fileName = 'import_pin_template.csv'
            const link = document.createElement('a')
            link.href = fileURL
            link.download = fileName
            document.body.appendChild(link)
            link.click()
            document.body.removeChild(link)
        })
        $(document).on('click', '#submit-visit-wdir-btn', function() {
            $('#is_with_dir').val('true')
            $('#submit-btn').click()
        })
    })

    const generate_form = () => {
        const fileInput = $('#pin-data').get(0)
        const file = fileInput.files[0]
        loading() 

        if (!file) {
            Swal.hideLoading()
            $('#importMarker').modal('hide')
            Swal.fire({ 
                title: 'Failed!', 
                text: 'Please select a file', 
                icon: 'error' 
            });
        } else if (!file.name.endsWith('.csv')) {
            Swal.hideLoading()
            $('#importMarker').modal('hide')
            Swal.fire({ 
                title: 'Failed!', 
                text: 'Please select a CSV file', 
                icon: 'error' 
            });
        } else {
            let reader = new FileReader()
            reader.readAsText(file)

            reader.onload = function (event) {
                let csvdata = event.target.result
                let rowData = csvdata.split('\n')
                let success_row = 0
                let failed_row = 0
                let map2
                let markers = []

                if (rowData.length > 0 && rowData.length < 201) {
                    $('#importMarker').modal('hide')
                    $('#add_multiple_pin').css('display', 'block')
                    $('#add_pin').css('display', 'none')

                    function initMapImported() {
                        map2 = new google.maps.Map(document.getElementById("map-board-imported"), {
                            center: { lat: -6.226838579766097, lng: 106.82157923228753 },
                            zoom: 12,
                        });
                    }

                    function addMarker(props) {
                        var marker = new google.maps.Marker({
                            position: props.coords,
                            map: map2,
                            icon: props.icon
                        });

                        if(props.icon){
                            marker.setIcon(props.icon);
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

                    for (let row = 1; row < rowData.length; row++) {
                        let rowColData = rowData[row].split(',')

                        if (rowColData.length >= 3) {
                            let pinName = rowColData[0].trim()
                            let longitude = rowColData[1].trim()
                            let latitude = rowColData[2].trim()

                            if (pinName && latitude && longitude) {
                                if (latitude !== "0" && longitude !== "0") {
                                    $('#tb_imported_pin tbody').append(`
                                        <tr id="pin_section_${row}">
                                            <td style="width:300px;">
                                                <input class="form-control" name="pin_name[]" value="${pinName}" oninput="dt_search_config(${row},'pin_name', this)">
                                                <span hidden id="pin_name_dt_holder_${row}">${pinName}</span>
                                            </td>
                                            <td>
                                                <select name="pin_category[]" class="form-select">
                                                    <?php 
                                                        foreach($dt_dct_pin_category as $dt){
                                                            echo "<option value='$dt->dictionary_name-$dt->dictionary_color'>$dt->dictionary_name</option>";
                                                        }
                                                    ?>
                                                </select>
                                                <a class="btn btn-dark py-1 px-2 float-start using-cat-btn" style='font-size:var(--textSM);' onclick="copy_all_cat(${row})"><i class="fa-solid fa-copy"></i> Using this category for all pin after this</a>
                                            </td>
                                            <td>
                                                <label>Latitude</label>
                                                <input class="form-control" name="pin_lat[]" value="${latitude}" oninput="dt_search_config(${row},'pin_lat', this)">
                                                <span hidden id="pin_lat_dt_holder_${row}">${latitude}</span>

                                                <label>Longitude</label>
                                                <input class="form-control" name="pin_long[]" value="${longitude}" oninput="dt_search_config(${row},'pin_long', this)">
                                                <span hidden id="pin_long_dt_holder_${row}">${longitude}</span>
                                            </td>
                                            <td>
                                                <textarea class="form-control" name="pin_desc" value=""></textarea>
                                            </td>
                                            <td>
                                                <a class='btn btn-dark d-block mx-auto delete-imported-pin-btn' onclick="delete_imported_pin(${row})"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    `)
                                    success_row++

                                    // markers.push()
                                    addMarker({
                                        coords: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
                                        icon: {
                                            url: 'https://maps.google.com/mapfiles/ms/icons/red.png',
                                            scaledSize: new google.maps.Size(40, 40),
                                        },
                                        content: `<div>
                                            <h6>${pinName}</h6>
                                        </div>
                                        `
                                    })
                                } else {
                                    failed_row++
                                }
                            } else {
                                failed_row++
                            }
                        } else {
                            failed_row++
                        }
                    }

                    Swal.hideLoading()
                    if (success_row > 0) {
                        window.initMap = initMapImported()
                        // $('#tb_imported_pin').DataTable()

                        $('#imported_map_btn_holder').empty().append(`
                            <a class="btn btn-dark mb-4 py-3 px-4 see-map-btn" data-bs-toggle="modal" data-bs-target="#importMarkerMap">
                                <i class="fa-solid fa-map"></i> See the Map
                            </a>
                        `)
                        Swal.fire({
                            title: 'Success!',
                            text: failed_row === 0 ? `Successfully imported ${success_row} pin` : `Successfully imported ${success_row} pin and ${failed_row} failed`,
                            icon: 'success',
                        });
                    } else {
                        Swal.fire({
                            title: 'Failed!',
                            text: `No pin ready to imported`,
                            icon: 'error',
                        });
                    }
                } else {
                    Swal.hideLoading();
                    Swal.fire({
                        title: 'Failed!',
                        text: `Total pin must be below 100. Your pin count is ${rowData.length}`,
                        icon: 'error',
                    });
                }
            };
        }
    };

    const check_nearest_pin = () => {
        if($('#pin_lat').val() && $('#pin_long').val() && $('#pin_lat').val().trim() != "" && $('#pin_long').val().trim() != ""){
            Swal.showLoading()
            $.ajax({
                url: `http://127.0.0.1:8000/api/v1/pin/nearest/${$('#pin_lat').val()}/${$('#pin_long').val()}`,
                dataType: 'json',
                contentType: 'application/json',
                type: "POST",
                data: JSON.stringify({
                    id: "<?= $this->session->userdata('user_id'); ?>",
                    max_distance: 1000
                }),
                beforeSend: function (xhr) {
                    // You can add any pre-request logic here
                }
            })
            .done(function (response) {         
                let data = response.data
                
                Swal.hideLoading()
                if (response.is_found_near == false) {
                    Swal.fire({ 
                        title: 'Success!', 
                        text: 'No other pin detected near this coordinate. You are free to create.', 
                        icon: 'success' 
                    });
                } else {
                    let listNear = '';

                    data.forEach(el => {
                        listNear += `<li>${el.pin_name} at <b>${el.distance.toFixed(2)} m</b></li>`
                    });

                    Swal.fire({ 
                        title: 'Warning!', 
                        html: `There's a pin located near this coordinate.<br><br>
                            <h5>Here's the list:</h5><div class='text-start'>${listNear}</div>`, 
                        icon: 'warning' 
                    });
                }
            })
            .fail(function (xhr, ajaxOptions, thrownError) {
                Swal.hideLoading()
                Swal.fire({ 
                    title: 'Failed!', 
                    text: 'Something went wrong.', 
                    icon: 'error' 
                });
            });

        } else {
            Swal.fire({ 
                title: 'Failed!', 
                text: `Something wrong happen`, 
                icon: 'error' 
            });
        }
    }

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
