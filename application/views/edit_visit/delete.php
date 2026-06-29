<a class='btn btn-danger py-1 px-3' id="delete-visit-btn"><i class='fa-solid fa-trash'></i> Delete</a>
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
                const id = takeIdFromPath('EditVisitController')

                $.ajax({
                    url: `/api/v1/visit/delete/${id}`,
                    method: 'DELETE',
                    contentType: 'application/json',
                    headers: {
                        'Authorization': `Bearer ${tokenKey}`
                    },
                    success: (response) => {
                        Swal.hideLoading()

                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success'
                        }).then(() => {
                            window.location.href = '/HistoryController'
                        })
                    },
                    error: (response) => {
                        Swal.hideLoading()
                        if (response.status === 401) return failedAuth()

                        const message = response.responseJSON?.message ?? 'Something went wrong.'

                        if (response.status === 400) {
                            Swal.fire({
                                title: 'Failed!',
                                html: message,
                                icon: 'warning'
                            })
                        } else {
                            unknownErrorSwal()
                        }
                    }
                })
            }
        })
    })
</script>