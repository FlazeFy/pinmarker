<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <?php 
            if(count($dt_my_activity['data']) > 0){
                foreach($dt_my_activity['data'] as $dt){
                    echo "
                        <div class='p-3 mb-2' style='border-radius: 15px; border: 2px solid black;'>
                            <h6 class='mb-0'>$dt->history_type | $dt->history_context</h6>
                            <p class='mb-0 date-target'>$dt->created_at</p>
                        </div>
                    ";
                }

                echo "<div class='d-inline-block'>
                    <h5>Page</h5>";

                $active = 0;
                if($this->session->userdata('page_activity')){
                    $active = $this->session->userdata('page_activity');
                }

                for($i = 0; $i < $dt_my_activity['total_page']; $i++){
                    $page = $i + 1;
                    echo "
                        <form method='POST' class='d-inline' action='/HistoryController/navigate/$i'>
                            <button class='btn btn-page"; 
                            if($active == $i){echo " active";}
                            echo" me-1' type='submit'>$page</button>
                        </form>
                    ";
                }

                echo "</div>";
            } else {
                echo "
                    <div class='text-center my-3'>
                        <h5 class='text-secondary'>- You have no activity to show -</h5>
                    </div>
                ";
            }
        ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        
    </div>
</div>


<script>
    const date_holder = document.querySelectorAll('.date-target');

    date_holder.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "calendar");
    });
</script>