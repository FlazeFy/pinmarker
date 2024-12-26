<?php
    $stats['data'] = $dt_visit_pertime_year;
    $stats['ctx'] = 'total_visit_with_by_month';
    $this->load->view('others/line_chart', $stats);
?>