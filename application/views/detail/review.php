<?php 
    if(count($dt_review_history) > 0){
        $show_page = true;
        foreach($dt_review_history as $dt){
            echo "<li>$dt->review_person give <i class='fa-solid fa-star'></i> <b> $dt->review_rate</b> for this location review at <span class='date-target'>$dt->created_at</span></li>";
        }
    } else {
        echo "
            <div class='text-center text-secondary'>
                <img class='img img-fluid m-1' style='width:200px;' src='http://127.0.0.1:8080/public/images/empty_item.png'>
                <h6>No Review found on this Pin</h6>
            </div>
        ";
    }
?>