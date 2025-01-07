<hr><p class='mt-2 mb-0 fw-bold'>Visit History</p>
<ol>
<?php 
    $show_page = false;
    if(count($dt_visit_history['data']) > 0){
        $show_page = true;
        foreach($dt_visit_history['data'] as $dt){
            echo "<li>$dt->visit_desc using "; echo strtolower($dt->visit_by);
                if($dt->visit_with != null){
                    echo " with $dt->visit_with";
                }    
            echo " at <span class='date-target'>$dt->created_at</span></li>";
        }
    } else {
        echo "
            <div class='text-center text-secondary'>
                <img class='img img-fluid m-1' style='width:200px;' src='http://127.0.0.1:8080/public/images/empty_item.png'>
                <h6>No History Visit found on this Pin</h6>
            </div>
        ";
    }
?>
</ol>
<?php 
    if($show_page){
        echo "<div class='d-inline-block'>
        <h6>Page History</h6>";

        $active = 0;
        if($this->session->userdata('page_detail_history')){
            $active = $this->session->userdata('page_detail_history');
        }

        for($i = 0; $i < $dt_visit_history['total_page']; $i++){
            $page = $i + 1;
            echo "
                <form method='POST' class='d-inline' action='/DetailController/navigate/$dt_detail_pin->id/$i'>
                    <button class='btn btn-page"; 
                    if($active == $i){echo " active";}
                    echo" me-1' type='submit'>$page</button>
                </form>
            ";
        }

        echo "</div>";
    }
?>
<hr>