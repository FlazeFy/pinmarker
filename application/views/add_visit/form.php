<form action="/addvisitcontroller/add_visit" method="POST">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <label>Pin Name</label>
            <select name="pin_id" class="form-select" id="pin_id" onchange="">
                <?php 
                    foreach($dt_all_my_pin_name as $dt){
                        echo "<option value='$dt->id'>$dt->pin_name</option>";
                    }
                ?>
            </select>
            <a class="msg-error-input"></a>

            <label>Description</label>
            <textarea name="visit_desc" id="visit_desc" rows="5" class="form-control"></textarea>
            <a class="msg-error-input"></a>
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
            <a class="msg-error-input"></a>

            <label>Visit With</label>
            <textarea name="visit_with" id="visit_with" rows="5" class="form-control"></textarea>
            <a class="msg-error-input"></a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <label>Visit At Date</label>
            <input name="visit_date" id="visit_date" type="date" class="form-control"/>
            <a class="msg-error-input"></a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <label>Visit At Hour</label>
            <input name="visit_hour" id="visit_hour" type="time" class="form-control"/>
            <a class="msg-error-input"></a>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <a class="btn btn-white rounded-pill w-100 py-3" style="border: 2.5px solid black;"><i class="fa-solid fa-location-arrow"></i> Save Visit & Set Direction</a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <button class="btn btn-dark rounded-pill w-100 py-3" type="Submit"><i class="fa-solid fa-floppy-disk"></i> Save Visit</button>
        </div>
    </div>
</form>