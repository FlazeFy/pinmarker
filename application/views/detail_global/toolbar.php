<span class="d-flex justify-content-between">
    <div>
        <?php 
            if($this->session->userdata('is_global_edit_mode') == false){
                echo "<h3>$dt_detail->list_name</h3>";
            } 
        ?>
    </div>
    <div>
        <?php 
            if($is_signed && !$is_mobile_device){
                echo "<a class='btn btn-success px-3 py-2 me-2' id='save-all-to-my-pin-btn'><i class='fa-solid fa-bookmark'></i> Save All to My Pin</a>";
            }
            if (!$is_mobile_device){
                echo "<a class='btn btn-dark px-3 py-2' id='share-global-pin-btn'><i class='fa-solid fa-paper-plane'></i> Share</a>";
            }
        ?>
        <?php 
            if($is_signed && $is_editable && !$is_mobile_device){
                echo "
                    <form action='/DetailGlobalController/edit_toggle/$dt_detail->id' method='POST' class='d-inline ms-2'>";
                        if($this->session->userdata('is_global_edit_mode') == false){
                            echo "<button class='btn btn-light px-3 py-2 me-2' id='toggle-edit-btn'><i class='fa-solid fa-pen-to-square'></i> Open Edit Mode</button>";
                        } else {
                            echo "<button class='btn btn-danger px-3 py-2 me-2' id='toggle-edit-btn'><i class='fa-solid fa-pen-to-square'></i> Close Edit Mode</button>";
                        }
                    echo"</form>
                ";
            }
        ?>
        <?php 
            if($is_signed && $is_editable && !$is_mobile_device){
                echo "
                    <form action='/DetailGlobalController/delete_global_list/$dt_detail->id' method='POST' class='d-inline ms-2' id='remove-list-form'>
                        <a class='btn btn-danger px-3 py-2 me-2' id='remove-list-btn' onclick='remove_list()'><i class='fa-solid fa-trash'></i> Delete Global List</a>
                    </form>
                ";
            }
        ?>
    </div>
</span>