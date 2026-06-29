<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="card-title">Visit Detail</h2>
        <?php $this->load->view("edit_visit/delete"); ?>
    </div>
    <input hidden id="type_add" name="type_add" value="visit">
    <div id="add_pin_form"></div>
    <div id="add-form-holder">
        <div class="row">
            <div class="col-12">
                <label>Marker</label>
                <div id="pin_name"></div>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <label>Visit By</label>
                <select name="visit_by" class="form-select" id="visit_by">
                    <?php 
                        foreach($dt_dct_visit_by as $dt){
                            echo "<option value='$dt->dictionary_name'>$dt->dictionary_name</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-lg-4 col-md-3 col-sm-6">
                <input name="visit_date" id="visit_date" type="date" class="form-control form-validated" required/>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
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
    <button class="btn btn-success w-100 py-3 mt-3" type="Submit" id='submit-btn'><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
</div>

<script>
   
</script>