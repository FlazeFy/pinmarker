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
                    <div id="single-person-section" class="d-none flex-column gap-4">
                        <?php $this->load->view("person/person_profile"); ?>
                        <?php $this->load->view("person/daily_hour_visit"); ?>
                        <div class="row">
                            <div class="col-xl-7">
                                <?php $this->load->view("person/monthly_visit_bar"); ?>
                            </div>
                            <div class="col-xl-5">
                                <?php $this->load->view("person/recent_activity"); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-5">
                                <?php $this->load->view("person/visit_pin_category"); ?>
                            </div>
                            <div class="col-xl-7">
                                <?php $this->load->view("person/hourly_visit"); ?>
                            </div>
                        </div>
                        <?php $this->load->view("person/maps"); ?>
                        <div class="row">
                            <div class="col-xl-7">
                                
                            </div>
                            <div class="col-xl-5">
                                <?php $this->load->view("person/review"); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>