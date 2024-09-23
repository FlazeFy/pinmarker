<?php 
    if($dt_active_search){
        if(count($dt_global) > 0){
            echo "<div class='mt-4'>"; 
            foreach($dt_global as $idx => $dt){
                echo "
                    <div class='pin-box mb-4'>
                        <div class='pin-box-label "; if(!$is_mobile_device){ echo "position-absolute"; } else { echo "float-end mb-1"; } echo "'"; if(!$is_mobile_device){ echo "style='right:-15px; top:-15px;'"; } echo ">$dt->total Marker</div>
                        <h3 id='list-name-holder-$idx'>$dt->list_name</h3>";
                        if($dt->list_desc){
                            echo "<p>$dt->list_desc</p>";
                        } else {
                            echo "<p class='text-secondary fst-italic'>- No Description -</p>";
                        }
                        if($dt->list_tag){
                            $list_tag = json_decode($dt->list_tag);
                            foreach($list_tag as $tag){
                                echo "<a class='pin-box-label me-2 mb-1 text-decoration-none search-global-by-tag-btn' href='http://127.0.0.1:8080/GlobalMapsController/view/$tag->tag_name'>#$tag->tag_name</a>";
                            }
                        } else {
                            echo "<p class='text-secondary fst-italic'>- No Tag -</p>";
                        }
                        echo "<hr>
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
                        <a class='btn btn-dark rounded-pill px-2 py-1 me-2 see-detail-btn' href='/DetailGlobalController/view/$dt->id'><i class='fa-solid fa-circle-info'></i> See Detail</a>
                        <a class='btn btn-dark rounded-pill px-2 py-1 share-global-pin-btn'><i class='fa-solid fa-paper-plane'></i> Share</a>
                        </div>
                    </div>
                ";
            }
        } else {
            echo "
                <div class='text-center text-secondary'>
                    <img class='img img-fluid m-1' style='width:200px;' src='http://127.0.0.1:8080/public/images/empty_item.png'>
                    <h6>No pin or list found</h6>
                </div>
            ";
        }
    }
?>