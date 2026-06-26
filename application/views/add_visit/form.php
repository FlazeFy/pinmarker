<div class="card mb-4">
    <h2 class="card-title">Visit Detail</h2>
    <input hidden id="type_add" name="type_add" value="visit">
    <div id="add_pin_form"></div>
    <div id="add-form-holder">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <?php $this->load->view("add_visit/marker_search"); ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label>Visit By</label>
                <select name="visit_by" class="form-select" id="visit_by">
                    <?php 
                        foreach($dt_dct_visit_by as $dt){
                            echo "<option value='$dt->dictionary_name'>$dt->dictionary_name</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                <input name="visit_date" id="visit_date" type="date" class="form-control form-validated" required/>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                <input name="visit_hour" id="visit_hour" type="time" class="form-control form-validated" required/>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <textarea name="visit_with" id="visit_with" rows="5" class="form-control form-validated visit-with" maxlength='500'></textarea>
                <div class="d-flex justify-content-start">
                    <?php $this->load->view('add_visit/visit_companion'); ?>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <textarea name="visit_desc" id="visit_desc" rows="5" class="form-control form-validated" maxlength='255'></textarea>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6 col-sm-12" id="save_visit_n_go">
            <a class="btn btn-outline-primary w-100 py-3 mb-2" id='submit-wdir-btn'><i class="fa-solid fa-location-arrow"></i> Save Visit & Set Direction</a>
        </div>
        <div class="col-md-6 col-sm-12" id='save-visit-btn-holder'>
            <button class="btn btn-success w-100 py-3" type="Submit" id='submit-btn'><i class="fa-solid fa-floppy-disk"></i> Save Visit</button>
        </div>
    </div>
</div>

<script>
    let selected_color = ''

    $(document).ready(function() {
        const btn_submit_el = `<button class="btn btn-success w-100 py-3" type="Submit" id='submit-visit-btn'><i class="fa-solid fa-floppy-disk"></i> Save Visit</button>`
        let count_multi_visit = 1

        $(document).on('click', '.add-form-btn', function() { 
            count_multi_visit++

            $('#type_add').val('multi')
            let target = $('#add-form-holder .row').last().clone()

            target.find('.diff-hr').remove()
            target.find('#add-new-pin-btn').remove()
            target.find('#add-custom-btn').remove()

            target.prepend(`
                <span class='diff-hr'>
                    <hr>
                    <div class='d-flex justify-content-between'>
                        <h4 class="mb-2">Visit Form</h4>  
                        <a class='btn btn-light py-2 px-4 remove-multi-form-idx-btn'><i class="fa-solid fa-trash"></i> Remove Form</a>
                    </div>
                </span>
            `)
            $('#add-form-holder').append(target)

            $('input, textarea, select').each(function() {
                let name = $(this).attr('name')

                if (name && name !== 'type_add' && name !== 'coordinate_dir' && !name.endsWith('[]')) {
                    $(this).attr('name', name + '[]')
                }
            });

            $('#save-visit-btn-holder').html(`
                <button class="btn btn-success w-100 py-3" type="Submit" id='submit-visit-btn'><i class="fa-solid fa-floppy-disk"></i> Save ${count_multi_visit} Visit</button>
            `)
            formValidation()
        })

        $(document).on('click', '.remove-multi-form-idx-btn', function() { 
            const idx = $(this).index('.remove-multi-form-idx-btn')

            $('#add-form-holder .row').eq(idx+1).remove()

            let count_visit = $('#add-form-holder .row').length
            if(count_visit == 1){
                count_visit = ''
            }
            $('#save-visit-btn-holder').html(`
                <button class="btn btn-success w-100 py-3" type="Submit" id='submit-btn'><i class="fa-solid fa-floppy-disk"></i> Save ${count_visit}Visit</button>
            `)
        })
    })

    function resetForm(){
        $('#type_add').val('visit')
        $('#add_pin_form').empty()
        $('#pin_name_init_holder').html(`
            <label>Pin Name</label>
            <select name="pin_id" class="form-select" id="pin_id" onchange="">
                <?php 
                    foreach($dt_all_my_pin_name as $dt){
                        echo "<option value='$dt->id/$dt->pin_name'>$dt->pin_name</option>";
                    }
                ?>
            </select>
            
            <div class="d-flex justify-content-start mb-3">
                <a class="btn btn-success ms-2 add-form-btn"><i class="fa-solid fa-plus"></i> Add Multiple Visit</a>
            </div>
        `)
        $('#save_visit_n_go').html(`
            <a class="btn btn-success w-100 py-3" style="border: 2.5px solid black;" id='submit-visit-wdir-btn'><i class="fa-solid fa-location-arrow"></i> Save Visit & Set Direction</a>
        `)
    } 

    const postCreateVisit = (isOpenMap) => {
        Swal.showLoading()

        const pin_id = $('#pin_name').data('id')
        const pinLat = $('#pin_name').data('lat')
        const pinLong = $('#pin_name').data('lng')
        const visit_date = $('#visit_date').val().trim()
        const visit_hour = $('#visit_hour').val().trim()
        const type_visit = isFutureDateTime(`${visit_date} ${visit_hour}`) ? 'plan' : 'history' 

        const data = {
            pin_id,
            visit_desc: $('#visit_desc').val().trim(),
            visit_by: $('#visit_by').val().trim(),
            visit_with: $('#visit_with').val().trim(),
            visit_date,
            visit_hour,
            type_visit 
        }

        $.ajax({
            url: '/api/v1/visit/create',
            method: 'POST',
            data,
            headers: {
                'Authorization': `Bearer ${tokenKey}`
            },
            success: (response) => {
                Swal.hideLoading()
                const data = response.data

                const id = data.id
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success'
                }).then(() => {
                    if (isOpenMap) window.open(`https://www.google.com/maps/dir/?api=1&destination=${pinLat},${pinLong}`, '_blank')
                    window.location.href = `/EditVisitController/${id}`
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
                    })
                } else {
                    unknownErrorSwal()
                }
            }
        })
    }

    $(document).ready(function () {
        $(document).on('click', '#submit-btn', function (e) {
            postCreateVisit(false)
        })
        $(document).on('click', '#submit-wdir-btn', function () {
            postCreateVisit(true)
        })
    })
</script>