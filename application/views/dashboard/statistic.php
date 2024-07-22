<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <?php
            $stats['data'] = $dt_get_stats_total_pin_by_category;
            $stats['ctx'] = 'total_pin_by_category';
            $this->load->view('others/pie_chart', $stats);
        ?>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <?php
            $stats['data'] = $dt_get_stats_total_visit_by_category;
            $stats['ctx'] = 'total_visit_by_category';
            $this->load->view('others/pie_chart', $stats);
        ?>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <?php
            $stats['data'] = $dt_get_stats_total_gallery;
            $stats['ctx'] = 'total_gallery_by_pin';
            $this->load->view('others/pie_chart', $stats);
        ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 pt-3">
        <?php
            $stats['data'] = $dt_get_total_visit_by_month;
            $stats['ctx'] = 'total_visit_by_month';
            $this->load->view('others/line_chart', $stats);
        ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 pt-3">
        <?php
            $this->load->view('dashboard/distance_monthly');
        ?>
    </div>
</div>
<div id="statistic_test_target"></div>