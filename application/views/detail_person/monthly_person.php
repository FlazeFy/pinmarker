<?php
    $stats['data'] = $dt_visit_pertime_year;
    $year_filter = $this->session->userdata('year_filter') ?? date('Y');
    $stats['ctx'] = "total_visit_with_by_month_in_$year_filter";
    $this->load->view('others/line_chart', $stats);
?>