<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="card-title mb-0">List Detail</h2>
        <div class="d-flex gap-2">
            <a class='btn btn-primary py-1 px-3' id="manage-tag-btn" data-bs-toggle="modal" data-bs-target="#manage-tag-modal"><i class='fa-solid fa-hashtag'></i> Manage Tags</a>
            <?php $this->load->view("detail_global/delete"); ?>
        </div>
    </div>
    <div class="row position-relative" id="detail-row">
        <div class="col-md-6 col-sm-12 position-absolute d-flex flex-column" style="top: 0; bottom: 0; left: 0;">
            <input type="text" id="pin-search" class="form-control mb-2" placeholder="Search by name or address...">
            <div id="pin-list-holder" style="flex: 1; min-height: 0; overflow-y: auto;"></div>
            <div id="pin-list-message-holder"></div>
        </div>
        <div class="col-md-6 col-sm-12" style="margin-left: 50%;">
            <input name="list_name" id="list_name" class="form-control form-validated" maxlength='75'>
            <textarea name="list_desc" id="list_desc" rows="5" class="form-control form-validated" maxlength='255'></textarea>
            <p class="text-muted text-sm mb-0">Created at : <span id="created_at"></span></p>
            <span id="updated_at"></span>
            <button class="btn btn-success w-100 py-3 mt-3" type="Submit" id='submit-btn'><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
            <hr>
            <button class="btn btn-primary w-100 py-3 mt-3" type="Submit" id='add-marker-btn'><i class="fa-solid fa-plus"></i> Add Marker</button>
        </div>
    </div>
</div>

<script>
    let pinSearchDebounce = null

    $(document).on('input', '#pin-search', function () {
        clearTimeout(pinSearchDebounce)
        const val = $(this).val().trim().toLowerCase()
        const holder = `#pin-list-holder`
        const msgHolder = '#pin-list-message-holder'

        pinSearchDebounce = setTimeout(() => {
            let count = 0

            $(`${holder} .activity-item`).each(function () {
                const name = $(this).find('.pin_name').text().toLowerCase()
                const address = $(this).find('.pin-address').text().toLowerCase()
                const match = name.includes(val) || address.includes(val)

                $(this).toggle(match)
                if (match) count++
            })

            if (count === 0) { 
                $(holder).hide()
                generateNoData(msgHolder, 'No marker found')
            } else {
                $(holder).show()
                $(msgHolder).hide()
            }
        }, debouncerTime)
    })

    const putGlobalList = (id) => {
        Swal.showLoading()

        const data = {
            list_name: $('#list_name').val().trim(),
            list_desc: $('#list_desc').val().trim()
        }

        $.ajax({
            url: `/api/v1/global_list/edit/${id}`,
            method: 'POST',
            data,
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
                    fetchGlobalList(id)
                })
            },
            error: (response) => {
                if (response.status === 401) return failedAuth()
                Swal.hideLoading()

                const message = response.responseJSON?.message ?? 'Something went wrong.'

                if (response.status === 400) {
                    Swal.fire({
                        title: 'Failed!',
                        html: message,
                        icon: 'warning'
                    }).then(() => {
                        fetchGlobalList(id)
                    })
                } else {
                    unknownErrorSwal()
                }
            }
        })
    }

    $(document).ready(function () {
        $(document).on('click', '#submit-btn', function (e) {
            const id = takeIdFromPath('GlobalListController')
            putGlobalList(id)
        })
    })
</script>