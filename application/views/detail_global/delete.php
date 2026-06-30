<a class='btn btn-danger py-1 px-3' id="delete-list-btn"><i class='fa-solid fa-trash'></i> Delete</a>
<script>
    $(document).on('click', '#delete-list-btn', function(){
        Swal.fire({
            title: "Are you sure?",
            html: `Want to delete this list?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        })
        .then((result) => {
            if (result.isConfirmed) {
                const id = takeIdFromPath('GlobalListController')

                $.ajax({
                    url: `/api/v1/global_list/delete/${id}`,
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
                            window.location.href = '/GlobalListController'
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