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
                                <button class='btn btn-danger w-100 mb-2 destroy-btn delete-user-btn'><i class='fa-solid fa-fire-flame-curved'></i></button>";
                                if($dt->telegram_user_id && $dt->telegram_is_valid){
                                    echo "<button class='btn btn-dark w-100 mb-2 chat-btn' data-bs-toggle='modal' data-bs-target='#addChatModal'><i class='fa-brands fa-telegram'></i></button>";
                                }
                                echo "<form class='d-none delete-user-form' action='/MyProfileController/delete_user' method='POST'>
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

<div class="modal fade" id="addChatModal" tabindex="-1" aria-labelledby="addGalleriesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGalleriesLabel">Send Chat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id='close-chat-modal-btn' aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Send to : @<span id="username-holder-chat"></span></h6>
                <label>Chat</label>
                <form method="POST" action="/MyProfileController/send_chat">
                    <input id="username_chat" name="username" hidden/>
                    <textarea name="chat" id="chat" rows="5" class="form-control" maxlength="1000"></textarea>
                    
                    <button class="btn btn-dark w-100 py-2 px-3" id='submit-chat-btn'><i class="fa-solid fa-paper-plane"></i> Send Chat</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
    $(document).on('click', '.chat-btn', function() {  
        const idx = $(this).index('.chat-btn')
        const context = $('.username-holder').eq(idx).text()
        $('#username-holder-chat').text(context)
        $('#username_chat').val(context)
    })
</script>