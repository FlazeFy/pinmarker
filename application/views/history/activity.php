<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <?php 
            foreach($dt_my_activity as $dt){
                echo "
                    <div class='p-3 mb-2' style='border-radius: 15px; border: 2px solid black;'>
                        <h6 class='mb-0'>$dt->history_type | $dt->history_context</h6>
                        <p class='mb-0'>"; echo date("Y-m-d H:i",strtotime($dt->created_at)); echo"</p>
                    </div>
                ";
            }
        ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        
    </div>
</div>