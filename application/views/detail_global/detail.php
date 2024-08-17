<style>
    .map-board {
        height:30vh;
        border-radius: 15px;
    }
</style>

<?php 
    $view = $this->session->userdata('view_mode_global_list_pin');
?>
<a class="btn btn-dark mb-4 rounded-pill py-3 px-4 me-2" href="/GlobalListController"><i class="fa-solid fa-arrow-left"></i> Back</a>
<div class='pin-box mb-4 no-animation'>
    <span class="d-flex justify-content-between">
        <div>
            <?php 
                if($this->session->userdata('is_global_edit_mode') == false){
                    echo "<h3>$dt_detail->list_name</h3>";
                } 
            ?>
        </div>
        <div>
            <?php 
                if($is_signed){
                    echo "<a class='btn btn-dark rounded-pill px-2 py-1 me-2' href='/GlobalListController'><i class='fa-solid fa-bookmark'></i> Save All to My Pin</a>";
                }
            ?>
            <a class='btn btn-dark rounded-pill px-2 py-1' id='share-global-pin'><i class='fa-solid fa-paper-plane'></i> Share</a>
            <?php 
                if($is_signed && $is_editable){
                    echo "
                        <form action='/DetailGlobalController/edit_toggle/$dt_detail->id' method='POST' class='d-inline ms-2' id='form-remove-list'>";
                            if($this->session->userdata('is_global_edit_mode') == false){
                                echo "<button class='btn btn-light rounded-pill px-2 py-1 me-2' style='border: 2px solid black;'><i class='fa-solid fa-pen-to-square'></i> Open Edit Mode</button>";
                            } else {
                                echo "<button class='btn btn-dark rounded-pill px-2 py-1 me-2'><i class='fa-solid fa-pen-to-square'></i> Close Edit Mode</button>";
                            }
                        echo"</form>
                    ";
                }
            ?>
            <?php 
                if($is_signed && $is_editable){
                    echo "
                        <form action='/DetailGlobalController/delete_global_list/$dt_detail->id' method='POST' class='d-inline ms-2' id='form-remove-list'>
                            <button class='btn btn-dark rounded-pill px-2 py-1 me-2' onclick='remove_list()'><i class='fa-solid fa-trash'></i> Delete Global List</button>
                        </form>
                    ";
                }
            ?>
        </div>
    </span>
    <?php
        if($this->session->userdata('is_global_edit_mode') == false){ 
            if($dt_detail->list_tag){
                $list_tag = json_decode($dt_detail->list_tag);
                foreach($list_tag as $idx => $dt){
                    echo "<a class='pin-box-label me-2 mb-1 text-decoration-none' href='http://127.0.0.1:8080/LoginController/view/$dt->tag_name'>#$dt->tag_name</a>";
                }
            } else {
                echo "<p class='text-secondary fst-italic'>- No Tag -</p>";
            }
        }
    ?>
    <br>
    <?php 
        if($this->session->userdata('is_global_edit_mode') == false){
            if($dt_detail->list_desc){
                echo "<p>$dt_detail->list_desc</p>";
            } else {
                echo "<p class='text-secondary fst-italic'>- No Description -</p>";
            }
        } else {
            echo "
                <div class='row'>
                    <div class='col-lg-6 col-md-6 col-sm-12 col-12'>
                        <form action='/DetailGlobalController/edit_list/$dt_detail->id' method='POST' id='edit-list-detail-form'>
                            <label>List Name</label>
                            <input name='list_name' id='list_name' value='$dt_detail->list_name' class='form-control'/>
                            <label>Description</label>
                            <textarea name='list_desc' id='list_desc' value='$dt_detail->list_desc' class='form-control'>$dt_detail->list_desc</textarea>
                            <input id='list_tag' class='d-none' name='list_tag'>
                            <a class='btn btn-dark rounded-pill' id='edit-list-detail-btn'>Save Changes</a>
                        </form>
                    </div>
                    <div class='col-lg-6 col-md-6 col-sm-12 col-12'>";
                        if($this->session->userdata('is_global_edit_mode')){
                            echo "<label class='mb-1'>Manage Tag</label><br>
                            <div id='selected-tag-holder'>";
                                $list_tag = json_decode($dt_detail->list_tag);
                                foreach($list_tag as $idx => $dt){
                                    echo "<a class='pin-box-label me-2 mb-1 text-decoration-none list-tag'>#$dt->tag_name</a>";
                                }
                                echo "
                            </div>
                            <br><label class='mt-2'>Add Tag</label><br>
                            <div class='mt-2' id='available-tag-holder'>";
                                $list_tag_names = array_column($list_tag, 'tag_name');

                                foreach ($dt_global_tag as $dt) {
                                    if (!in_array($dt->tag_name, $list_tag_names)) {
                                        echo "<a class='pin-tag me-2 mb-1 text-decoration-none'>#$dt->tag_name</a>";
                                    }
                                }
                            echo "
                            </div>";
                        }
                    echo"
                    </div>
                </div>
            ";
        }
    ?>
    <?php $this->load->view('detail_global/props'); ?>
    <hr>

    <span class="d-flex justify-content-between">
        <div>
            <h5>List Marker</h5>
        </div>
        <div>
            <?php 
                if($is_editable){
                    echo "
                        <a class='btn btn-dark rounded-pill px-2 py-1' data-bs-target='#addMarker' id='btn-add-marker' data-bs-toggle='modal'><i class='fa-solid fa-plus'></i> Add Marker</a>
                    ";
                    $this->load->view('detail_global/add_pin');
                }
            ?>
            <form action="/DetailGlobalController/view_global_list_pin/<?= $dt_detail->id ?>" method="POST" class="d-inline">
                <button class='btn btn-dark rounded-pill px-2 py-1'><i class='fa-solid fa-table'></i> See <?php if($view == 'table'){ echo'Catalog'; } else { echo 'Table'; } ?> View</button>
            </form>
            <a class='btn btn-dark rounded-pill px-2 py-1'><i class='fa-solid fa-map'></i> Whole Map</a>
        </div>
    </span>
    <div class="row mt-3 mx-2 <?php if($view == 'catalog'){ echo 'grid';} ?>">
        <?php 
            if($view == 'catalog'){
                foreach($dt_pin_list as $dt){
                    echo "
                        <div class='col-lg-6 col-md-12 col-sm-12 col-12 grid-item'>
                            <div class='pin-box'>
                                <div id='map-board-$dt->id' class='map-board'></div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        initMap({
                                            coords: {lat: $dt->pin_lat, lng: $dt->pin_long}
                                        }, 'map-board-$dt->id')
                                    })
                                </script>
                                <h3 class='mb-0'>$dt->pin_name</h3>
                                <span class='pin-box-label me-2 mb-3'>$dt->pin_category</span>
                                ";
                                echo "<br>";
                                if($dt->pin_desc){
                                    echo "<p>$dt->pin_desc</p>";
                                } else {
                                    echo "<p class='text-secondary fst-italic'>- No Description -</p>";
                                }
                                    if($dt->pin_call){
                                        echo "
                                            <p class='mt-2 mb-0 fw-bold'>Person In Touch</p>
                                            <p>$dt->pin_call</p>
                                        ";
                                    }
                                    if($dt->pin_address){
                                        echo "
                                            <p class='mt-2 mb-0 fw-bold'>Address</p>
                                            <p>$dt->pin_address</p>
                                        ";
                                    }
                                if(!$is_mobile_device){
                                    echo"
                                        <p class='mt-2 mb-0 fw-bold'>Added At</p>
                                        <p>
                                            <span class='date-target'>$dt->created_at</span> 
                                            <span>by <button class='btn-account-attach'>@$dt_detail->created_by</button></span>
                                        </p>
                                    ";
                                }
                                if($is_signed){
                                    echo "<a class='btn btn-dark rounded-pill px-2 py-1 me-2' href='/DetailController/view/$dt->id'><i class='fa-solid fa-bookmark'></i> Save to My Pin</a>";
                                }
                                    echo"
                                <a class='btn btn-dark rounded-pill px-2 py-1 me-2'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>";
                                if($is_editable){
                                    echo "<a class='btn btn-dark rounded-pill px-2 py-1 me-2' onclick='remove_pin("; echo '"'; echo $dt->id; echo '"'; echo")'><i class='fa-solid fa-trash'></i> Remove</a>";
                                }
                                echo"
                            </div>
                        </div>
                    ";
                }
            } else {
                    echo "
                        <table class='table table-bordered' id='tb_related_pin_track'>
                            <thead class='text-center'>
                                <tr>
                                    <th scope='col'>Name</th>
                                    <th scope='col'>Location</th>
                                    <th scope='col'>Context</th>
                                    <th scope='col'>Props</th>
                                    <th scope='col'>Action</th>
                                </tr>
                            </thead>
                            <tbody>";
                                foreach($dt_pin_list as $dt){
                                    echo "
                                        <tr>
                                            <td style='width: 200px;'><h6>$dt->pin_name<h6></td>
                                            <td style='width: 450px;'>
                                                <div id='map-board-$dt->id' class='map-board'></div>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        initMap({
                                                            coords: {lat: $dt->pin_lat, lng: $dt->pin_long}
                                                        }, 'map-board-$dt->id')
                                                    })
                                                </script>";
                                                if($dt->pin_address){
                                                    echo "
                                                        <p class='mt-2 mb-0 fw-bold'>Address</p>
                                                        <p>$dt->pin_address</p>
                                                    ";
                                                }
                                                echo"
                                            </td>
                                            <td>
                                                <p class='mt-2 mb-0 fw-bold'>Category</p>
                                                <p>$dt->pin_category</p>
                                                <p class='mt-2 mb-0 fw-bold'>Description</p>";
                                                if($dt->pin_desc){
                                                    echo "<p>$dt->pin_desc</p>";
                                                } else {
                                                    echo "<p class='text-secondary fst-italic'>- No Description -</p>";
                                                }
                                                if($dt->pin_call){
                                                    echo "
                                                        <p class='mt-2 mb-0 fw-bold'>Person In Touch</p>
                                                        <p>$dt->pin_call</p>
                                                    ";
                                                }
                                                echo"
                                            </td>
                                            <td>
                                                <p class='mt-2 mb-0 fw-bold'>Added At</p>
                                                <p class='date-target'>$dt->created_at</p>
                                                <p class='mt-2 mb-0 fw-bold'>Added By</p>
                                                <button class='btn-account-attach'>@$dt_detail->created_by</button>
                                            </td>
                                            <td style='width: 160px;'>";
                                                if($is_signed){
                                                    echo "<a class='btn btn-dark rounded-pill px-2 py-1 me-2 w-100 mb-2' href='/DetailController/view/$dt->id'><i class='fa-solid fa-bookmark'></i> Save to My Pin</a>";
                                                }
                                                echo "
                                                <a class='btn btn-dark rounded-pill px-2 py-1 w-100 mb-2'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>";
                                                if($is_editable){
                                                    echo "<a class='btn btn-dark rounded-pill px-2 py-1 w-100' onclick='remove_pin("; echo '"'; echo $dt->id; echo '"'; echo")'><i class='fa-solid fa-trash'></i> Remove</a>";
                                                }
                                                echo "
                                            </td>
                                        </tr>
                                    "; 
                                }
                            echo"</tbody>
                        </table>
                    ";
            }
        ?>
    </div>

    <form action="/DetailGlobalController/remove_pin/<?= $dt_detail->id ?>" method="POST" id="form-remove-pin">
        <input hidden name="pin_rel_id" id="pin_rel_id">
    </form>
    <form action="/DetailGlobalController/remove_tag/<?= $dt_detail->id ?>" method="POST" id="form-remove-tag">
        <input hidden name="tag_selected_idx" id="tag_selected_idx">
    </form>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

