<?php $is_edit = $this->session->userdata('is_edit_mode'); ?>
<div class="d-flex justify-content-between mt-4">
    <a class="btn btn-danger mb-4 py-3 px-4 me-2" href="/MapsController" id="back-page-btn"><i class="fa-solid fa-arrow-left"></i><?php if (!$is_mobile_device){ echo " Back"; } ?></a>
    <span>
        <a class='btn btn-dark mb-4 py-3 px-4 me-1' href="/CustomDocController/view/<?= $dt_detail_pin->id ?>"><i class='fa-solid fa-print'></i><?php if(!$is_mobile_device){ echo " Custom Print"; } else { echo " Custom"; }?></a>
        <?php $this->load->view('detail/print'); ?>
        <?php $this->load->view('detail/edit_toggle'); ?>
        <?php $this->load->view('detail/favorite_toggle'); ?>
        <?php $this->load->view('detail/delete'); ?>
    </span>
</div>

<form action="/DetailController/edit_marker/<?= $dt_detail_pin->id ?>" method="POST">
<?php 
    if($this->session->flashdata('validation_error')){
        echo "
            <div class='alert alert-danger' role='alert'>
                <h5><i class='fa-solid fa-triangle-exclamation'></i> Error</h5>
                ".$this->session->flashdata('validation_error')."
            </div>
        "; 
    }
?>

<?php 
    if($is_edit){
        echo "
            <div class='row'>
                <div class='col-lg-6 col-md-6 col-sm-12'>
                    <p class='mt-2 mb-0 fw-bold'>Pin Name</p>
                    <input name='pin_name' id='pin_name' type='text' class='form-control' value='$dt_detail_pin->pin_name' required/>
                </div>
                <div class='col-lg-6 col-md-6 col-sm-12'>
                    <p class='mt-2 mb-0 fw-bold'>Pin Category</p>
                    <select name='pin_category' class='form-select' id='pin_category'>";
                        foreach($dt_dct_pin_category as $dt){
                            echo "<option value='$dt->dictionary_name-$dt->dictionary_color'";
                            if($dt->dictionary_name == $dt_detail_pin->pin_category){
                                echo " selected>$dt->dictionary_name</option>";
                            } else {
                                echo ">$dt->dictionary_name</option>";
                            }
                        }
                    echo"</select>
                </div>
            </div>";
    } else {
        echo "<h2 class='text-center' style='font-weight:600;'>$dt_detail_pin->pin_name 
            <span class='bg-dark text-light px-3 py-2 rounded-pill' style='font-size: 16px;'>$dt_detail_pin->pin_category</span>
        </h2>";
    }
?>

