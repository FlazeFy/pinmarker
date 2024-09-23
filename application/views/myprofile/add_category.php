<div class="d-inline-block mt-2">
    <button class="btn btn-success rounded-pill w-100 py-2 px-3" data-bs-target="#addDictionary" data-bs-toggle="modal"><i class="fa-solid fa-plus"></i> Add Dictionary</button>
</div>

<div class="modal fade" id="addDictionary" tabindex="-1" aria-labelledby="addGalleriesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGalleriesLabel">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id='close-add-category-modal-btn' aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method='POST' action='/MyProfileController/add_category'>
                    <label>Dictionary Type</label>
                    <select name='dictionary_type' class='form-select' id='dictionary_type'>
                        <option value='visit_by'>Visit By</option>
                        <option value='pin_category'>Pin Category</option>
                    </select>
                    <input name="dictionary_name" id="dictionary_name" type="text" class="form-control form-validated" maxlength="36" required/>
                    <div id="form-dictionary-color"></div>
                    <button class='btn btn-success rounded-pill w-100' style='width:180px;' type='submit' id='submit-category-btn'><i class='fa-solid fa-floppy-disk'></i> Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('change','#dictionary_type',function(){
        if($(this).val() == "pin_category"){
            $('#form-dictionary-color').html(`
                <label>Dictionary Color</label>
                <select name='dictionary_color' class='form-select' id='dictionary_color'>
                    <option value='red'>Red</option>
                    <option value='blue'>Blue</option>
                    <option value='yellow'>Yellow</option>
                    <option value='orange'>Orange</option>
                    <option value='purple'>Purple</option>
                    <option value='green'>Green</option>
                </select>
            `)
        } else {
            $('#form-dictionary-color').empty()
        }
    })
</script>