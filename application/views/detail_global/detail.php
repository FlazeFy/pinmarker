<style>
    .map-board {
        height:30vh;
        border-radius: 15px;
    }
</style>

<?php 
    $view = $this->session->userdata('view_mode_global_list_pin');
?>

<div class="d-flex justify-content-between mb-4">
    <a class="btn btn-dark rounded-pill <?php if (!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> px-4 me-2" href="/GlobalListController"><i class="fa-solid fa-arrow-left"></i> <?php if (!$is_mobile_device){ echo "Back"; } ?></a>
    <?php 
        if ($is_mobile_device){
            echo "
                <div>
                    <a class='btn btn-dark rounded-pill px-4 py-2' id='share-global-pin-btn'><i class='fa-solid fa-paper-plane'></i> "; if(!$is_signed){ echo "Share"; } echo"</a>";
                    if($is_signed){
                        echo "
                            <a class='btn btn-success rounded-pill px-4 py-2 ms-1' id='save-all-to-my-pin-btn'><i class='fa-solid fa-bookmark'></i></a>
                            <form action='/DetailGlobalController/edit_toggle/$dt_detail->id' method='POST' class='d-inline' id='remove-list-form'>";
                                if($this->session->userdata('is_global_edit_mode') == false){
                                    echo "<button class='btn btn-light rounded-pill px-4 py-2'><i class='fa-solid fa-pen-to-square'></i></button>";
                                } else {
                                    echo "<button class='btn btn-dark rounded-pill px-4 py-2'><i class='fa-solid fa-pen-to-square'></i></button>";
                                }
                            echo"
                            </form>
                            <form action='/DetailGlobalController/delete_global_list/$dt_detail->id' method='POST' class='d-inline' id='remove-list-form'>
                                <a class='btn btn-danger rounded-pill px-4 py-2' id='remove-list-btn' onclick='remove_list()'><i class='fa-solid fa-trash'></i></a>
                            </form>
                        ";    
                    }
                echo "
                </div>
            ";
        }
    ?>
</div>

