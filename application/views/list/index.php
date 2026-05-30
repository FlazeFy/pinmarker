<div class="d-flex" style="min-height:100vh; background:var(--containerColor);">
    <?php $this->load->view("others/left_bar"); ?>
    <div class="main-wrap">
        <?php $this->load->view("others/top_bar"); ?>
        <div class="content">
            <?php $this->load->view("list/header"); ?>
            <?php $this->load->view("list/summary"); ?>
            <?php $this->load->view("list/filter"); ?>
            <?php $this->load->view("list/marker_list"); ?>
        </div>
    </div>
</div>
