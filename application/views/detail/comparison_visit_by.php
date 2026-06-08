<div class="card">            
    <?php
        $stats['data'] = $dt_total_visit_by_by_pin;
        $stats['ctx'] = 'total_visit_pin_category';
        $this->load->view('others/pie_chart', $stats);
    ?>
</div>