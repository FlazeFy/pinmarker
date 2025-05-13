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
    <a class="btn btn-danger <?php if (!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> px-4 me-2" href="/GlobalListController"><i class="fa-solid fa-arrow-left"></i> <?php if (!$is_mobile_device){ echo "Back"; } ?></a>
    <?php 
        if ($is_mobile_device){
            echo "
                <div>
                    <a class='btn btn-dark px-4 py-2' id='share-global-pin-btn'><i class='fa-solid fa-paper-plane'></i> "; if(!$is_signed){ echo "Share"; } echo"</a>";
                    if($is_signed){
                        echo "
                            <a class='btn btn-success px-4 py-2 ms-1' id='save-all-to-my-pin-btn'><i class='fa-solid fa-bookmark'></i></a>
                            <form action='/DetailGlobalController/edit_toggle/$dt_detail->id' method='POST' class='d-inline' id='remove-list-form'>";
                                if($this->session->userdata('is_global_edit_mode') == false){
                                    echo "<button class='btn btn-light px-4 py-2' id='toggle-edit-btn'><i class='fa-solid fa-pen-to-square'></i></button>";
                                } else {
                                    echo "<button class='btn btn-dark px-4 py-2' id='toggle-edit-btn'><i class='fa-solid fa-pen-to-square'></i></button>";
                                }
                            echo"
                            </form>
                            <form action='/DetailGlobalController/delete_global_list/$dt_detail->id' method='POST' class='d-inline' id='remove-list-form'>
                                <a class='btn btn-danger px-4 py-2' id='remove-list-btn' onclick='remove_list()'><i class='fa-solid fa-trash'></i></a>
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
    <div class="container-fluid bg-light-primary p-4">
        <?php $this->load->view('detail_global/toolbar'); ?>
        <?php $this->load->view('detail_global/detail'); ?>
        <?php $this->load->view('detail_global/props'); ?>
    </div>
    <hr>
    <?php
        $data['view'] = $view; 
        $this->load->view('detail_global/pin_manage', $data); 
        $this->load->view('detail_global/all_pin', $data);
    ?>
    

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
        $('#share-global-pin-btn').on('click', function() {
            messageCopy('http://127.0.0.1:8080/DetailGlobalController/view/<?= $dt_detail->id ?>')
        })
        $('.save-to-my-pin-btn').on('click', function() {
            const idx = $(this).index('.save-to-my-pin-btn')
            Swal.fire({
                title: "Are you sure?",
                html: `Want to save this pin?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, save it!"
            })
            .then((result) => {
                if(result.isConfirmed){
                    const pinInfo = {
                        pin_lat: $('.pin-lat').eq(idx).val(),
                        pin_long: $('.pin-long').eq(idx).val(),
                        pin_name: $('.pin-name').eq(idx).text(),
                        pin_category: $('.pin-category').eq(idx).text(),
                        pin_address: $('.pin-address').eq(idx).text(),
                        pin_call: $('.pin-call').eq(idx).text(),
                        pin_desc: $('.pin-desc').eq(idx).text() === '- No Description -' ? null : $('.pin-desc').eq(idx).text()
                    };
                    const queryString = Object.keys(pinInfo).map(key => `${key}=${encodeURIComponent(pinInfo[key] || '')}`).join('&')

                    window.location.href = `/AddController?${queryString}`
                }                
            })
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
