<span style="width: 160px;" class="me-2">
    <label>Show Route For</label>
    <form action="TrackController/filter_date" method="POST">
        <input class="form-control d-inline-block my-0 mt-1 rounded-pill" 
            value="<?= $this->session->userdata('filter_date_track') ?>" 
            name="filter_date_track" onchange="this.form.submit()" style="height: 30px;" type="date">
    </form>
</span>


<script>
    let date_filter = <?php if($this->session->userdata('filter_date_track') != null){ echo $this->session->userdata('filter_date_track;'); } else {echo 'null;';} ?>
    if(date_filter != null){
        getRouteDay()
    }
    function getRouteDay() {
        $.ajax({
            url: `http://127.0.0.1:8000/api/v1/track/journey_day/<?= $this->session->userdata('filter_date_track')?>/<?= $this->session->userdata('user_id'); ?>`,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {

            }
        })
        .done(function (response) {
            let data = response.data
            updateMarkers(data)
            Swal.fire({
                title: "Track is Filtered!",
                text: "Showing route at <?= $this->session->userdata('filter_date_track')?>",
                icon: "success"
            });
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            Swal.fire({
                title: "Oops!",
                text: "No track found at <?= $this->session->userdata('filter_date_track')?>",
                icon: "error"
            });
        });
    }
</script>