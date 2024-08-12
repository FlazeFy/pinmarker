<?php
    foreach($dt_global_list as $dt){
        echo "
            <div class='col-lg-4 col-md-6 col-sm-12'>
                <div class='pin-box mb-4'>
                    <div class='pin-box-label "; if(!$is_mobile_device){ echo "position-absolute"; } else { echo "float-end mb-1"; } echo "'"; if(!$is_mobile_device){ echo "style='right:-15px; top:-15px;'"; } echo ">$dt->total Marker</div>
                    <h3>$dt->list_name</h3>
                    <hr>
                    <h5>List Marker</h5>
                    <p class='list-pin-desc'>"; 
                        if($dt->pin_list){
                            echo $dt->pin_list;
                        } else {
                            echo '<span class="fst-italic text-secondary">- No Marker Found -</span>';
                        }
                    echo"</p>
                    <p class='mt-2 mb-0 fw-bold'>Created At</p>
                    <p><span class='date-target'>$dt->created_at</span> by <button class='btn-account-attach'>@$dt->created_by</button></p>
                    <a class='btn btn-dark rounded-pill px-2 py-1 me-2' href='/DetailGlobalController/view/$dt->id'><i class='fa-solid fa-circle-info'></i> See Detail</a>
                    <a class='btn btn-dark rounded-pill px-2 py-1'><i class='fa-solid fa-paper-plane'></i> Share</a>
                </div>
            </div>
        ";
    }
?>
<script>
    $( document ).ready(function() {
        const date_holder = document.querySelectorAll('.date-target')

        date_holder.forEach(e => {
            const date = new Date(e.textContent);
            e.textContent = getDateToContext(e.textContent, "calendar")
        })
    })
</script>