<p class='my-2 fw-bold'>Visit Time Analyze</p>
<?php 
    if(count($dt_visit_pertime_hour) > 0){
        echo "<div class='row'>";
        foreach($dt_visit_pertime_hour as $dt){
            echo "
                <div class='col-lg-6 col-md-12 col-sm-12 col-12'>
                    <div class='pin-box solid'>
                        <div style='margin-bottom: var(--spaceXMD);'>
                            <span class='time-context me-1'>$dt->context:00 - "; echo $dt->context+1; echo ":00</span>
                            <span class='total-visit'>$dt->total Visit</span>
                        </div>
                        <p class='mt-2 mb-0 fw-bold'>Visit At</p>
                        <p class='mb-0 mt-1'>$dt->visit_list</p>
                    </div>
                </div>
            ";
        }
        echo "</div>";
    } else {
        echo "
            <div class='text-center my-3'>
                <img src='http://127.0.0.1:8080/public/images/pin.png' class='img nodata-icon'>
                <h5>No visit history found for this person</h5>
            </div>
        ";
    }
?>