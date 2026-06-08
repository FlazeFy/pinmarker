<div class="card">            
    <?php
        $stats['data'] = $dt_total_visit_by_day;
        $stats['ctx'] = 'total_visit_per_day';
        $this->load->view('others/pie_chart', $stats);
    ?>
</div>