<?php
    $stats['data'] = $dt_visit_location_category;
    $stats['ctx'] = 'total_visit_by_pin_category';
    $this->load->view('others/pie_chart', $stats);
?>