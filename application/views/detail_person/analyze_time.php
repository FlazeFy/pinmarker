<p class='mt-2 mb-0 fw-bold'>Visit Time Analyze</p>
<?php 
    if(count($dt_visit_pertime) > 0){
        echo "<div class='row'>";
        foreach($dt_visit_pertime as $dt){
            echo "
                <div class='col-lg-6 col-md-12 col-sm-12 col-12'>
                    <div class='px-2 py-3 mb-3' style='border: 2px solid black; border-radius: 15px;'>
                        <div class='d-flex justify-content-between'>
                            <span><span class='bg-dark rounded-pill px-3 py-1 text-white'>$dt->total Visit</span> At Hour <b>$dt->context:00</b> - "; echo "<b>".$dt->context+1; echo ":00</b>"; echo"</span>
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