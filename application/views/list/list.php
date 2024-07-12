<style>
    .pin-box {
        width: 100%;
        border: 2.5px solid black;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 10px;
        cursor: pointer;
        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        transition: all 0.4s;
    }
    .pin-box:hover {
        transform: scale(1.05);
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }
</style>
<?php 
    if(count($dt_my_pin) > 0){
        foreach($dt_my_pin as $dt){
            echo "
                <div class='pin-box'>
                    <h3>$dt->pin_name</h3>
                    <span class='bg-dark rounded-pill px-3 py-2 text-white'>$dt->pin_category</span>
                    ";
                    if($dt->is_favorite == 1){
                        echo "<span class='bg-dark rounded-pill px-3 py-2 text-white'><i class='fa-solid fa-bookmark'></i></span>";
                    }
                    echo "<br><br>";
                    if($dt->pin_desc){
                        echo "<p>$dt->pin_desc</p>";
                    } else {
                        echo "<p class='text-secondary fst-italic'>- No Description -</p>";
                    }
                    echo "<div class='row py-0 my-0'>";
                    if($dt->pin_person){
                        echo "<div class='col-lg-4 col-md-6 col-sm-12'><p class='mt-2 mb-0 fw-bold'>Person In Touch</p>
                        <p>$dt->pin_person</p></div>";
                    }
                    if($dt->pin_call){
                        echo "<div class='col-lg-4 col-md-6 col-sm-12'><p class='mt-2 mb-0 fw-bold'>Phone Number</p>
                        <p>$dt->pin_call</p></div>";
                    }
                    if($dt->pin_email){
                        echo "<div class='col-lg-4 col-md-6 col-sm-12'><p class='mt-2 mb-0 fw-bold'>Email</p>
                        <p>$dt->pin_email</p></div>";
                    }
                    echo"
                    </div>
                    <div class='row py-0 my-0'>
                        <div class='col-lg-4 col-md-6 col-sm-12'>
                            <p class='mt-2 mb-0 fw-bold'>Created At</p>
                            <p>"; echo date("Y-m-d H:i",strtotime($dt->created_at)); echo"</p>
                        </div>
                        <div class='col-lg-4 col-md-6 col-sm-12'>
                            <p class='mt-2 mb-0 fw-bold'>Total Visit</p>
                            <p>$dt->total_visit</p>
                        </div>
                        <div class='col-lg-4 col-md-6 col-sm-12'>
                            <p class='mt-2 mb-0 fw-bold'>Last Visit</p>
                            <p>dt->last_visit_desc</p>
                        </div>
                    </div>
                    <a class='btn btn-dark rounded-pill px-2 py-1 me-2' href='/DetailController/view/$dt->id'><i class='fa-solid fa-circle-info'></i> See Detail</a>
                    <a class='btn btn-dark rounded-pill px-2 py-1'><i class='fa-solid fa-location-arrow'></i> Set Direction</a>
                </div>
            ";
        }
    } else {
        echo "
            <div class='text-center my-3'>
                <img src='http://127.0.0.1:8080/public/images/pin.png' class='img nodata-icon'>
                <h5>You have no marker to show</h5>
            </div>
        ";
    }
?>

<?php 
    if($this->session->flashdata('message_generated_error')){
        echo "
            <script>
                Swal.fire({
                    title: 'Failed!',
                    text: '".$this->session->flashdata('message_generated_error')."',
                    icon: 'error'
                });
            </script>
        ";
    }
    if($this->session->flashdata('message_generated_success')){
        echo "
            <script>
                Swal.fire({
                    title: 'Success!',
                    text: '".$this->session->flashdata('message_generated_success')."',
                    icon: 'success'
                });
            </script>
        ";
    }
?>