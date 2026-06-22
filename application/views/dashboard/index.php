<div class="d-flex" style="min-height:100vh; background:var(--containerColor);">
    <?php $this->load->view("others/left_bar"); ?>
    <div class="main-wrap">
        <?php $this->load->view("others/top_bar"); ?>
        <div class="content">
            <div class="row g-4">
                <div class="col-xl-8 col-lg-7">
                    <?php $this->load->view("dashboard/summary"); ?>
                    <div id="main-category-section">
                        <?php $this->load->view("dashboard/category_marker"); ?>
                    </div>
                    <?php $this->load->view("dashboard/comparison_stats"); ?>
                    <?php $this->load->view("dashboard/total_visit_monthly"); ?>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <?php $this->load->view("dashboard/mini_profile"); ?>
                    <?php $this->load->view("dashboard/recent_activity"); ?>
                    <?php $this->load->view("dashboard/news_around_me"); ?>
                    <?php $this->load->view("dashboard/sync"); ?>
                </div>
            </div>
        </div>
    </div>
</div>
