<?php 
    if($this->session->userdata('is_catalog_view') == false || $this->session->userdata('open_pin_list_category')){
        if(count($dt_my_pin['data']) > 0){
            if($this->session->userdata('is_catalog_view') == true){
                echo "<div class='row'>
                    <div class='col-6'>";
            }

            if($this->session->userdata('role_key') == 0){
                echo 
                "<table class='table table-bordered my-3'>
                    <thead class='text-center'>
                        <tr>
                            <th style='width: 240px;'>Pin Name</th>
                            <th style='min-width: 240px;'>Detail</th>
                            <th style='min-width: 240px;'>Contact</th>
                            <th style='width: 200px;'>Props</th>
                            <th style='width: 100px;'>Action</th>
                        </tr>
                    </thead>
                    <tbody>";
                        foreach($dt_my_pin['data'] as $dt){
                            echo "
                                <tr>
                                    <td>$dt->pin_name</td>
                                    <td>
                                        <p class='mt-2 mb-0 fw-bold'>Category</p>
                                        <a>$dt->pin_category</a>
                                        <p class='mt-2 mb-0 fw-bold'>Description</p>";
                                        if($dt->pin_desc){
                                            echo "<a>$dt->pin_desc</a>";
                                        } else {
                                            echo "<a class='text-secondary fst-italic text-decoration-none'>- No Description -</a>";
                                        }
                                        echo"<p class='mt-2 mb-0 fw-bold'>Coordinate</p>
                                        <a>$dt->pin_lat, $dt->pin_long</a>
                                        <p class='mt-2 mb-0 fw-bold'>Address</p>";
                                        if($dt->pin_address){
                                            echo "<a>$dt->pin_address</a>";
                                        } else {
                                            echo "<a class='text-secondary fst-italic text-decoration-none'>- No Address -</a>";
                                        }
                                        echo"
                                    </td>
                                    <td>
                                        <p class='mt-2 mb-0 fw-bold'>Person in Touch</p>";
                                        if($dt->pin_person){
                                            echo "<a>$dt->pin_person</a>";
                                        } else {
                                            echo "<a class='text-secondary fst-italic text-decoration-none'>- No Person in Touch -</a>";
                                        }
                                        echo"
                                        <p class='mt-2 mb-0 fw-bold'>Phone Number</p>";
                                        if($dt->pin_call){
                                            echo "<a>$dt->pin_call</a>";
                                        } else {
                                            echo "<a class='text-secondary fst-italic text-decoration-none'>- No Phone Number -</a>";
                                        }
                                        echo"
                                        <p class='mt-2 mb-0 fw-bold'>Email</p>";
                                        if($dt->pin_email){
                                            echo "<a>$dt->pin_email</a>";
                                        } else {
                                            echo "<a class='text-secondary fst-italic text-decoration-none'>- No Email -</a>";
                                        }
                                        echo"
                                    </td>
                                    <td>
                                        <p class='mt-2 mb-0 fw-bold'>Created At</p>
                                        <a>
                                            <span class='date-target'>$dt->created_at</span> 
                                            <span>by <button class='btn-account-attach'>@$dt->created_by</button></span>
                                        </a>
                                        <p class='mt-2 mb-0 fw-bold'>Updated At</p>
                                        <span class='date-target'>$dt->updated_at</span> 
                                        <p class='mt-2 mb-0 fw-bold'>Deleted At</p>
                                        <span class='date-target'>$dt->deleted_at</span> 
                                    </td>
                                    <td style='max-width:100px;'>
                                        <input class='id_holder' value='$dt->id' hidden>
                                        <button class='btn btn-dark w-100 mb-2 edit-pin-btn'><i class='fa-solid fa-pen-to-square'></i></button>
                                        <button class='btn btn-danger w-100 mb-2 delete-pin'><i class='fa-solid fa-trash'></i></button>
                                        <button class='btn btn-dark w-100 mb-2 share-pin-btn'><i class='fa-solid fa-paper-plane'></i></button>
                                        <button class='btn btn-dark w-100 mb-2 see-map-btn'><i class='fa-solid fa-map'></i></button>
                                    </td>
                                </tr>
                            ";
                        }
                    echo"</tbody>
                </table>
                <form id='form-delete-pin' method='POST' action='/ListController/soft_del_pin' hidden>
                    <input name='id' id='pin_id_delete'>
                </form>
                ";
            } else {
                if($this->session->userdata('is_catalog_view') == false){
                    echo "<div class='row'>";
                }
                foreach($dt_my_pin['data'] as $dt){
                    if($this->session->userdata('is_catalog_view') == false){
                        echo "
                            <div class='col-lg-4 col-md-6'>";
                    }
                        echo"
                            <div class='pin-box "; if($is_mobile_device){ echo "solid"; } echo"'>
                                <div class='pin_info-holder'>
                                    <span class='pin-category me-1'>$dt->pin_category</span>";
                                    if($dt->total_visit > 0){
                                        echo "<span class='total-visit'>$dt->total_visit Visit</span>";
                                    }
                                    if($dt->is_favorite == 1){
                                        echo "<span class='is-favorite ms-1'><i class='fa-solid fa-heart'></i></span>";
                                    }
                                    if($dt->pin_person){
                                        echo "<button class='pin-person ms-1' data-bs-toggle='popover' title='Pin Person' data-bs-content='$dt->pin_person'><i class='fa-solid fa-user'></i></button>";
                                    }
                                    if($dt->pin_call){
                                        echo "<button class='pin-person ms-1' data-bs-toggle='popover' title='Pin Call' data-bs-content='$dt->pin_call'><i class='fa-solid fa-phone'></i></button>";
                                    }
                                    if($dt->pin_email){
                                        echo "<button class='pin-person ms-1' data-bs-toggle='popover' title='Pin Email' data-bs-content='$dt->pin_email'><i class='fa-solid fa-envelope'></i></button>";
                                    }
                                echo"
                                </div>
                                <h4>$dt->pin_name</h4>
                                <div class='pin-desc'>";
                                if($dt->pin_desc){
                                    echo "<p class='text-secondary'>$dt->pin_desc</p>";
                                } else {
                                    echo "<p class='text-secondary fst-italic'>- No Description -</p>";
                                }
                                echo "
                                </div>
                                <div class='row py-0 my-0'>";
        
                                if(!$is_mobile_device){
                                    echo"
                                    <div class='col-md-6 col-sm-12'>
                                        <p class='mt-2 mb-0 fw-bold'>Created At</p>
                                        <p class='date-target'>$dt->created_at</p>
                                    </div>";
                                }
                                echo"
                                    <div class='col-md-6 col-sm-12'>
                                        <p class='mt-2 mb-0 fw-bold'>Last Visit</p>
                                        <p class='date-target'>"; echo $dt->last_visit ?? "-"; echo"</p>
                                    </div>
                                </div>
                                <a class='btn btn-primary px-2 py-1 me-2 see-detail-btn' href='/DetailController/view/$dt->id'><i class='fa-solid fa-circle-info'></i> See Detail</a>
                                <a class='btn btn-primary-outline px-2 py-1 set-direction-btn' href='https://www.google.com/maps/dir/My+Location/$dt->pin_lat,$dt->pin_long'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>
                            </div>";

                    if($this->session->userdata('is_catalog_view') == false){
                        echo "
                            </div>
                        ";
                    }
                }
                if($this->session->userdata('is_catalog_view') == false){
                    echo "</div>";
                }
            }

            echo "<div class='d-inline-block'>
                <h5>Page</h5>";

            $active = 0;
            if($this->session->userdata('page_pin')){
                $active = $this->session->userdata('page_pin');
            }

            for($i = 0; $i < $dt_my_pin['total_page']; $i++){
                $page = $i + 1;
                echo "
                    <form method='POST' class='d-inline' action='/ListController/navigate/$i'>
                        <button class='btn btn-page"; 
                        if($active == $i){echo " active";}
                        echo" me-1' type='submit'>$page</button>
                    </form>
                ";
            }

            if($this->session->userdata('is_catalog_view') == true){
                echo "</div>
                    </div>
                    <div class='col-6'>
                        <h4>Maps</h4>
                        <div id='map-category'></div>
                    </div>
                </div>";
            }
            echo "</div>";
        } else {
            echo "
                <div class='text-center my-3'>
                    <img src='http://127.0.0.1:8080/public/images/pin.png' class='img nodata-icon'>
                    <h5>You have no marker to show</h5>
                </div>
            ";
        }
    } else {
        if(count($dt_my_pin) > 0){
            echo "<div class='row grid'>";
            foreach($dt_my_pin as $dt){
                echo "
                    <div class='col-lg-4 col-md-6 col-sm-12 col-12 grid-item'>
                        <div class='pin-box mb-4'>
                            <div class='pin_info-holder'>
                                <span class='total-visit'>$dt->total Visit</span>  
                            </div>
                            <h3 class='dictionary_name_holder'>$dt->dictionary_name</h3>
                            <p class='list-pin-desc'>"; 
                                if($dt->pin_list){
                                    echo $dt->pin_list;
                                } else {
                                    echo '<span class="fst-italic text-secondary">- No Marker Found -</span>';
                                }
                            echo"</p>
                            <form method='POST' action='/ListController/view_catalog_detail/$dt->dictionary_name' class='d-inline'>
                                <button class='btn btn-primary px-2 py-1 me-2 see-detail-btn' href='/DetailController/'><i class='fa-solid fa-circle-info'></i> See Detail</button>
                            </form>
                            <a class='btn btn-primary-outline px-2 py-1 publish-to-global'><i class='fa-solid fa-globe'></i> Publish to Global</a>
                        </div>
                    </div>
                ";
            }
            echo "</div>";
        } else {
            echo "
                <div class='text-center my-3'>
                    <img src='http://127.0.0.1:8080/public/images/pin.png' class='img nodata-icon'>
                    <h5>You have no marker to show</h5>
                </div>
            ";
        }
    }
