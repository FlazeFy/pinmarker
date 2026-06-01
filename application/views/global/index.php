<div class="d-flex" style="min-height:100vh; background:var(--containerColor);">
    <?php $this->load->view("others/left_bar"); ?>
    <div class="main-wrap">
        <?php $this->load->view("others/top_bar"); ?>
        <div class="content">
            <?php $this->load->view("global/header"); ?>
            <h3 class="mb-3">My Collection</h3>
            <?php $this->load->view("global/filter"); ?>
            <?php $this->load->view("others/filter_companion"); ?>
            <?php $this->load->view("global/collection_list"); ?>
            <hr class="my-5">
            <h3 class="mb-3">Global Collection</h3>
        </div>
    </div>
</div>
