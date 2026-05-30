<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="page-title">My Markers</h2>
        <p class="page-sub">Manage your saved travel locations and analytics.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="/AddController" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add New Marker
        </a>
        <a class="btn btn-outline" id="btn-print">
            <i class="fa-solid fa-print"></i> Print
        </a>
        <a href="/TrashController" class="btn btn-danger">
            <i class="fa-solid fa-trash"></i> Trash
        </a>
    </div>
</div>

<script>
    $('#btn-print').on('click', function() {
        Swal.fire({
            title: 'Choose Print Type',
            text: 'Select what you want to print.',
            icon: 'question',
            showCancelButton: false,
            showDenyButton: true,
            confirmButtonText: 'Print Document',
            denyButtonText: 'Print Dataset',
        }).then((result) => {
            if (result.isConfirmed) window.location.href = '/ListController/print_pin_doc'
            if (result.isDenied) window.location.href = '/ListController/print_dataset'
        })
    })
</script>