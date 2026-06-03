<div class="d-flex" style="min-height:100vh; background:var(--containerColor);">
    <?php $this->load->view("others/left_bar"); ?>
    <div class="main-wrap">
        <?php $this->load->view("others/top_bar"); ?>
        <div class="content">
            <?php $this->load->view("person/header"); ?>
            <div class="row">
                <div class="col-xl-3">
                    <?php $this->load->view("person/activity"); ?>
                </div>
                <div class="col-xl-9">
                    <div id="all-person-section">
                        <?php $this->load->view("person/top_person_journey"); ?>
                    </div>
                    <div id="single-person-section">
                        <?php $this->load->view("person/person_profile"); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>