<div class="d-flex" style="min-height:100vh; background:var(--containerColor);">
    <?php $this->load->view("others/left_bar"); ?>
    <div class="main-wrap">
        <?php $this->load->view("others/top_bar"); ?>
        <div class="content">
            <?php $this->load->view("add_visit/header"); ?>
            <div class="row mb-4">
                <div class="col-lg-8">
                    <?php $this->load->view('add_visit/form'); ?>
                </div>
                <div class="col-lg-4">
                    <?php $this->load->view('add_visit/maps_select'); ?>
                </div>
            </div>
        </div>
    </div>
</div>