<div class="row mt-4">
    <div class="col-lg-6 col-md-6 col=sm-12">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-6 col-6">
                <p class='mt-2 mb-0 fw-bold'>Latitude</p>
                <?php 
                    if($is_edit){
                        echo "<input name='pin_lat' id='pin_lat' type='text' class='form-control' value='$dt_detail_pin->pin_lat' required/>";
                    } else {
                        echo "<p>$dt_detail_pin->pin_lat</p>";
                    }
                ?>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-6 col-6">
                <p class='mt-2 mb-0 fw-bold'>Longitude</p>
                <?php 
                    if($is_edit){
                        echo "<input name='pin_long' id='pin_long' type='text' class='form-control' value='$dt_detail_pin->pin_long' required/>";
                    } else {
                        echo "<p>$dt_detail_pin->pin_long</p>";
                    }
                ?>
            </div>
        </div>

        <p class='mt-2 mb-0 fw-bold'>Person In Touch</p>
        <?php 
            if($is_edit){
                echo "<input name='pin_person' id='pin_person' type='text' class='form-control' value='$dt_detail_pin->pin_person'/>";
            } else {
                if($dt_detail_pin->pin_person != null){ 
                    echo "<p>$dt_detail_pin->pin_person</p>";
                } else {
                    echo "<p>-</p>";
                }
            }
        ?>

        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <p class='mt-2 mb-0 fw-bold'>Email</p>
                <?php 
                    if($is_edit){
                        echo "<input name='pin_email' id='pin_email' type='email' class='form-control' value='$dt_detail_pin->pin_email'/>";
                    } else {
                        if($dt_detail_pin->pin_email != null){ 
                            echo "<p>$dt_detail_pin->pin_email</p>";
                        } else {
                            echo "<p>-</p>";
                        }
                    }
                ?>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <p class='mt-2 mb-0 fw-bold'>Phone Number</p>
                <?php 
                    if($is_edit){
                        echo "<input name='pin_call' id='pin_call' type='phone' class='form-control' value='$dt_detail_pin->pin_call'/>";
                    } else {
                        if($dt_detail_pin->pin_call != null){ 
                            echo "<p>$dt_detail_pin->pin_call</p>";
                        } else {
                            echo "<p>-</p>";
                        }
                    }
                ?>
            </div>
        </div>

        <p class='mt-2 mb-0 fw-bold'>Address</p>
        <?php 
            if($is_edit){
                echo "<textarea name='pin_address' id='pin_address' rows='5' class='form-control'>$dt_detail_pin->pin_address</textarea>";
            } else {
                if($dt_detail_pin->pin_address != null){
                    echo "<p>$dt_detail_pin->pin_address</p>";
                } else {
                    echo '<p>-</p>';
                }
            }
        ?>

        <p class='mt-2 mb-0 fw-bold'>Description</p>
        <?php 
            if($is_edit){
                echo "<textarea name='pin_desc' id='pin_desc' rows='5' class='form-control'>$dt_detail_pin->pin_desc</textarea>";
            } else {
                if($dt_detail_pin->pin_desc != null){
                    echo "<p>$dt_detail_pin->pin_desc</p>";
                } else {
                    echo '<p class="text-secondary fst-italic">- No Description Provided -</p>';
                }
            }
        ?>

        <?php 
            if($is_edit){
                echo "<button class='btn btn-success w-100 py-3 my-4' type='Submit' id='submit-btn'><i class='fa-solid fa-floppy-disk'></i> Save Changes</button>";
            } 
        ?>
    </form>

        <?php if (!$is_edit): ?>
        <?php 
            $this->load->view('detail/history');  
        ?>
       
        
        <p class='mt-2 mb-0 fw-bold'>Review By Person Visited With</p>
        <ol>
        <?php 
            $this->load->view('detail/review');  
        ?>
        </ol>
        <hr>
        
        <?php
            $stats['data'] = $dt_total_visit_by_by_pin;
            $stats['ctx'] = 'visit_using_stats';
            $this->load->view('others/pie_chart', $stats);
        ?>
        <hr>

        <p class='mt-2 mb-0 fw-bold'>Count Distance to Other Pin</p>
        <?php $this->load->view('detail/count_distance'); ?>
        <hr>
        <?php endif;?>

        <div class="d-flex justify-content-between mt-2 mb-0">
            <p class='fw-bold mt-1'>Galleries</p>
            <?php $this->load->view('detail/add_galleries'); ?>
        </div>
        <?php $this->load->view('detail/galleries'); ?>
        <hr>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <?php if (!$is_edit): ?>
            <hr>
            <p class='mt-2 mb-0 fw-bold'>Distance to My Personal Pin</p>
            <?php $this->load->view('detail/distance'); ?>
            <hr>
            <p class='mt-2 mb-0 fw-bold'>Tracked Activity Around</p>
            <?php $this->load->view('detail/tracker_activity_around'); ?>
        <?php else: ?>
            <?php $this->load->view('detail/props'); ?>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        $(document).on('click', '#delete-pin-btn', function() {
            Swal.fire({
                title: "Are you sure?",
                html: `Want to delete this pin?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $('#delete-pin-form').submit()
                }
            });
        })
    });
</script>
