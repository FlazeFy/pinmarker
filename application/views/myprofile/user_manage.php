<table class="table table-bordered my-3">
    <thead>
        <tr>
            <th>Fullname</th>
            <th>Username</th>
            <th>Email</th>
            <th>Telegram ID</th>
            <th>Props</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($dt_all_user as $dt){
                echo "
                    <tr>
                        <td>$dt->fullname</td>
                        <td>$dt->username</td>
                        <td>$dt->email</td>
                        <td>$dt->telegram_user_id</td>
                        <td>
                            <p class='mt-2 mb-0 fw-bold'>Created At</p>
                            <span class='date-target'>$dt->created_at</span>
                            <p class='mt-2 mb-0 fw-bold'>Updated At</p>
                            <span class='date-target'>$dt->updated_at</span>
                            <p class='mt-2 mb-0 fw-bold'>Last Login</p>
                            <span class='date-target'>$dt->last_login</span>
                        </td>
                        <td>
                            <button class='btn btn-dark w-100 rounded-pill mb-2'><i class='fa-solid fa-pen-to-square'></i> Update</button>
                            <button class='btn btn-dark w-100 rounded-pill mb-2'><i class='fa-solid fa-fire-flame-curved'></i> Destroy</button>
                            <button class='btn btn-dark w-100 rounded-pill mb-2'><i class='fa-brands fa-telegram'></i> Announce</button>
                        </td>
                    </tr>
                ";
            }
        ?>
    </tbody>
</table>