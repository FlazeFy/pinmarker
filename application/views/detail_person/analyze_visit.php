<p class='my-2 fw-bold'>Found On Visit</p>
<?php 
    if(count($dt_visit_by_person['data']) > 0){
        foreach($dt_visit_by_person['data'] as $dt){
            $desc = 'Visit';
            if($dt->visit_desc != null){
                $desc = $dt->visit_desc;
            }

            if($dt->pin_name){
                $desc = "$desc at $dt->pin_name";
            } 

            $visit_with_element = highlight_item($clean_name, $dt->visit_with);
            
            echo "
                <div class='p-3 mb-2' style='border: 2px solid black; border-radius: 15px;'>
                    <span>$desc with $visit_with_element</span>
                    <p class='mt-2 mb-0 fw-bold'>Visit At</p>
                    <p class='date-target mb-0'>$dt->created_at</p>
                </div>
            ";
        }

        $active = 0;
        if($this->session->userdata('page_visit')){
            $active = $this->session->userdata('page_visit');
        }

        for($i = 0; $i < $dt_visit_by_person['total_page']; $i++){
            $page = $i + 1;
            echo "
                <form method='POST' class='d-inline' action='/DetailPersonController/navigate/$raw_name/$i'>
                    <button class='btn btn-page"; 
                    if($active == $i){echo " active";}
                    echo" me-1' type='submit'>$page</button>
                </form>
            ";
        }
    } else {
        echo "
            <div class='text-center my-3'>
                <img src='http://127.0.0.1:8080/public/images/pin.png' class='img nodata-icon'>
                <h5>No visit found for this person</h5>
            </div>
        ";
    }
?>