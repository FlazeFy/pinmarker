<?php 
    if($dt->gallery_url){
        echo "<p class='mt-2 fw-bold'>Gallery</p>
        <div class='row mb-2'>";
        $split_url = explode(',',$dt->gallery_url);
        foreach($split_url as $idx => $url){
            echo "<div class='col-lg-4 col-md-6 col-sm-6'>
            <button class='gallery-btn' data-bs-toggle='modal' data-bs-target='#gallery_modal_$dt->id-$idx'>";
            if($dt->gallery_type == "image"){
                echo "<img class='rounded img img-fluid d-block mx-auto' src='$dt->gallery_url' alt='$dt->gallery_url'>";
            } else if($dt->gallery_type == "video"){
                echo "
                    <video controls class='rounded w-100 mx-auto mt-2' alt='$dt->gallery_url'>
                        <source src='$dt->gallery_url'>
                    </video>
                ";
            }
            echo"</button>
            <div class='modal fade' id='gallery_modal_$dt->id-$idx' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                <div class='modal-dialog modal-lg'>
                    <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='exampleModalLabel'>$dt->gallery_caption</h5>
                        <button type='button' class='btn-close close-gallery-caption-modal-btn' data-bs-dismiss='modal' aria-label='Close'></button>
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
            </div>";
        }
        echo '</div>';
    }
?>