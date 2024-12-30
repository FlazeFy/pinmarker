<?php
    $stats['data'] = $dt_visit_location_favorite;
    $stats['ctx'] = 'total_visit_by_favorite_status';
    $this->load->view('others/pie_chart', $stats);
?>