<div class="d-flex" style="min-height:100vh; background:var(--containerColor);">
    <?php $this->load->view("others/left_bar"); ?>
    <div class="main-wrap">
        <?php $this->load->view("others/top_bar"); ?>
        <div class="content">
            <?php $this->load->view("global/header"); ?>
            <?php $this->load->view("global/filter"); ?>
            <?php $this->load->view("global/collection_list"); ?>
        </div>
    </div>
</div>
