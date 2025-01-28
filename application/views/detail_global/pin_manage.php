<span class="d-flex justify-content-between">
    <div>
        <h5 class="<?php if($is_mobile_device){ echo "mt-2"; } ?>">List Marker</h5>
    </div>
    <div>
        <?php 
            if($is_editable){
                echo "
                    <a class='btn btn-success px-3 py-2' data-bs-target='#addMarker' id='add-marker-btn' data-bs-toggle='modal'><i class='fa-solid fa-plus'></i> "; if(!$is_mobile_device){ echo "Add Marker"; } echo"</a>
                ";
                $this->load->view('detail_global/add_pin');
            }
        ?>
        <form action="/DetailGlobalController/view_global_list_pin/<?= $dt_detail->id ?>" method="POST" class="d-inline">
            <button class='btn btn-dark px-3 py-2' id='toggle-view-btn'><i class='fa-solid fa-table'></i> 
                <?php if (!$is_mobile_device): ?>
                    See <?php if($view == 'table'){ echo'Catalog'; } else { echo 'Table'; } ?> View
                <?php endif; ?>
            </button>
        </form>
        <?php $this->load->view('detail_global/whole_map'); ?>
    </div>
</span>