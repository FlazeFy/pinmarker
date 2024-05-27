<div class="row mt-2">
    <?php
        if(count($dt_all_gallery_by_pin) > 0){
            foreach($dt_all_gallery_by_pin as $dt){
                echo "
                    <div class='col-lg-6 col-md-12 col-sm-12 mx-auto'>
                        <button class='gallery-btn' data-bs-toggle='modal' data-bs-target='#gallery_modal_$dt->id'>";
    
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
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
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
            }
        } else {
            echo "
                <div class='text-center text-secondary'>
                    <img class='img img-fluid m-1' style='width:200px;' src='http://127.0.0.1:8080/public/images/empty_item.png'>
                    <h6>No Gallery found on this Pin</h6>
                </div>
            ";
        }
        
    ?>
</div>