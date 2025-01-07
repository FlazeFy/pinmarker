<?php 
    if(count($dt_review_history) > 0){
        echo "
        <div class='collapse' id='collapseReview'>
            <h5 class='fw-bold mb-0'>Review History</h5>
                <ol class='mb-0'>
            ";
            foreach ($dt_review_history as $dt) {
                echo "<li>Give <i class='fa-solid fa-star'></i> <b> $dt->review_rate</b> for <a class='fw-bold' href='/DetailController/view/$dt->pin_id'>$dt->pin_name</a> at <span class='date-target'>$dt->created_at</span></li>";
            }
            echo "</ol>
        </div>";
    }
?>