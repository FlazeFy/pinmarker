<style>
    #map-board {
        height:50vh;
        border-radius: 15px;
    }
</style>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#pinTable').DataTable({
            pageLength: 14, 
            lengthMenu: [ 
                [14, 28, 75, 125],
                [14, 28, 75, 125] 
            ],
        });
        $('#pinTable_info').closest('.col-sm-12.col-md-5').remove()
    });
</script>

<div class="modal fade" id="addMarker" tabindex="-1" aria-labelledby="addGalleriesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGalleriesLabel">Add Marker</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id='close-add-marker-modal-btn' aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div id="map-board"></div>
                        <hr>
                        <label>Selected Pin</label>
                        <div id="selected-pin-holder"></div>
                        <form method="POST" action="/DetailGlobalController/add_pin_rel/<?= $dt_detail->id ?>" id="form-add-global-rel">
                            <input id="list_pin" class='d-none'  name="list_pin">
                        </form>
                        <a class="btn btn-dark w-100 py-2 px-3 mt-2" id="submit-btn"><i class="fa-solid fa-floppy-disk"></i> Submit this Pin</a>
                    </div>
                    <div class="col">
                        <label>Available Pin</label>
                        <table id="pinTable" class="display table table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>Pin Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach($dt_available_pin as $dt){
                                        $is_found = false;
                                        foreach($dt_pin_list as $pl){
                                            if($pl->pin_name == $dt->pin_name){
                                                $is_found = true;
                                                break;
                                            }
                                        }
                        
                                        if(!$is_found){
                                            echo "
                                                <tr>
                                                    <td>
                                                        <span class='pin-name-holder'>$dt->pin_name</span>
                                                        <input hidden class='pin-id-coor' value='$dt->id,$dt->pin_lat,$dt->pin_long'>  
                                                    </td>
                                                    <td class='action-btn-holder'><a class='btn btn-success btn-add-pin'><i class='fa-solid fa-plus'></i></a></td>
                                                </tr>   
                                            ";
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let markers = []
    let map
    let selected_pin = []

    function initMapAdd() {
        map = new google.maps.Map(document.getElementById("map-board"), {
            center: { lat: -6.226838579766097, lng: 106.82157923228753},
            zoom: 12,
        });

        for (let i = 0; i < markers.length; i++) {
            addMarker(markers[i])
        }
    }

    $( document ).ready(function() {
        $('#btn-add-marker').on('click', function() {
            initMapAdd()        
        })
    })

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
    $(document).ready(function() { 
        // Submit form
        $(document).on('click', '#submit-btn', function() {  
            let listTag = null
                
            if($('#selected-tag-holder').children().length > 0){
                listTag = []
                $('#selected-tag-holder .pin-tag').each(function(idx, el) {
                    listTag.push({
                        tag_name: $(el).text().replace('#','')
                    })
                })

                listTag = JSON.stringify(listTag)

                $('#list_tag').val(listTag)
            } 
            if(selected_pin.length > 0){
                let id = ''
                let pins = ''

                selected_pin.forEach((el, idx) => {
                    id += el.id + (idx < selected_pin.length - 1 ? ',' : '')
                    pins += el.pin_name + (idx < selected_pin.length - 1 ? ', ' : '')
                });
                $('#list_pin').val(id)

                Swal.fire({
                    title: "Are you sure?",
                    html: `Want to share this pin <b>${pins}</b> with others?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, share it!"
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $('#form-add-global-rel').submit()
                    }
                });
            } else {
                $('#form-add-global-rel').submit()
            }            
        })
    })
</script>