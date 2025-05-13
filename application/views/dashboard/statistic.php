<div class="d-flex mx-auto" id="statistic-holder">
    <div id="carouselExampleControls" class="carousel slide position-relative" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="row">
                    <div class="col-4">
                        <div class="container-fluid p-4 me-4">
                            <h2 class="mt-3 fw-bold" style="font-size: 40px;">Statistic</h2>
                            <h5 class="text-secondary fw-normal mb-3">We've provided several charts that visualize your data. You can see the most frequently used categories for your markers, the most visited places by category, and much more.</h5>
                            <?php $this->load->view('dashboard/control_panel'); ?>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="container-fluid bg-light-warning p-4 me-4">
                            <?php
                                $stats['data'] = $dt_get_stats_total_pin_by_category;
                                $stats['ctx'] = 'total_pin_by_category';
                                $this->load->view('others/pie_chart', $stats);
                            ?>      
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="container-fluid bg-light-warning p-4 me-4">            
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
                    <div class="col-4">
                        <div class="container-fluid bg-light-warning p-4 me-4">
                            <?php
                                $stats['data'] = $dt_get_stats_total_gallery;
                                $stats['ctx'] = 'total_gallery_by_pin';
                                $this->load->view('others/pie_chart', $stats);
                            ?>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="container-fluid bg-light-warning p-4 me-4">
                            <?php
                                $stats['data'] = $dt_get_stats_total_visit_by;
                                $stats['ctx'] = 'total_visit_by';
                                $this->load->view('others/pie_chart', $stats);
                            ?>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="container-fluid bg-light-warning p-4 me-4">
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
        <div class="position-absolute" style="top: -80px; right: 60px;">
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
<br><br>
<div class="container-fluid bg-light-primary p-4 me-4">
    <?php
        $year_filter = $this->session->userdata('year_filter') ?? date('Y');
        $stats['data'] = $dt_get_total_visit_by_month;
        $stats['ctx'] = "total_visit_by_month_$year_filter";
        $this->load->view('others/line_chart', $stats);
    ?>
</div>
<br><br>
<?php
    if($this->session->userdata('role_key')){
        $this->load->view('dashboard/distance_monthly');
    }
?>
<div id="statistic_test_target"></div>