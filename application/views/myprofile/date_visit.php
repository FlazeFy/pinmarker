<h4><?= date('d M Y') ?></h4>
<?php 
    if(count($dt_visit_activity_by_date) > 0){
        echo "<div class='row'>";
            foreach($dt_visit_activity_by_date as $dt){
                echo "
                    <div class='col-lg-6 col-md-6 col-sm-12 col-12 mb-2'>
                        <div class='p-3' style='border-radius: 15px; border: 2px solid black;'>
                            <h6 class='mb-1'>- Visit on $dt->pin_name by $dt->visit_by at "; echo date("Y-m-d H:i",strtotime($dt->created_at)); echo "</h6>
                            <h6 class='mb-0' style='font-size:14px;'>Notes : </h6>
                            <p style='font-size:14px;' class='m-0'>$dt->visit_desc</p>
                        </div>
                    </div>
                ";
            }
        echo "</div>";
    } else {
        echo "
            <h6 class='mb-1 text-center mt-4 text-secondary'>- No visit for this date -</h6>
        ";
    }
    
?>