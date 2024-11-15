<?php 
    if($this->session->userdata('is_catalog_view') == false && !$this->session->userdata('open_pin_list_category')){
        echo "
        <form class='d-inline' method='POST' action='/ListController/view_toogle'>
            <button class='btn btn-dark btn-menu-main p-0' style='bottom:calc(4*var(--spaceXLG));' id='toggle-view-btn'><i class='fa-solid fa-folder-open'></i>"; if(!$is_mobile_device){ echo " Catalog";} echo"</button>
        </form>";
    } else if(!$this->session->userdata('open_pin_list_category')) {
        echo "
        <form class='d-inline' method='POST' action='/ListController/view_toogle'>
            <button class='btn btn-dark btn-menu-main p-0' style='bottom:calc(4*var(--spaceXLG));' id='toggle-view-btn'><i class='fa-solid fa-list'></i>"; if(!$is_mobile_device){ echo " List";} echo"</button>
        </form>";
    } else {
        echo "
        <form class='d-inline' method='POST' action='/ListController/view_catalog_detail/back'>
            <button class='btn btn-danger btn-menu-main p-0' style='bottom:calc(4*var(--spaceXLG));' id='toggle-view-btn'><i class='fa-solid fa-arrow-left'></i>"; if(!$is_mobile_device){ echo " Back";} echo"</button>
        </form>";
    }
?>