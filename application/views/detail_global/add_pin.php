<style>
    #map-board {
        height:50vh;
        border-radius: 15px;
    }
</style>

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
                        <a class="btn btn-dark w-100 rounded-pill py-2 px-3 mt-2" id="submit-btn"><i class="fa-solid fa-floppy-disk"></i> Submit this Pin</a>
                    </div>
                    <div class="col">
                        <label>Selected Pin</label>
                        <div id="selected-pin-holder"></div>
                        <form method="POST" action="/DetailGlobalController/add_pin_rel/<?= $dt_detail->id ?>" id="form-add-global-rel">
                            <input id="list_pin" class='d-none'  name="list_pin">
                        </form>
                        <hr>
                        <label>Available Pin</label>
                        <div id="available-pin-holder">
                            <?php 
                                foreach($dt_available_pin as $dt){
                                    $found = false;
                                    foreach($dt_pin_list as $pl){
                                        if($pl->pin_name == $dt->pin_name){
                                            $found = true;
                                            break;
                                        }
                                    }

                                    if(!$found){
                                        echo "
                                            <a class='pin-name-btn me-2 mb-1 text-decoration-none'><i class='fa-solid fa-location-dot'></i> $dt->pin_name</a>
                                            <input hidden class='pin-id-coor' value='$dt->id,$dt->pin_lat,$dt->pin_long'>    
                                        ";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

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
        // Pin choose
        $(document).on('click', '.pin-name-btn:not(.remove)', function() {
            const idx = $(this).index('.pin-name-btn')
            let pinEl = ''
            const pin_name = $(this).text().trim()
            const coor_split = $('.pin-id-coor').eq(idx).val().split(',')

            selected_pin.push({
                'id': coor_split[0],
                'pin_name': pin_name,
                'pin_lat': coor_split[1],
                'pin_long': coor_split[2],
            })

            if($('#selected-pin-holder').children().length > 0){
                tagEl = `
                    <a class='pin-name-btn remove me-2 mb-1 text-decoration-none'><i class='fa-solid fa-location-dot'></i> ${pin_name}<input hidden class='d-none remove-from-id' value='${coor_split[0]}'></a>
                `
            } else {
                tagEl = `
                    <label>Attached Pin</label><br>
                    <a class='pin-name-btn remove me-2 mb-1 text-decoration-none'><i class='fa-solid fa-location-dot'></i> ${pin_name}<input hidden class='d-none remove-from-id' value='${coor_split[0]}'></a>
                `
            }
            $('#selected-pin-holder').append(tagEl)

            markers.push({
                coords: {lat: parseFloat(coor_split[1]), lng: parseFloat(coor_split[2])},
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/red.png',
                    scaledSize: {width: 40, height: 40}
                },
                content: 
                `<div class='mt-4'>
                    <h6>${pin_name}</h6>
                    <a class='btn btn-dark remove-via-marker rounded-pill px-2 py-1' style='font-size:12px;'><i class='fa-regular fa-circle-xmark'></i> Remove<input hidden class='d-none remove-from-id' value='${coor_split[0]}'></a>
                </div>`
            })

            addMarker(markers)
            initMapAdd()
    
            $('.pin-name-btn').eq(idx).remove()
        })
        $(document).on('click', '.pin-name-btn.remove, .remove-via-marker', function() {            
            const idx = $(this).closest('.pin-name-btn.remove').index('.pin-name-btn.remove')
            const tag_name = $('.pin-name-btn.remove').eq(idx).text()

            const id = $(this).find('input.remove-from-id').val()
            selected_pin.forEach((el,idxEl) => {
                if(id == el.id){
                    selected_pin.splice(idxEl, 1)
                    return
                }
            });

            const tagEl = `
                <a class='pin-name-btn me-2 mb-1 text-decoration-none'><i class='fa-solid fa-location-dot'></i> ${tag_name}</a>
            `
            $('#available-pin-holder').append(tagEl)
            
            $('.pin-name-btn.remove').eq(idx).remove()
            markers.splice(idx, 1)
            initMapAdd()
        })

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