?>
<?php $this->load->view('list/publish_to_global'); ?>
<script>
    let map;

    function initMap() {
        //Map starter
        var markers = [
            <?php 
                foreach($dt_my_pin['data'] as $dt){
                    echo "{
                        coords: {lat: $dt->pin_lat, lng: $dt->pin_long},
                        icon: {
                            url: 'https://maps.google.com/mapfiles/ms/icons/$dt->pin_color.png',
                            scaledSize: new google.maps.Size(40, 40),
                        },
                        content: 
                        `<div>
                            <h6>$dt->pin_name</h6>
                            <span class='bg-dark rounded-pill px-2 py-1 text-white'>$dt->pin_category</span>
                            ";
                            if($dt->is_favorite == 1){
                                echo "<span class='btn bg-success px-2 py-1 text-white'><i class='fa-solid fa-bookmark'></i></span>";
                            }
                            echo "<br><br>";
                            if($dt->pin_desc){
                                echo "<p>$dt->pin_desc</p>";
                            } else {
                                echo "<p class='text-secondary fst-italic'>- No Description -</p>";
                            }
                            if($dt->pin_person){
                                echo "<p class='mt-2 mb-0 fw-bold'>Person In Touch</p>
                                <p>$dt->pin_person</p>";
                            }
                            echo"
                            <p class='mt-2 mb-0 fw-bold'>Created At</p>
                            <p class='date-target'>$dt->created_at</p>
                            <a class='btn btn-dark px-2 py-1 me-2 see-detail-btn' style='font-size:12px;' href='/DetailController/view/$dt->id'><i class='fa-solid fa-circle-info'></i> See Detail</a>
                            <a class='btn btn-light px-2 py-1 set-direction-btn' style='font-size:12px;' href='https://www.google.com/maps/dir/My+Location/$dt->pin_lat,$dt->pin_long'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>
                        </div>`
                    },";
                }
            ?>
        ];

        map = new google.maps.Map(document.getElementById("map-category"), {
            center: <?php 
                $search_pin_name = $this->session->userdata('search_pin_name_key');
                if($search_pin_name != null && $search_pin_name != ""){
                    echo "{ lat: "; echo $dt_my_pin[0]['data']->pin_lat; echo", lng: "; echo $dt_my_pin[0]['data']->pin_long; echo"}";
                } else {
                    echo "{ lat: -6.226838579766097, lng: 106.82157923228753}";
                }
            ?>,
            zoom: 12,
        });

        <?php 
            if($dt_my_pin){
                $total = count($dt_my_pin['data']);

                for($i = 0; $i < $total; $i++){
                    echo "addMarker(markers[".$i."]);";
                }
            }
        ?>

        function addMarker(props){
            var marker = new google.maps.Marker({
                position: props.coords,
                map: map,
                icon: props.icon
            });

            if(props.iconImage){
                marker.setIcon(props.iconImage);
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
    }
    window.initMap = initMap

    $( document ).ready(function() {
        $(document).on('click', '.delete-pin', function() {
            const idx = $(this).index('.delete-pin')
            Swal.fire({
                title: "Are you sure?",
                html: `Want to delete this pin?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            })
            .then((result) => {
                if (result.isConfirmed) {
                    const id = $('.id_holder').eq(idx).val()
                    $('#pin_id_delete').val(id)
                    $('#form-delete-pin').submit()
                }
            });
        })
    });
</script>