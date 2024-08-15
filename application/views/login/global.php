<div class="<?php if(!$dt_active_search){ echo"text-center"; } ?> position-relative pt-4 w-100" style="margin-top:50vh;" id="global-section">
    <div class="position-absolute text-start" style="top:-60px;">
        <h1 class="mb-0">PINMARKER</h1>
        <h4 class="text-secondary">Web-Based Location Marker</h4>
    </div>
    <div style="border: var(--spaceMini) solid black; border-radius: 15px; <?php if(!$dt_active_search){ echo"height: 300px;"; } ?> z-index:1000; position: relative;" class="bg-white p-4">
        <h3>Search Global Pin</h3>
        <div class="position-relative mx-auto" style="max-width:600px;">
            <input class="form-control py-3 px-4" placeholder="Search by list name or list tag..." style="font-weight: 700; font-size: var(--textJumbo);" id="search_input" value="<?= $dt_active_search ?>" style="max-width: 480px;">
            <?php 
                if($dt_active_search){
                    echo "<a class='position-absolute btn btn-dark px-3 py-2' href='/' style='right: 12px; top: 12px;'><i class='fa-solid fa-xmark'></i></a>";
                }
            ?>
        </div>
        <?php 
            if($dt_active_search){
                if(count($dt_global) > 0){
                    echo "<div class='row mt-4 grid'>"; 
                    foreach($dt_global as $idx => $dt){
                        echo "
                            <div class='col-lg-4 col-md-6 col-sm-12 grid-item'>
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
                                            echo "<a class='pin-box-label me-2 mb-1 text-decoration-none' href='http://127.0.0.1:8080/LoginController/view/$tag->tag_name'>#$tag->tag_name</a>";
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
                                    <a class='btn btn-dark rounded-pill px-2 py-1 me-2' href='/DetailGlobalController/view/$dt->id'><i class='fa-solid fa-circle-info'></i> See Detail</a>
                                    <a class='btn btn-dark rounded-pill px-2 py-1 share-global-pin'><i class='fa-solid fa-paper-plane'></i> Share</a>
                                    </div>
                            </div>
                        ";
                    }
                    echo "</div>";
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
        <div class="text-center">
            <h4 class="text-secondary mt-4">Or, search by maps</h4>
            <a class="btn btn-dark rounded-pill px-4 py-3 fw-bold">Open Global Maps</a>
        </div>
    </div>
    <img class='img img-fluid position-absolute' style="max-width:480px; top:-42vh; z-index:98; left:25vw;" src='http://127.0.0.1:8080/public/images/pinmarker.png'>
</div>

<script>
    $(document).ready(function() {
        $('#search_input').on('blur', function() {
            const val = this.value.trim()
            const old_val = '<?= $dt_active_search; ?>'

            if(old_val != val && val && val != ''){
                window.location.href = `/LoginController/view/${this.value}`
            } else if((old_val != val && val) || val == ''){
                window.location.href = `/`
            } else {
               
            }
        })

        $('.share-global-pin').on('click', function() {
            const idx = $(this).index('.share-global-pin')
            const list_name = $(`#list-name-holder-${idx}`).text().trim().replace(' ','%20')
            messageCopy(`http://127.0.0.1:8080/LoginController/view/${list_name}`)
        })
    })
</script>