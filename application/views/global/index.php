<script>
    $(document).ready(function() {
        $('.grid').isotope({
            itemSelector: '.grid-item',
            layoutMode: 'masonry', 
        });
    })
</script>
<h2 class="text-center" style="font-weight:600;">Global List</h2><br>
<a class="btn btn-success btn-menu-main" href="/AddGlobalListController" id='add-global-list-btn'><i class="fa-solid fa-plus"></i><?php if(!$is_mobile_device){ echo " Add Global List";} ?></a>
<?php if(!$is_mobile_device){ echo "<br><br>"; } ?>
<?php $this->load->view('global/global_list'); ?>