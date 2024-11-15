<?php if(!$is_mobile_device): ?>
    <table class="table table-bordered" id="tb_related_pin_track">
        <thead>
            <tr>
                <th scope="col">Pin Name</th>
                <th scope="col">Visit Attached</th>
                <th scope="col">Props</th>
                <th scope="col">Recover</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody id="tb_related_pin_track_body" style="font-size: var(--textSM);">
            <?php 
                if(count($dt_deleted_pin) > 0){
                    foreach($dt_deleted_pin as $dt){
                        echo "
                            <tr>
                                <td><h6>$dt->pin_name</h6></td>
                                <td class='text-center'><h6>$dt->visit_attached Visit</h6></td>
                                <td>
                                    <h6>Created At</h6>
                                    <p class='mb-0 date-target'>$dt->created_at</p>
                                    <h6>Updated At</h6>
                                    <p class='mb-0 date-target'>$dt->updated_at</p>
                                    <h6>Deleted At</h6>
                                    <p class='mb-0 date-target'>$dt->deleted_at</p>
                                </td>
                                <td>
                                    <form action='/TrashController/recover/$dt->id' method='POST'>
                                        <button class='btn btn-success w-100 recover-pin-btn' type='submit'><i class='fa-solid fa-rotate-left'></i> Recover</button>
                                    </form>
                                </td>
                                <td><button class='btn btn-danger w-100 delete-pin-btn'><i class='fa-solid fa-fire-flame-curved'></i> Destroy</button></td>
                            </tr>
                        ";
                    }
                } else {
                    echo "
                        <tr>
                            <td colspan='5' class='text-secondary fst-italic'>- No Item Found In Trash -</td>
                        </tr>
                    ";
                }
            ?>
        </tbody>
    </table>
<?php else: ?>
    <?php 
        if(count($dt_deleted_pin) > 0){
            foreach($dt_deleted_pin as $dt){
                echo "
                    <div class='container w-100 bordered mb-3 p-3'>
                        <h3>$dt->pin_name</h3>
                        <div class='d-flex justify-content-between'>
                            <h6 class='mt-2'>$dt->visit_attached Visit</h6>
                            <div class='d-flex justify-content-start'>
                                <form action='/TrashController/recover/$dt->id' method='POST'>
                                    <button class='btn btn-success recover-pin-btn me-2' type='submit'><i class='fa-solid fa-rotate-left'></i> Recover</button>
                                </form>
                                <button class='btn btn-danger delete-pin-btn'><i class='fa-solid fa-fire-flame-curved'></i> Destroy</button>
                            </div>
                        </div>
                        <hr>
                        <h6>Created At</h6>
                        <p class='mb-0 date-target'>$dt->created_at</p>
                        <h6>Updated At</h6>
                        <p class='mb-0 date-target'>$dt->updated_at</p>
                        <h6>Deleted At</h6>
                        <p class='mb-0 date-target'>$dt->deleted_at</p>
                    </div>
                ";
            }
        } else {
            echo "<p class='text-secondary fst-italic'>- No Item Found In Trash -</p>";
        }
    ?>
<?php endif; ?>