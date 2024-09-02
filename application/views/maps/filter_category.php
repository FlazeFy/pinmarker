<form action="/MapsController/filter_pin_category" method="POST">
    <div class="form-floating">
        <select class="form-select text-white" style="width:300px;" aria-label="Default select example" name="pin_category" oninput="this.form.submit()">
            <option value="all">All Pin</option>
            <option value="favorite" <?php if($this->session->userdata('filter_pin_by_cat') == "favorite"){ echo "selected"; }?>>My Favorite</option>
            <?php 
                foreach($dt_dct_pin_category as $dt){    
                    echo "<option value='$dt->dictionary_name'"; if($dt->dictionary_name == $this->session->userdata('filter_pin_by_cat')){ echo "selected"; } echo">$dt->dictionary_name</option>";        
                }
            ?>
        </select>
        <label for="floatingSelect" class="text-white fw-normal">Filter By Category</label>
    </div>
</form>