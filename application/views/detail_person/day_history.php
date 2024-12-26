<?php
    $stats['data'] = $dt_visit_pertime_dayname;
    $stats['ctx'] = 'total_visit_with_by_day';
    $this->load->view('others/pie_chart', $stats);
?>