<form action="/DetailVisitController/delete_visit/<?= $id ?>" method="POST" class="d-inline" id="delete-visit-form">
    <a class='btn btn-danger mb-4 py-3 px-4' id="delete-visit-btn"><i class='fa-solid fa-trash'></i>
    <?php
        if(!$is_mobile_device){
            echo " Delete";
        }
    ?>
    </a>
</form>
<script>
    $(document).on('click', '#delete-visit-btn', function(){
        Swal.fire({
            title: "Are you sure?",
            html: `Want to delete this visit?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        })
        .then((result) => {
            if (result.isConfirmed) {
                $('#delete-visit-form').submit()
            }
        });
    })
</script>