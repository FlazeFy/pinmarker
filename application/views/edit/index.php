<div class="d-flex" style="min-height:100vh; background:var(--containerColor);">
    <?php $this->load->view("others/left_bar"); ?>
    <div class="main-wrap">
        <?php $this->load->view("others/top_bar"); ?>
        <div class="content">
            <?php $this->load->view("edit/header"); ?>
            <div class="row mb-4">
                <div class="col-lg-8">
                    <?php $this->load->view('edit/form'); ?>
                    <?php $this->load->view('edit/schedule'); ?>
                </div>
                <div class="col-lg-4">
                    <?php $this->load->view('edit/maps_select'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
