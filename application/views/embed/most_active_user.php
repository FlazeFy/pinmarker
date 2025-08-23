<table class='table table-bordered my-3'>
    <thead class='text-center'>
        <tr>
            <th scope="col">Rank</th>
            <th scope="col">Username</th>
            <th scope="col">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($dt_user_most_pin as $idx => $dt) {
                echo "
                    <tr>
                        <th>"; echo $idx + 1; echo "</th>
                        <td>@$dt->context</td>
                        <td>$dt->total</td>
                    </tr>
                ";
            }
        ?>
    </tbody>
</table>