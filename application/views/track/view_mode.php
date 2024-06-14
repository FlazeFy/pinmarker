<span style="width: 270px;" class="me-2">
    <label>View Mode</label>
    <form action="trackcontroller/set_view_mode" method="POST">
        <select class="form-select d-inline-block my-0 mt-1 py-0 rounded-pill" onchange="this.form.submit()" aria-label="Default select example" name="view_mode" onchange="this.form.submit()" style="height: 30px;">
            <option value="current_location" <?php if($this->session->userdata('view_mode_track') == 'current_location'){echo "selected";} ?>>Current Location</option>
            <option value="track" <?php if($this->session->userdata('view_mode_track') == 'track'){echo "selected";} ?>>Track</option>
            <option value="related_pin" <?php if($this->session->userdata('view_mode_track') == 'related_pin'){echo "selected";} ?>>Related Pin</option>
        </select>
    </form>
</span>