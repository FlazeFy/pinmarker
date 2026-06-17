<div class="card">
    <h2 class="card-title">Multiple Add</h2>
</div>

<script>
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
</script>