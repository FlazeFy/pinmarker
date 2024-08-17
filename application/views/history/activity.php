<?php 
    if(count($dt_my_activity['data']) > 0){
        echo "<div class='row'>";
        foreach($dt_my_activity['data'] as $dt){
            echo "
                <div class='col-lg-4 col-md-4 col-sm-6 col-12 mb-2'>
                    <div class='p-3' style='border-radius: 15px; border: 2px solid black;'>
                        <h6 class='mb-0'>$dt->history_type | $dt->history_context</h6>
                        <p class='mb-0 date-target'>$dt->created_at</p>
                    </div>
                </div>
            ";
        }
        echo "</div>";

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


<script>
    const date_holder = document.querySelectorAll('.date-target');

    date_holder.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "calendar");
    });
</script>