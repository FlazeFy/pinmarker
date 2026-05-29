<div class="d-flex mx-auto mb-4" id="statistic-holder">
    <div id="carouselExampleControls" class="carousel slide position-relative" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <?php
                                $stats['data'] = $dt_get_stats_total_pin_by_category;
                                $stats['ctx'] = 'total_pin_by_category';
                                $this->load->view('others/pie_chart', $stats);
                            ?>      
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">            
                            <?php
                                $stats['data'] = $dt_get_stats_total_visit_pin_category;
                                $stats['ctx'] = 'total_visit_pin_category';
                                $this->load->view('others/pie_chart', $stats);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <?php
                                $stats['data'] = $dt_get_stats_total_visit_by;
                                $stats['ctx'] = 'total_visit_by';
                                $this->load->view('others/pie_chart', $stats);
                            ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <?php
                                $stats['data'] = $dt_get_most_visit_with;
                                $stats['ctx'] = 'total_visit_with';
                                $this->load->view('others/pie_chart', $stats);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="position-absolute" style="top: -40px; right: 40px;">
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>