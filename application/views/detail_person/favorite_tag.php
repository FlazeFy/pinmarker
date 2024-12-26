<p class='my-2 fw-bold'>Favorite Tag (Global List)</p>
<?php 
    if(count($dt_visit_location_favorite_tag_by_person) > 0){
        foreach($dt_visit_location_favorite_tag_by_person as $dt){
            echo "
                <div class='p-3 mb-2' style='border: 2px solid black; border-radius: 15px;'>
                    <b><span class='text-white bg-dark px-2 py-1 rounded-pill me-2' style='font-size:var(--textJumbo);'>$dt->total Times</span> #$dt->context</b> 
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