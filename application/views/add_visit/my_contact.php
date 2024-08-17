<div class="modal fade" id="myContactModel" tabindex="-1" aria-labelledby="addGalleriesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGalleriesLabel">Import Marker</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php 
                    foreach($dt_my_contact as $dt){
                        echo "
                            <div class='p-3 mb-2' style='border-radius: 15px; border: 2px solid black;'>
                                <div class='d-flex justify-content-start'>
                                    <input class='form-check-input' type='checkbox'>
                                    <div>
                                        <h6 class='mb-0'>$dt->pin_person</h6>
                                        <p class='mb-0'>Found in marker : $dt->pin_list</p>
                                    </div>
                                </div>
                            </div>
                        ";
                    }
                ?>
                <a class="btn btn-dark w-100 rounded-pill py-2 px-3"><i class="fa-solid fa-copy"></i> Add Selected Person</a>
            </div>
        </div>
    </div>
</div>