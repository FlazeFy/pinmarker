<br>
<form action="/AddGlobalListController/add_list" method="POST" id="form-add-global-list">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
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
            <input name="list_name" id="list_name" type="text" class="form-control form-validated" maxlength="75" required/>
            <input name="list_code" id="list_code" type="text" class="form-control form-validated" maxlength="6"/>
            <a class="msg-error-input" id='list_code_msg'></a>
            <label>Description</label>
            <textarea name="list_desc" id="list_desc" rows="4" class="form-control"></textarea>

            <div id="map-board"></div>
            <div id="selected-pin-holder"></div>

            <?php $this->load->view('add_global_list/manage_tag'); ?>
            <hr>

            <a class="btn btn-success w-100 py-3 mt-3" id="submit-btn"><i class="fa-solid fa-floppy-disk"></i> Save Global List <span id="submit-note"></span></a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <?php $this->load->view('add_global_list/manage_pin'); ?>        
        </div>
    </div>
</form>

<script type="text/javascript">
    let markers = []
    let map
    let selected_pin = []

    $(document).ready(function() {
        // Tag choose
        $(document).on('click', '.pin-tag-btn:not(.remove)', function() {
            const idx = $(this).index('.pin-tag-btn')
            let tagEl = ''
            const tag_name = $(this).text()

            if($('#selected-tag-holder').children().length > 0){
                tagEl = `
                    <a class='pin-tag-btn remove me-2 mb-1 text-decoration-none'>${tag_name}</a>
                `
            } else {
                tagEl = `
                    <label>Selected Tags</label><br>
                    <a class='pin-tag-btn remove me-2 mb-1 text-decoration-none'>${tag_name}</a>
                `
            }
            $('#selected-tag-holder').append(tagEl)
            $(this).remove()
        })

        $(document).on('click', '.pin-tag-btn.remove', function() {            
            const idx = $(this).index('.pin-tag-btn.remove')
            const tag_name = $(this).text()
            let tagEl = ''

            tagEl = `
                <a class='pin-tag-btn me-2 mb-1 text-decoration-none'>${tag_name}</a>
            `

            $('#available-tag-holder').append(tagEl)
            $(this).remove()
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
        $(document).on('click', '#submit-btn', function() {  
            let listTag = null
                
            if($('#selected-tag-holder').children().length > 0){
                listTag = []
                $('#selected-tag-holder .pin-tag-btn').each(function(idx, el) {
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