<script>
    function initMap(markerData, mapId) {
        var map = new google.maps.Map(document.getElementById(mapId), {
            center: markerData.coords,
            zoom: 12
        });

        var marker = new google.maps.Marker({
            position: markerData.coords,
            map: map
        });

        if (markerData.content) {
            var infoWindow = new google.maps.InfoWindow({
                content: markerData.content
            });

            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });
        }
    }

    const remove_pin = (id) => {
        Swal.fire({
            title: "Are you sure?",
            html: `Want to remove this attached pin?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        })
        .then((result) => {
            if (result.isConfirmed) {
                $('#pin_rel_id').val(id)
                $('#form-remove-pin').submit()
            }
        });
    }

    const remove_list = () => {
        Swal.fire({
            title: "Are you sure?",
            html: `Want to remove this list with attached pin?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        })
        .then((result) => {
            if (result.isConfirmed) {
                $('#form-remove-list').submit()
            }
        });
    }

    $( document ).ready(function() {
        const date_holder = document.querySelectorAll('.date-target')

        date_holder.forEach(e => {
            const date = new Date(e.textContent);
            e.textContent = getDateToContext(e.textContent, "calendar")
        })

        $('#share-global-pin').on('click', function() {
            messageCopy('http://127.0.0.1:8080/DetailGlobalController/view/<?= $dt_detail->id ?>')
        })
        $('.list-tag').on('click', function() {
            const idx = $(this).index('.list-tag')
            
            Swal.fire({
                title: "Are you sure?",
                html: `Want to remove this tag?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            })
            .then((result) => {
                if(result.isConfirmed){
                    $('#tag_selected_idx').val(idx)
                    $('#form-remove-tag').submit()
                }                
            })
        })

        $(document).on('click', '.pin-tag:not(.remove)', function() {
            const idx = $(this).index('.pin-tag')
            const tag_name = $(this).text()

            let tagEl = `<a class='pin-tag remove me-2 mb-1 text-decoration-none bg-white' style='color:var(--primaryColor) !important;
                border: calc(var(--spaceMini)/2) solid var(--primaryColor);'>${tag_name}</a>`

            $('#selected-tag-holder').append(tagEl)
            $(this).remove()
        })
        $(document).on('click', '.pin-tag.remove', function() {            
            const idx = $(this).index('.pin-tag.remove')
            const tag_name = $(this).text()
            let tagEl = ''

            tagEl = `<a class='pin-tag me-2 mb-1 text-decoration-none'>${tag_name}</a>`

            $('#available-tag-holder').append(tagEl)
            $(this).remove()
        })
        
        $(document).on('click', '#edit-list-detail-btn', function() {      
            if($('#selected-tag-holder').children().length > 0){
                listTag = []
                $('#selected-tag-holder a').each(function(idx, el) {
                    listTag.push({
                        tag_name: $(el).text().replace('#','')
                    })
                })

                listTag = JSON.stringify(listTag)

                $('#list_tag').val(listTag)
            }       
            $('#edit-list-detail-form').submit()
        })
    })
</script>
