<table class="table table-bordered my-3">
    <thead>
        <tr>
            <th>Type</th>
            <th>Name</th>
            <th>Color</th>
            <th>Props</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($dt_all_dct as $dt){
                echo "
                    <tr>
                        <td>$dt->dictionary_type</td>
                        <td>$dt->dictionary_name</td>
                        <td>$dt->dictionary_color</td>
                        <td>
                            <p class='mt-2 mb-0 fw-bold'>Created By</p>";
                            if($dt->created_by){
                                echo "<span class='date-target'><button class='btn-account-attach'>@$dt->created_by</button></span>";
                            } else {
                                echo "<span class='date-target'>-</span>";
                            }
                            echo"
                        </td>
                        <td>
                            <button class='btn btn-dark w-100 rounded-pill mb-2'><i class='fa-solid fa-pen-to-square'></i> Update</button>
                            <button class='btn btn-dark w-100 rounded-pill mb-2'><i class='fa-solid fa-fire-flame-curved'></i> Destroy</button>
                        </td>
                    </tr>
                ";
            }
        ?>
    </tbody>
</table>