<div class="<?php if(!$dt_active_search){ echo"text-center"; } ?> position-relative pt-4 w-100" style="<?php if(!$is_mobile_device){ echo "margin-top:50vh;"; } else { echo "margin-top:13vh;"; } ?>" id="global-section">
    <div class="position-absolute text-start" style="top:-60px;">
        <h1 class="mb-0">PINMARKER</h1>
        <h4 class="text-secondary">
            <img class='img img-fluid mb-1' style='width:var(--spaceJumbo);' src='http://127.0.0.1:8080/public/images/logo.png'> Marks Your World
        </h4>
    </div>
    <div style="border: var(--spaceMini) solid black; border-radius: 15px; <?php if(!$dt_active_search && !$is_mobile_device){ echo"height: 300px;"; } ?> z-index:1000; position: relative;" class="bg-white <?php if(!$is_mobile_device){ echo "p-4"; } else { echo "py-3 px-2"; } ?>">
        <h3>Search Global Pin</h3>
        <div class="position-relative mx-auto" style="max-width:600px;">
            <input class="form-control <?php if(!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> px-4" placeholder="Search by list name or list tag..." style="font-weight: <?php if(!$is_mobile_device){ echo "700; font-size: var(--textJumbo)"; } else { echo "500; font-size: var(--textXLG)"; } ?>" id="search_input" value="<?= $dt_active_search ?>" style="max-width: 480px;">
            <?php 
                if($dt_active_search){
                    echo "<a id='cancel-search-btn' class='position-absolute btn btn-dark "; if(!$is_mobile_device){ echo "px-3 py-2"; } else { echo "px-2 py-1"; } echo"' href='/' style='"; if(!$is_mobile_device){ echo "right: 12px; top: 12px"; } else { echo "right: 7px; top: 6px"; } echo"'><i class='fa-solid fa-xmark'></i></a>";
                }
            ?>
        </div>
        <?php 
            if($dt_active_search){
                if(count($dt_global) > 0){
                    echo "<div class='row mt-4 grid'>"; 
                    foreach($dt_global as $idx => $dt){
                        echo "
                            <div class='col-lg-4 col-md-6 col-sm-12 col-12 grid-item'>
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
                                            echo "<a class='pin-box-label me-2 mb-1 text-decoration-none search-global-by-tag-btn' href='http://127.0.0.1:8080/LoginController/view/$tag->tag_name'>#$tag->tag_name</a>";
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
                                    <a class='btn btn-dark px-2 py-1 me-2 see-detail-btn' href='/DetailGlobalController/view/$dt->id'><i class='fa-solid fa-circle-info'></i> See Detail</a>
                                    <a class='btn btn-dark px-2 py-1 share-global-pin-btn'><i class='fa-solid fa-paper-plane'></i> Share</a>
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
            <?php if (!$is_mobile_device): ?>
                <h4 class="text-secondary mt-4">Or, search by maps</h4>
            <?php else: ?>
                <h5 class="text-secondary mt-3">Or, search by maps</h5>
            <?php endif; ?>
            <a class="btn btn-dark px-4 <?php if(!$is_mobile_device){ echo "py-3"; } else { echo "py-2"; } ?> fw-bold" id='global-maps-btn' href="<?php if($dt_active_search){ echo"/GlobalMapsController/view/$dt_active_search"; } else { echo"/GlobalMapsController"; }?>"><i class="fa-solid fa-earth-americas"></i> Open Global Maps</a>
        </div>
    </div>
    <?php if (!$is_mobile_device): ?>
        <img class='img img-fluid position-absolute' style="max-width:480px; top:-42vh; z-index:98; left:25vw;" src='http://127.0.0.1:8080/public/images/pinmarker.png'>
    <?php else:?>
        <br><hr>
    <?php endif; ?>
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

        $('.share-global-pin-btn').on('click', function() {
            const idx = $(this).index('.share-global-pin-btn')
            const list_name = $(`#list-name-holder-${idx}`).text().trim().replace(' ','%20')
            messageCopy(`http://127.0.0.1:8080/LoginController/view/${list_name}`)
        })
    })
</script>