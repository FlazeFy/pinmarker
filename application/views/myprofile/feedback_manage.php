<table class="table table-bordered my-3">
    <thead class="text-center">
        <tr>
            <th>Rate</th>
            <th>Body</th>
            <th>Props</th>
            <th style='width: 100px;'>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($dt_all_feedback as $dt){
                echo "
                    <tr>
                        <td>$dt->feedback_rate</td>
                        <td>$dt->feedback_body</td>
                        <td>
                            <p class='mt-2 mb-0 fw-bold'>Created At</p>
                            <span class='date-target'>$dt->created_at</span>
                        </td>
                        <td style='max-width:100px;'>
                            <button class='btn btn-dark w-100 rounded-pill mb-2'><i class='fa-solid fa-fire-flame-curved'></i></button>
                        </td>
                    </tr>
                ";
            }
        ?>
    </tbody>
</table>