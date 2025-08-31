<div class="container-fluid bg-light-primary p-4 me-4">
    <?php
        $stats['data'] = $dt_total_pin_created_monthly_by_year;
        $stats['ctx'] = "total_created_pin_by_month";
        $stats['label'] = "Total Pin";
        $this->load->view('others/line_chart', $stats);
    ?>
</div>