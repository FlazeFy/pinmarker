<div class="container-fluid bg-light-primary p-4 me-4">
    <?php
        $stats['data'] = $dt_total_context_created_monthly_by_year;
        $stats['ctx'] = $ctx;
        $stats['label'] = $label;
        $this->load->view('others/line_chart', $stats);
    ?>
</div>