<div class="card mb-4">
    <h2 class="card-title">Marker Detail</h2>
    <input hidden id="pin_name_old" value="<?= $dt_detail_pin->pin_name ?>" type="text"/>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <input name="pin_name" id="pin_name" value="<?= $dt_detail_pin->pin_name ?>" type="text" class="form-control form-validated" maxlength="75" required/>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <label>Pin Category</label>
            <select name="pin_category" class="form-select" id="pin_category">
                <?php 
                    foreach($dt_dct_pin_category as $dt){
                        echo "<option value='$dt->dictionary_name'>$dt->dictionary_name</option>";
                    }
                ?>
            </select>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <input name="pin_person" id="pin_person" maxlength="75" value="<?= $dt_detail_pin->pin_person ?>" type="text" class="form-control form-validated"/>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="row">
                <div class="col-6">
                    <input name="pin_lat" id="pin_lat" type="text" maxlength="144" value="<?= $dt_detail_pin->pin_lat ?>" class="form-control form-validated" onblur="check_nearest_pin_edit()" required/>
                </div>
                <div class="col-6">
                    <input name="pin_long" id="pin_long" type="text" maxlength="144" value="<?= $dt_detail_pin->pin_long ?>" class="form-control form-validated" onblur="check_nearest_pin_edit()" required/>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <input name="pin_village" id="pin_village" maxlength="75" value="<?= $dt_detail_pin->pin_village ?>" class="form-control form-validated"/>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <input name="pin_suburb" id="pin_suburb" maxlength="75" value="<?= $dt_detail_pin->pin_suburb ?>" class="form-control form-validated"/>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <input name="pin_city" id="pin_city" maxlength="75" value="<?= $dt_detail_pin->pin_city ?>" class="form-control form-validated"/>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <input name="pin_country" id="pin_country" maxlength="75" value="<?= $dt_detail_pin->pin_country ?>" class="form-control form-validated"/>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <textarea name="pin_desc" id="pin_desc" maxlength="500" rows="5" class="form-control form-validated"><?= $dt_detail_pin->pin_desc ?></textarea>
            <input name="pin_email" id="pin_email" maxlength="255" value="<?= $dt_detail_pin->pin_email ?>" type="email" class="form-control form-validated"/>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <textarea name="pin_address" id="pin_address" maxlength="500" rows="5" class="form-control form-validated"><?= $dt_detail_pin->pin_address ?></textarea>
            <input name="pin_call" id="pin_call" value="<?= $dt_detail_pin->pin_call ?>" maxlength="16" type="phone" class="form-control form-validated"/>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="is_favorite" <?php if ($dt_detail_pin->is_favorite) echo "checked"; ?>>
                <label class="form-check-label" for="is_favorite">Add To My Favorite</label>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-6 col-md-6 col-sm-12">
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <button class="btn btn-success w-100 py-3" id="submit-btn" type="Submit"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
        </div>
    </div>
</div>

<script>
    const pinNameOld = $('#pin_name_old').val().toLowerCase()
    const id = '<?= $dt_detail_pin->id ?>';

    const set_disabled_submit = (val) => {
        $('#submit-btn').prop('disabled', val)
        $('#pin_name').prev('label').addClass('d-inline-block')
        $('#pin_name').prevAll('.pin-name-status').remove()
        $('#pin_name').before(!val ? `<span class="pin-name-status tag bg-success ms-2">Valid</span>` : `<span class="pin-name-status tag bg-danger ms-2">Duplicated!</span>`)
    }

    $(document).on('blur','#pin_name',function(){
        const pinName = $(this).val().trim().toLowerCase()
        pinNameOld !== pinName && pinName !== "" && check_pin_name_availability(pinName, (res) => { set_disabled_submit(res) })
    })

    const putUpdatePin = (id) => {
        Swal.showLoading()

        const data = {
            pin_name: $('#pin_name').val().trim(),
            pin_desc: $('#pin_desc').val().trim(),
            pin_lat: $('#pin_lat').val().trim(),
            pin_long: $('#pin_long').val().trim(),
            pin_village: $('#pin_village').val().trim(),
            pin_suburb: $('#pin_suburb').val().trim(),
            pin_city: $('#pin_city').val().trim(),
            pin_country: $('#pin_country').val().trim(),
            pin_category: $('#pin_category').val(),
            pin_person: $('#pin_person').val().trim(),
            pin_call: $('#pin_call').val().trim(),
            pin_email: $('#pin_email').val().trim(),
            pin_address: $('#pin_address').val().trim(),
            is_favorite: $('#is_favorite').is(':checked') ? 1 : 0,
        }

        $.ajax({
            url: `/api/v1/pin/update/${id}`,
            method: 'POST',
            data: data,
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
                    window.location.href = `/DetailController/view/${id}`
                })
            },
            error: (response) => {
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
        // Submit marker
        $(document).on('click', '#submit-btn', function (e) {
            e.preventDefault()
            putUpdatePin(id)
        })

        // Submit marker with direction
        $(document).on('click', '#submit-visit-wdir-btn', function () {
            $('#is_with_dir').val('true')
            putUpdatePin(id)
        })
    })
</script>