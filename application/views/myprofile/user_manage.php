<table class="table table-bordered my-3">
    <thead class="text-center">
        <tr>
            <th>Fullname</th>
            <th>Username</th>
            <th>Email</th>
            <th>Telegram ID</th>
            <th>Props</th>
            <th style='width: 100px;'>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if(count($dt_all_user) > 0){
                foreach($dt_all_user as $dt){
                    echo "
                        <tr>
                            <td>$dt->fullname</td>
                            <td class='username-holder'>$dt->username</td>
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
                            <td style='max-width:100px;'>
                                <button class='btn btn-dark w-100 rounded-pill mb-2'><i class='fa-solid fa-pen-to-square'></i></button>
                                <button class='btn btn-dark w-100 rounded-pill mb-2 destroy-btn'><i class='fa-solid fa-fire-flame-curved'></i></button>
                                <button class='btn btn-dark w-100 rounded-pill mb-2'><i class='fa-brands fa-telegram'></i></button>

                                <form class='d-none delete-user-form' action='/MyProfileController/delete_user' method='POST'>
                                    <input name='id' value='$dt->id'>
                                </form>
                            </td>
                        </tr>
                    ";
                }
            } else {
                echo "
                    <tr>
                        <td colspan='5'>
                            <p class='text-secondary text-center fst-italic'>- No User Found -</p>
                        </td>
                    </tr>
                ";
            }
        ?>
    </tbody>
</table>
<script>
    $(document).on('click', '.destroy-btn', function() {  
        const idx = $(this).index('.destroy-btn')
        const context = $('.username-holder').eq(idx).text()

        Swal.fire({
            title: "Are you sure?",
            html: `Want to delete <button class='btn-account-attach'>@${context}</button> account?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        })
        .then((result) => {
            if (result.isConfirmed) {
                $('.delete-user-form').eq(idx).submit()
            }
        });
    })
</script>