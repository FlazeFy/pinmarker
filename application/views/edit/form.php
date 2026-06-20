<div class="card mb-4">
    <h2 class="card-title">Marker Detail</h2>
    <input hidden id="is_with_dir" name="is_with_dir" value="false">
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
</script>