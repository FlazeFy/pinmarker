<style>
    #map-board {
        height:50vh;
        border-radius: 15px;
    }
</style>

<br>
<div class="row">
    <div class="col">
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
        <form action="/AddGlobalListController/add_list" method="POST" id="form-add-global-list">
            <label>List Name</label>
            <input name="list_name" id="list_name" type="text" class="form-control" required/>
            <a class="msg-error-input"></a>
            <label>List Code</label>
            <input name="list_code" id="list_code" type="text" class="form-control" maxlength="6"/>
            <a class="msg-error-input" id='list_code_msg'></a><br>
            <label>Description</label>
            <textarea name="list_desc" id="list_desc" rows="5" class="form-control"></textarea>
            <a class="msg-error-input"></a>
            <label>Tags</label>
            <div class='mt-2' id='available-tag-holder'>
                <?php 
                    foreach($dt_global_tag as $dt){
                        echo "<a class='pin-tag me-2 mb-1 text-decoration-none'>#$dt->tag_name</a>";
                    }
                ?>
            </div>
            <div id='selected-tag-holder'></div>
            <input id="list_tag" class='d-none' name="list_tag">
            <hr>
            <label>Attach some Pin</label>
            <div id="available-pin-holder">
                <?php 
                    foreach($dt_available_pin as $dt){
                        echo "
                            <a class='pin-name me-2 mb-1 text-decoration-none'><i class='fa-solid fa-location-dot'></i> $dt->pin_name</a>
                            <input hidden class='pin-id-coor' value='$dt->id,$dt->pin_lat,$dt->pin_long'>    
                        ";
                    }
                ?>
            </div>
            <input id="list_pin" class='d-none'  name="list_pin">
            <a class="btn btn-dark rounded-pill w-100 py-3 mt-3" id="btn_submit"><i class="fa-solid fa-floppy-disk"></i> Save Global List <span id="submit-note"></span></a>
        </form>
    </div>
    <div class="col">
        <div id="map-board"></div>
        <div id="selected-pin-holder"></div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

<script type="text/javascript">
    let markers = []
    let map
    let selected_pin = []

    $(document).ready(function() {
        // Tag choose
        $(document).on('click', '.pin-tag:not(.remove)', function() {
            const idx = $(this).index('.pin-tag')
            let tagEl = ''
            const tag_name = $(this).text()

            if($('#selected-tag-holder').children().length > 0){
                tagEl = `
                    <a class='pin-tag remove me-2 mb-1 text-decoration-none'>${tag_name}</a>
                `
            } else {
                tagEl = `
                    <label>Selected Tags</label><br>
                    <a class='pin-tag remove me-2 mb-1 text-decoration-none'>${tag_name}</a>
                `
            }
            $('#selected-tag-holder').append(tagEl)
            $(this).remove()
        })
        $(document).on('click', '.pin-tag.remove', function() {            
            const idx = $(this).index('.pin-tag.remove')
            const tag_name = $(this).text()
            let tagEl = ''

            tagEl = `
                <a class='pin-tag me-2 mb-1 text-decoration-none'>${tag_name}</a>
            `

            $('#available-tag-holder').append(tagEl)
            $(this).remove()
        })
        // Pin choose
        $(document).on('click', '.pin-name:not(.remove)', function() {
            const idx = $(this).index('.pin-name')
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
                    <a class='pin-name remove me-2 mb-1 text-decoration-none'><i class='fa-solid fa-location-dot'></i> ${pin_name}<input hidden class='d-none remove-from-id' value='${coor_split[0]}'></a>
                `
            } else {
                tagEl = `
                    <label>Attached Pin</label><br>
                    <a class='pin-name remove me-2 mb-1 text-decoration-none'><i class='fa-solid fa-location-dot'></i> ${pin_name}<input hidden class='d-none remove-from-id' value='${coor_split[0]}'></a>
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
            initMap()
    
            $('.pin-name').eq(idx).remove()
        })
        $(document).on('click', '.pin-name.remove, .remove-via-marker', function() {            
            const idx = $(this).closest('.pin-name.remove').index('.pin-name.remove')
            const tag_name = $('.pin-name.remove').eq(idx).text()

            const id = $(this).find('input.remove-from-id').val()
            selected_pin.forEach((el,idxEl) => {
                if(id == el.id){
                    selected_pin.splice(idxEl, 1)
                    return
                }
            });

            const tagEl = `
                <a class='pin-name me-2 mb-1 text-decoration-none'><i class='fa-solid fa-location-dot'></i> ${tag_name}</a>
            `
            $('#available-pin-holder').append(tagEl)
            
            $('.pin-name.remove').eq(idx).remove()
            markers.splice(idx, 1)
            initMap()
        })

        // Input field
        $(document).on('input', '#list_code', function() {            
            const val = $(this).val().trim()

            if(val.length != 6){
                $('#submit-note').empty()
                $('#list_code_msg').html(`<span class='text-danger'><i class="fa-solid fa-triangle-exclamation"></i> Your code is not valid</span>`)
            } else {
                $('#submit-note').text('with Code')
                $('#list_code_msg').html(`<span class='text-success'><i class="fa-solid fa-circle-check"></i> Your code is valid</span>`)
            }
        })

        // Submit form
        $(document).on('click', '#btn_submit', function() {  
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
                    confirmButtonText: "Yes, delete it!"
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $('#form-add-global-list').submit()
                    }
                });
            } else {
                $('#form-add-global-list').submit()
            }            
        })
    })

    function initMap() {
        map = new google.maps.Map(document.getElementById("map-board"), {
            center: { lat: -6.226838579766097, lng: 106.82157923228753},
            zoom: 12,
        });

        for (let i = 0; i < markers.length; i++) {
            addMarker(markers[i])
        }
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
</script>