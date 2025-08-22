<div class="row">
    <div class="col-6">
        <div class="container-fluid bg-light-warning p-4 me-4">
            <?php
                $stats['data'] = $dt_distribution_pin;
                $stats['ctx'] = 'distribution_pin_category';
                $this->load->view('others/bar_chart', $stats);
            ?>      
        </div>
    </div>
    <div class="col-6">
        <div class="container-fluid bg-light-warning p-4 me-4">            
            <?php
                $stats['data'] = $dt_distribution_visit;
                $stats['ctx'] = 'distribution_visit_by';
                $this->load->view('others/bar_chart', $stats);
            ?>
        </div>
    </div>
</div>