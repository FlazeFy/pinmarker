<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <label>Pin Name</label>
        <input name="pin_name" id="pin_name" type="text" class="form-control" required/>
        <a class="msg-error-input"></a>
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
        <a class="msg-error-input"></a>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <label>Maps</label>
        <?php $this->load->view('add/maps_select'); ?>
        <a class="msg-error-input"></a>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <label>Latitude</label>
        <input name="pin_lat" id="pin_lat" type="text" class="form-control" required/>
        <a class="msg-error-input"></a>

        <label>Longitude</label>
        <input name="pin_long" id="pin_long" type="text" class="form-control" required/>
        <a class="msg-error-input"></a>

        <label>Description</label>
        <textarea name="pin_desc" id="pin_desc" rows="5" class="form-control"></textarea>
        <a class="msg-error-input"></a>

        <label>Address</label>
        <textarea name="pin_address" id="pin_address" rows="5" class="form-control"></textarea>
        <a class="msg-error-input"></a>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <label>Person In Contact</label>
        <input name="pin_person" id="pin_person" type="text" class="form-control"/>
        <a class="msg-error-input"></a>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <label>Phone Number</label>
        <input name="pin_call" id="pin_call" type="phone" class="form-control"/>
        <a class="msg-error-input"></a>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <label>Email</label>
        <input name="pin_email" id="pin_email" type="email" class="form-control"/>
        <a class="msg-error-input"></a>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="is_favorite">
            <label class="form-check-label" for="is_favorite">Add To My Favorite</label>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <a class="btn btn-white rounded-pill w-100 py-3" style="border: 2.5px solid black;"><i class="fa-solid fa-location-arrow"></i> Save Marker & Set Direction</a>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <button class="btn btn-dark rounded-pill w-100 py-3" type="Submit"><i class="fa-solid fa-floppy-disk"></i> Save Marker</button>
    </div>
</div>