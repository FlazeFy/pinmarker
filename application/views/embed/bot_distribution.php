<div class="row">
    <div class="col-6">
        <div class="container-fluid bg-light-warning p-4 me-4">
            <?php
                $stats['data'] = $dt_relation_platform;
                $stats['ctx'] = 'distribution_by_platform';
                $this->load->view('others/bar_chart', $stats);
            ?>      
        </div>
    </div>
    <div class="col-6">
        <div class="container-fluid bg-light-warning p-4 me-4">            
            <?php
                $stats['data'] = $dt_relation_type;
                $stats['ctx'] = 'distribution_by_type';
                $this->load->view('others/bar_chart', $stats);
            ?>
        </div>
    </div>
</div>