<form action="/addcontroller/add_marker" method="POST">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <label>Pin Name</label>
            <input name="pin_name" id="pin_name" type="text" class="form-control" required/>
            <a class="msg-error-input"></a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <label>Pin Category</label>
            <select name="pin_category" class="form-select" id="pin_category" onchange="select_color_marker(this.value)">
                <?php 
                    foreach($dt_dct_pin_category as $dt){
                        echo "<option value='$dt->dictionary_name-$dt->dictionary_color'>$dt->dictionary_name</option>";
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
            <input name="pin_lat" id="pin_lat" type="text" class="form-control" onchange="select_map()" required/>
            <a class="msg-error-input"></a>

            <label>Longitude</label>
            <input name="pin_long" id="pin_long" type="text" class="form-control" onchange="select_map()" required/>
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
</form>

<script>
    const pin_lat_input = document.getElementById('pin_lat')
    const pin_long_input = document.getElementById('pin_long')

    function select_color_marker(val){
        const split_val = val.split("-")
        const color = split_val[1]

        selected_color = color
    }

    function select_map(){
        let fullfilled = false
        if(pin_lat_input.value != null && pin_long_input.value != null){
            const lat_val = cleanCoor(pin_lat_input.value)
            const long_val = cleanCoor(pin_long_input.value)
            
            pin_lat_input.value = lat_val
            pin_long_input.value = long_val
            
            if(lat_val != '' && lat_val != null && long_val != '' && long_val != null){
                fullfilled = true
            } 

            if(fullfilled){
                const coor = `(${lat_val}, ${long_val})`
                initMap()
                placeMarkerAndPanTo(coor, map)
            }
        }
    }

    function cleanCoor(val) {
        var res = val.replace(/[^\d-.]/g, '')
        return res
    }
</script>