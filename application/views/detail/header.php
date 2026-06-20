<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div class="d-flex gap-3">
        <a class="btn btn-danger align-self-center" href="/ListController">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <div>
            <h1 class="page-title"><?= $dt_detail_pin->pin_name ?></h1>
            <p class="page-sub mb-0"><i class="fa-solid fa-location-dot"></i> <?= $dt_detail_pin->pin_address ?></p>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="/EditController/view/<?= $dt_detail_pin->id ?>" class="btn btn-primary">
            <i class="fa-solid fa-pen-to-square"></i> Edit Marker
        </a>
        <a class="btn btn-outline" id="btn-print">
            <i class="fa-solid fa-print"></i> Print
        </a>
        <form action="/DetailController/delete_pin/<?= $dt_detail_pin->id ?>" method="POST" id="delete-pin-form">
            <a class="btn btn-danger" id="delete-pin-btn">
                <i class="fa-solid fa-trash"></i> Delete
            </a>
        </form>
    </div>
</div>

<script>
    $( document ).ready(function() {
        $(document).on('click', '#delete-pin-btn', function() {
            Swal.fire({
                title: "Are you sure?",
                html: `Want to delete this pin?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $('#delete-pin-form').submit()
                }
            });
        })
    })

    $('#btn-print').on('click', function() {
        Swal.fire({
            title: 'Print Right Now?',
            text: 'Or maybe you want to modifed some data first',
            icon: 'question',
            showCancelButton: false,
            showDenyButton: true,
            confirmButtonText: 'Print Now!',
            denyButtonText: 'I want to modify',
        }).then((result) => {
            if (result.isConfirmed) window.location.href = '/DetailController/print_detail/<?= $dt_detail_pin->id ?>'
            if (result.isDenied) window.location.href = '/DetailController/...'
        })
    })
</script>