<div class="row">
    <div class="col-8">
        <label>Pin Name</label>
        <select class="form-select" id="pin_name_distance_count" onchange="count_distance_pin(this.value)">
            <option value="">- select your pin -</option>
            <?php 
                foreach($dt_all_my_pin_name as $dt){
                    if($dt->id != $dt_detail_pin->id){
                        echo "<option value='$dt->pin_lat/$dt->pin_long'>$dt->pin_name</option>";
                    }
                }
            ?>
        </select>
        <a class="msg-error-input"></a>
    </div>
    <div class="col-4">
        <label>Distance</label>
        <input class="form-control" id="pin_to_pin_distance_count" disabled>
    </div>
</div>

<script>
    function calculateDistance(lat1, lon1, lat2, lon2, unit = 'km') {
        const theta = lon1 - lon2
        let distance = Math.sin(deg2rad(lat1)) * Math.sin(deg2rad(lat2)) + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.cos(deg2rad(theta))
        distance = Math.acos(distance)
        distance = rad2deg(distance)
        distance = distance * 60 * 1.1515

        if (unit === 'km') {
            distance = distance * 1.609344
        }

        distance = distance.toFixed(2)

        return distance
    }

    function deg2rad(deg) {
        return deg * (Math.PI / 180)
    }

    function rad2deg(rad) {
        return rad * (180 / Math.PI)
    }


    function count_distance_pin(coor){
        if(coor != ""){
            const coor_split = coor.split('/')
            const lat_to = coor_split[0]
            const long_to = coor_split[1]
            const lat_from = '<?= $dt_detail_pin->pin_lat ?>'
            const long_from = '<?= $dt_detail_pin->pin_long ?>'

            const dis = calculateDistance(lat_to, long_to, lat_from, long_from, 'km')
            document.getElementById('pin_to_pin_distance_count').value = dis+' Km'
        }
    }
</script>