<div class='mb-4 no-animation'>
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
                if($is_signed && !$is_mobile_device){
                    echo "<a class='btn btn-success rounded-pill px-3 py-2 me-2' id='save-all-to-my-pin-btn'><i class='fa-solid fa-bookmark'></i> Save All to My Pin</a>";
                }
                if (!$is_mobile_device){
                    echo "<a class='btn btn-dark rounded-pill px-3 py-2' id='share-global-pin-btn'><i class='fa-solid fa-paper-plane'></i> Share</a>";
                }
            ?>
            <?php 
                if($is_signed && $is_editable && !$is_mobile_device){
                    echo "
                        <form action='/DetailGlobalController/edit_toggle/$dt_detail->id' method='POST' class='d-inline ms-2'>";
                            if($this->session->userdata('is_global_edit_mode') == false){
                                echo "<button class='btn btn-light rounded-pill px-3 py-2 me-2' id='open-edit-mode-btn'><i class='fa-solid fa-pen-to-square'></i> Open Edit Mode</button>";
                            } else {
                                echo "<button class='btn btn-danger rounded-pill px-3 py-2 me-2' id='close-edit-mode-btn'><i class='fa-solid fa-pen-to-square'></i> Close Edit Mode</button>";
                            }
                        echo"</form>
                    ";
                }
            ?>
            <?php 
                if($is_signed && $is_editable && !$is_mobile_device){
                    echo "
                        <form action='/DetailGlobalController/delete_global_list/$dt_detail->id' method='POST' class='d-inline ms-2' id='remove-list-form'>
                            <a class='btn btn-danger rounded-pill px-3 py-2 me-2' id='remove-list-btn' onclick='remove_list()'><i class='fa-solid fa-trash'></i> Delete Global List</a>
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
                            <a class='btn btn-success rounded-pill' id='edit-list-detail-submit-btn'>Save Changes</a>
                        </form>
                    </div>
                    <div class='col-lg-6 col-md-6 col-sm-12 col-12'>";
                        if($this->session->userdata('is_global_edit_mode')){
                            echo "<label class='mb-1'>Manage Tag</label><br>
                            <div id='selected-tag-holder'>";
                                $list_tag = json_decode($dt_detail->list_tag);
                                foreach($list_tag as $idx => $dt){
                                    echo "<a class='pin-box-label me-2 mb-1 text-decoration-none list-tag-btn'>#$dt->tag_name</a>";
                                }
                                echo "
                            </div>
                            <br><label class='mt-2'>Add Tag</label><br>
                            <div class='mt-2' id='available-tag-holder'>";
                                $list_tag_names = array_column($list_tag, 'tag_name');

                                foreach ($dt_global_tag as $dt) {
                                    if (!in_array($dt->tag_name, $list_tag_names)) {
                                        echo "<a class='pin-tag-btn me-2 mb-1 text-decoration-none'>#$dt->tag_name</a>";
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
            <h5 class="<?php if($is_mobile_device){ echo "mt-2"; } ?>">List Marker</h5>
        </div>
        <div>
            <?php 
                if($is_editable){
                    echo "
                        <a class='btn btn-success rounded-pill px-3 py-2' data-bs-target='#addMarker' id='add-marker-btn' data-bs-toggle='modal'><i class='fa-solid fa-plus'></i> "; if(!$is_mobile_device){ echo "Add Marker"; } echo"</a>
                    ";
                    $this->load->view('detail_global/add_pin');
                }
            ?>
            <form action="/DetailGlobalController/view_global_list_pin/<?= $dt_detail->id ?>" method="POST" class="d-inline">
                <button class='btn btn-dark rounded-pill px-3 py-2'><i class='fa-solid fa-table'></i> 
                    <?php if (!$is_mobile_device): ?>
                        See <?php if($view == 'table'){ echo'Catalog'; } else { echo 'Table'; } ?> View
                    <?php endif; ?>
                </button>
            </form>
            <?php $this->load->view('detail_global/whole_map'); ?>
        </div>
    </span>
    <div class="row mt-3 <?php if($view == 'catalog'){ echo 'grid';} ?>">
        <?php 
            if($view == 'catalog'){
                foreach($dt_pin_list as $dt){
                    echo "
                        <div class='col-lg-6 col-md-12 col-sm-12 col-12 grid-item'>
                            <div class='pin-box solid'>
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
                                echo"
                                    <p class='mt-2 mb-0 fw-bold'>Added At</p>
                                    <p>
                                        <span class='date-target'>$dt->created_at</span> 
                                        <span>by <button class='btn-account-attach'>@$dt_detail->created_by</button></span>
                                    </p>
                                ";

                                $data['dt'] = $dt;
                                $this->load->view('detail_global/gallery',$data);

                                if($is_signed){
                                    echo "<a class='btn btn-success rounded-pill px-3 py-2 me-1 save-to-my-pin-btn'><i class='fa-solid fa-bookmark'></i> "; if(!$is_mobile_device){ echo "Save to My Pin"; } echo"</a>";
                                }
                                if($is_editable){
                                    echo "<a class='btn btn-danger rounded-pill px-3 py-2 me-1 remove-pin-btn' onclick='remove_pin("; echo '"'; echo $dt->id; echo '"'; echo")'><i class='fa-solid fa-trash'></i> "; if(!$is_mobile_device){ echo "Remove"; } echo"</a>";
                                }
                                echo"
                                <a class='btn btn-light rounded-pill px-3 py-2 me-1 set-direction-btn' href='https://www.google.com/maps/dir/My+Location/$dt->pin_lat,$dt->pin_long'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>
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

                                                $data['dt'] = $dt;
                                                $this->load->view('detail_global/gallery',$data);
                                                
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
                                                    echo "<a class='btn btn-success rounded-pill px-2 py-1 me-2 w-100 mb-2 save-to-my-pin-btn'><i class='fa-solid fa-bookmark'></i> Save to My Pin</a>";
                                                }
                                                echo "
                                                <a class='btn btn-light rounded-pill px-2 py-1 w-100 mb-2 set-direction-btn' href='https://www.google.com/maps/dir/My+Location/$dt->pin_lat,$dt->pin_long'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>";
                                                if($is_editable){
                                                    echo "<a class='btn btn-danger rounded-pill px-2 py-1 w-100 remove-pin-btn' onclick='remove_pin("; echo '"'; echo $dt->id; echo '"'; echo")'><i class='fa-solid fa-trash'></i> Remove</a>";
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
                $('#remove-list-form').submit()
            }
        });
    }

    $( document ).ready(function() {
        const date_holder = document.querySelectorAll('.date-target')

        date_holder.forEach(e => {
            const date = new Date(e.textContent);
            e.textContent = getDateToContext(e.textContent, "calendar")
        })

        $('#share-global-pin-btn').on('click', function() {
            messageCopy('http://127.0.0.1:8080/DetailGlobalController/view/<?= $dt_detail->id ?>')
        })
        $('.list-tag-btn').on('click', function() {
            const idx = $(this).index('.list-tag-btn')
            
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

        $(document).on('click', '.pin-tag-btn:not(.remove)', function() {
            const idx = $(this).index('.pin-tag-btn')
            const tag_name = $(this).text()

            let tagEl = `<a class='pin-tag-btn remove me-2 mb-1 text-decoration-none bg-white' style='color:var(--primaryColor) !important;
                border: calc(var(--spaceMini)/2) solid var(--primaryColor);'>${tag_name}</a>`

            $('#selected-tag-holder').append(tagEl)
            $(this).remove()
        })
        $(document).on('click', '.pin-tag-btn.remove', function() {            
            const idx = $(this).index('.pin-tag-btn.remove')
            const tag_name = $(this).text()
            let tagEl = ''

            tagEl = `<a class='pin-tag-btn me-2 mb-1 text-decoration-none'>${tag_name}</a>`

            $('#available-tag-holder').append(tagEl)
            $(this).remove()
        })
        
        $(document).on('click', '#edit-list-detail-submit-btn', function() {      
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
