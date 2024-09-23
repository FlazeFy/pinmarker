<h4 class="mt-3">My Gallery</h4>
<div class="accordion" id="accordionGallery">
    <?php 
        if(count($dt_my_gallery) > 0 ){
            $pin_name_before = "";
            $i = 0;

            foreach($dt_my_gallery as $dt){
                if($pin_name_before == "" || $pin_name_before != $dt->pin_name){
                    echo "
                    <button class='btn btn-collapse collapse-gallery-btn' title='Click to open / close pin gallery' data-bs-toggle='collapse' data-bs-target='#collapse-$dt->pin_id'>
                        <h6 class='mb-0'>- Found in $dt->pin_name <span class='bg-dark text-white px-2 py-1 rounded-pill'>$dt->pin_category</span></h6>
                    </button>
                    <div class='collapse "; if($i == 0){ echo "show"; } echo"' id='collapse-$dt->pin_id' data-bs-parent='#accordionGallery'>
                        <div class='row mb-3'>
                    ";
                    $pin_name_before = $dt->pin_name;
                }
                echo "
                    <div class='col-lg-4 col-md-6 col-sm-12 mb-3'>
                        <button class='gallery-btn open-gallery-btn' data-bs-toggle='modal' data-bs-target='#gallery_modal_$dt->id'>";

                        if($dt->gallery_type == "image"){
                            echo "<img class='rounded img img-fluid d-block mx-auto' src='$dt->gallery_url' alt='$dt->gallery_url'>";
                        } else if($dt->gallery_type == "video"){
                            echo "
                                <video controls class='rounded w-100 mx-auto mt-2' alt='$dt->gallery_url'>
                                    <source src='$dt->gallery_url'>
                                </video>
                            ";
                        }

                        echo"
                        <p class='m-0'>$dt->gallery_caption</p>
                        <p class='m-0' style='font-size:var(--textSM);'>Posted at "; echo date('Y-m-d H:i',strtotime($dt->created_at)); echo"</p>
                        </button>
                    </div>
                    <div class='modal fade' id='gallery_modal_$dt->id' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                        <div class='modal-dialog modal-lg'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='exampleModalLabel'>$dt->gallery_caption</h5>
                                    <button type='button' class='btn-close close-gallery-btn' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body'>";
                                    if($dt->gallery_type == "image"){
                                        echo "<img class='rounded img img-fluid d-block mx-auto' src='$dt->gallery_url' alt='$dt->gallery_url'>";
                                    } else if($dt->gallery_type == "video"){
                                        echo "
                                            <video controls class='rounded w-100 mx-auto mt-2' alt='$dt->gallery_url'>
                                                <source src='$dt->gallery_url'>
                                            </video>
                                        ";
                                    }
                                echo"
                                <p class='m-0 mt-3' style='font-size:var(--textMD);'>Posted at "; echo date('Y-m-d H:i',strtotime($dt->created_at)); echo"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                ";
                if($i < count($dt_my_gallery) - 1 && $pin_name_before != $dt_my_gallery[$i+1]->pin_name){
                    echo "
                        </div>
                    </div>
                    ";
                }
                $i++;
            }
            echo "</div></div>";
        } else {
            echo "
                <div class='text-center my-3'>
                    <img src='http://127.0.0.1:8080/public/images/gallery.png' class='img nodata-icon'>
                    <h5>You have no gallery to show</h5>
                </div>
            ";
        }
    ?>
</div>