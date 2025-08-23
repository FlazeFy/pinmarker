<table class='table table-bordered my-3'>
    <thead class='text-center'>
        <tr>
            <th scope="col">Rank</th>
            <th scope="col">Pin Category</th>
            <th scope="col">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($dt_most_visited_pin_category as $idx => $dt) {
                echo "
                    <tr>
                        <th>"; echo $idx + 1; echo "</th>
                        <td>$dt->context</td>
                        <td>$dt->total</td>
                    </tr>
                ";
            }
        ?>
    </tbody>
</table>