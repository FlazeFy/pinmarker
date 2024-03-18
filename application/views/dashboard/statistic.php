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
</div>