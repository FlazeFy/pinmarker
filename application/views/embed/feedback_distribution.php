<table class='table table-bordered my-3'>
    <thead class='text-center'>
        <tr>
            <th scope="col">Rate</th>
            <th scope="col">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($dt_feedback_distribution as $idx => $dt) {
                echo "
                    <tr>
                        <td>$dt->context</td>
                        <td>$dt->total</td>
                    </tr>
                ";
            }
        ?>
    </tbody>
</table>