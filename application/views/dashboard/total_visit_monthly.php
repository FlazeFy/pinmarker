<div class="card" id="total_visit_monthly-section">
    <?php
        $year_filter = $this->session->userdata('year_filter') ?? date('Y');
        $stats['data'] = $dt_get_total_visit_by_month;
        $stats['ctx'] = "total_visit_per_month_$year_filter";
        $stats['label'] = "Total Visit";
        $this->load->view('others/line_chart', $stats);
    ?>
</div>