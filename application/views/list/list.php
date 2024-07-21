<style>
    .pin-box {
        width: 100%;
        border: 2.5px solid black;
        padding: var(--spaceMD);
        margin-bottom: 20px;
        border-radius: 10px;
        cursor: pointer;
        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        transition: all 0.4s;
        position: relative;
    }
    .pin-box:hover {
        transform: scale(1.05);
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }
    .pin-box .pin-box-label {
        background: var(--primaryColor);
        color: var(--whiteColor);
        padding: var(--spaceXXSM) var(--spaceSM);
        font-size: var(--textMD);
        font-weight: 500;
        display: inline-block;
        border-radius: var(--roundedMD);
    }
    .list-pin-desc {
        overflow: hidden; 
        text-overflow: ellipsis; 
        display: -webkit-box; 
        -webkit-line-clamp: 5; 
        line-clamp: 5; 
        -webkit-box-orient: vertical;
    }
</style>
<?php 
    if($this->session->userdata('is_catalog_view') == false || $this->session->userdata('open_pin_list_category')){
        if(count($dt_my_pin) > 0){
            foreach($dt_my_pin['data'] as $dt){
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
                        if($dt->pin_call && !$is_mobile_device){
                            echo "<div class='col-lg-4 col-md-6 col-sm-12'><p class='mt-2 mb-0 fw-bold'>Phone Number</p>
                            <p>$dt->pin_call</p></div>";
                        }
                        if($dt->pin_email && !$is_mobile_device){
                            echo "<div class='col-lg-4 col-md-6 col-sm-12'><p class='mt-2 mb-0 fw-bold'>Email</p>
                            <p>$dt->pin_email</p></div>";
                        }
                        echo"
                        </div>
                        <div class='row py-0 my-0'>";

                        if(!$is_mobile_device){
                            echo"
                            <div class='col-lg-4 col-md-6 col-sm-12'>
                                <p class='mt-2 mb-0 fw-bold'>Created At</p>
                                <p class='date-target'>$dt->created_at</p>
                            </div>
                            <div class='col-lg-4 col-md-6 col-sm-12'>
                                <p class='mt-2 mb-0 fw-bold'>Total Visit</p>
                                <p>$dt->total_visit</p>
                            </div>";
                        }
                            echo"
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

            echo "<div class='d-inline-block'>
                <h5>Page</h5>";

            $active = 0;
            if($this->session->userdata('page_pin')){
                $active = $this->session->userdata('page_pin');
            }

            for($i = 0; $i < $dt_my_pin['total_page']; $i++){
                $page = $i + 1;
                echo "
                    <form method='POST' class='d-inline' action='/ListController/navigate/$i'>
                        <button class='btn btn-page"; 
                        if($active == $i){echo " active";}
                        echo" me-1' type='submit'>$page</button>
                    </form>
                ";
            }

            echo "</div>";
        } else {
            echo "
                <div class='text-center my-3'>
                    <img src='http://127.0.0.1:8080/public/images/pin.png' class='img nodata-icon'>
                    <h5>You have no marker to show</h5>
                </div>
            ";
        }
    } else {
        if(count($dt_my_pin) > 0){
            echo "<div class='row'>";
            foreach($dt_my_pin as $dt){
                echo "
                    <div class='col-lg-4 col-md-6 col-sm-12'>
                        <div class='pin-box mb-4'>
                            <div class='pin-box-label "; if(!$is_mobile_device){ echo "position-absolute"; } else { echo "float-end mb-1"; } echo "'"; if(!$is_mobile_device){ echo "style='right:-15px; top:-15px;'"; } echo ">$dt->total Marker</div>
                            <h3>$dt->dictionary_name</h3>
                            <p class='list-pin-desc'>"; 
                                if($dt->pin_list){
                                    echo $dt->pin_list;
                                } else {
                                    echo '<span class="fst-italic text-secondary">- No Marker Found -</span>';
                                }
                            echo"</p>
                            <form method='POST' action='/ListController/view_catalog_detail/$dt->dictionary_name' class='d-inline'>
                                <button class='btn btn-dark rounded-pill px-2 py-1 me-2' href='/DetailController/'><i class='fa-solid fa-circle-info'></i> See Detail</button>
                            </form>
                            <a class='btn btn-dark rounded-pill px-2 py-1'><i class='fa-solid fa-globe'></i> Publish to Global</a>
                        </div>
                    </div>
                ";
            }
            echo "</div>";
        } else {
            echo "
                <div class='text-center my-3'>
                    <img src='http://127.0.0.1:8080/public/images/pin.png' class='img nodata-icon'>
                    <h5>You have no marker to show</h5>
                </div>
            ";
        }
    }
?>

<?php 
    if($this->session->flashdata('message_error')){
        echo "
            <script>
                Swal.fire({
                    title: 'Failed!',
                    text: '".$this->session->flashdata('message_error')."',
                    icon: 'error'
                });
            </script>
        ";
    }
    if($this->session->flashdata('message_success')){
        echo "
            <script>
                Swal.fire({
                    title: 'Success!',
                    text: '".$this->session->flashdata('message_success')."',
                    icon: 'success'
                });
            </script>
        ";
    }
?>

<script>
    const date_holder = document.querySelectorAll('.date-target');

    date_holder.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "calendar");
    });
</script>