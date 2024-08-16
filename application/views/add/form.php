<div id="add_pin">
    <form action="/AddController/add_marker/single" method="POST">
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
                <label>Pin Name</label>
                <input name="pin_name" id="pin_name" type="text" class="form-control" required/>
                <a class="msg-error-input"></a>
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
                <a class="msg-error-input"></a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label>Maps</label>
                <?php $this->load->view('add/maps_select'); ?>
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
                <input name="pin_call" id="pin_call" type="phone" class="form-control"/>
                <a class="msg-error-input"></a>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <label>Email</label>
                <input name="pin_email" id="pin_email" type="email" class="form-control"/>
                <a class="msg-error-input"></a>
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
                <a class="btn btn-white rounded-pill w-100 py-3 mb-2" style="border: 2.5px solid black;"><i class="fa-solid fa-location-arrow"></i> Save Marker & Set Direction</a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <button class="btn btn-dark rounded-pill w-100 py-3" type="Submit"><i class="fa-solid fa-floppy-disk"></i> Save Marker</button>
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
        <button class="btn btn-dark rounded-pill w-100 py-2 px-3"><i class="fa-solid fa-floppy-disk"></i> Save All Marker</button>
    </form>
</div>

<div class="modal fade" id="importMarker" tabindex="-1" aria-labelledby="addGalleriesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGalleriesLabel">Import Marker</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <a class="btn btn-dark w-100 rounded-pill py-2 px-3 mb-2" id="download-template-btn"><i class="fa-solid fa-download"></i> Download Template</a>
                <hr>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Only Accept CSV file <b>(Max : 20 mb)</b></label>
                    <input class="form-control" type="file" id="pin-data"  accept=".csv">
                </div>
                <a class="btn btn-dark w-100 rounded-pill py-2 px-3" onclick="generate_form()"><i class="fa-solid fa-plus"></i> Generate Form</a>
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
</style>
<div class="modal fade" id="importMarkerMap" tabindex="-1" aria-labelledby="addGalleriesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGalleriesLabel">Imported Marker Map</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

    $(document).ready(function() {
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
    })

    const generate_form = () => {
        const fileInput = $('#pin-data').get(0)
        const file = fileInput.files[0]
        Swal.showLoading()

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
                                                <a class="btn btn-dark py-1 px-2 float-start" style='font-size:var(--textSM);' onclick="copy_all_cat(${row})"><i class="fa-solid fa-copy"></i> Using this category for all pin after this</a>
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
                                                <a class='btn btn-dark d-block mx-auto' onclick="delete_imported_pin(${row})"><i class="fa-solid fa-trash"></i></a>
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
                            <a class="btn btn-dark mb-4 rounded-pill py-3 px-4" data-bs-toggle="modal" data-bs-target="#importMarkerMap">
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
