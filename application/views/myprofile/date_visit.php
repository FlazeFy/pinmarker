<h4><?= date('d M Y') ?></h4>
<?php 
    if(count($dt_visit_activity_by_date) > 0){
        foreach($dt_visit_activity_by_date as $dt){
            echo "
                <div class='mb-2'>
                    <h6 class='mb-1'>- Visit on $dt->pin_name by $dt->visit_by at "; echo date("Y-m-d H:i",strtotime($dt->created_at)); echo "</h6>
                    <h6 class='mb-0' style='font-size:14px;'>Notes : </h6>
                    <p style='font-size:14px;'>$dt->visit_desc</p>
                </div>
            ";
        }
    } else {
        echo "
            <h6 class='mb-1 text-center mt-4 text-secondary'>- No visit for this date -</h6>
        ";
    }
    
?>