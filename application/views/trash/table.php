<a class="btn btn-dark mb-4 rounded-pill <?php if (!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> px-4 me-2" href="/ListController"><i class="fa-solid fa-arrow-left"></i> Back</a>
<table class="table table-bordered" id="tb_related_pin_track">
    <thead style="font-size: var(--textMD);" class="text-center">
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
                                    <button class='btn btn-dark w-100 rounded-pill' type='submit'><i class='fa-solid fa-rotate-left'></i> Recover</button>
                                </form>
                            </td>
                            <td><button class='btn btn-dark w-100 rounded-pill'><i class='fa-solid fa-fire-flame-curved'></i> Destroy</button></td>
                        </tr>
                    ";
                }
            } else {
                echo "
                    <tr>
                        <td colspan='5' class='text-center'>- No Item Found In Trash -</td>
                    </tr>
                ";
            }
        ?>
    </tbody>
</table>

<script>
    const date_holder = document.querySelectorAll('.date-target');

    date_holder.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "calendar");
    });
</script>