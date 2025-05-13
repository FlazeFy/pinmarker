<p class='my-2 fw-bold'>Favorite Tag (Global List)</p>
<?php 
    if(count($dt_visit_location_favorite_tag_by_person) > 0){
        foreach($dt_visit_location_favorite_tag_by_person as $dt){
            echo "
                <div class='pin-box solid'>
                    <b><span class='text-white bg-primary px-2 py-1 rounded-pill me-1' style='font-size:var(--textXLG);'>$dt->total Times</span> #$dt->context</b> 
                </div>
            ";
        }
    } else {
        echo "
            <div class='text-center my-3'>
                <img src='http://127.0.0.1:8080/public/images/pin.png' class='img nodata-icon'>
                <h5>No attached tag found on location that this person has been visited with</h5>
            </div>
        ";
    }
?>