<div class="card">
    <h3 class="card-title">Count Distance</h3>
    <label>Other Marker</label>
    <select class="form-select" id="pin_name_distance_count">
        <option value="">-</option>
        <?php 
            foreach($dt_all_my_pin_name as $dt){
                if($dt->id != $dt_detail_pin->id){
                    echo "<option value='$dt->pin_lat/$dt->pin_long'>$dt->pin_name</option>";
                }
            }
        ?>
    </select>
    <div class="d-flex justify-content-between align-items-center bg-secondary p-4 rounded">
        <label>Distance</label>
        <p class="fw-bold text-primary mb-0 text-lg" id="pin_to_pin_distance_count">0.0 Km</p>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('change', '#pin_name_distance_count', function() { 
            const coor_split = $(this).val().split('/')
            const lat_to = coor_split[0]
            const long_to = coor_split[1]
            const lat_from = '<?= $dt_detail_pin->pin_lat ?>'
            const long_from = '<?= $dt_detail_pin->pin_long ?>'

            let distance = (calculateDistance(`${lat_from},${long_from}`, `${lat_to},${long_to}`) / 1000).toFixed(2)
            $('#pin_to_pin_distance_count').text(`${distance} Km`)
        })
    })
</script>