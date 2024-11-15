<table class="table table-bordered my-3">
    <thead>
        <tr>
            <th>Rate</th>
            <th>Body</th>
            <th>Props</th>
            <th style='width: 100px;'>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if(count($dt_all_feedback) > 0){
                foreach($dt_all_feedback as $dt){
                    echo "
                        <tr>
                            <td>$dt->feedback_rate</td>
                            <td class='body-holder'>$dt->feedback_body</td>
                            <td>
                                <p class='mt-2 mb-0 fw-bold'>Created At</p>
                                <span class='date-target'>$dt->created_at</span>
                            </td>
                            <td style='max-width:100px;'>
                                <button class='btn btn-danger w-100 mb-2 destroy-feedback-btn'><i class='fa-solid fa-fire-flame-curved'></i></button>

                                <form class='d-none delete-feedback-form' action='/MyProfileController/delete_feedback' method='POST'>
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
                            <p class='text-secondary text-center fst-italic'>- No Feedback Found -</p>
                        </td>
                    </tr>
                ";
            }
        ?>
    </tbody>
</table>
<script>
    $(document).on('click', '.destroy-feedback-btn', function() {  
        const idx = $(this).index('.destroy-btn')
        const context = $('.body-holder').eq(idx).text()

        Swal.fire({
            title: "Are you sure?",
            html: `Want to delete this feedback. With body ${context}?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        })
        .then((result) => {
            if (result.isConfirmed) {
                $('.delete-feedback-form').eq(idx).submit()
            }
        });
    })
</script>