<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinMarker | List</title>
    <link rel="icon" type="image/png" href="http://127.0.0.1:8080/public/images/logo_white.png"/>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <script src="https://kit.fontawesome.com/328b2b4f87.js" crossorigin="anonymous"></script>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- CSS -->
    <link href="http://127.0.0.1:8080/public/css/global.css" rel="stylesheet"/>
    <link href="http://127.0.0.1:8080/public/css/pin.css" rel="stylesheet"/>
    <link href="http://127.0.0.1:8080/public/css/button.css" rel="stylesheet"/>
    <link href="http://127.0.0.1:8080/public/css/form.css" rel="stylesheet"/>
    <link href="http://127.0.0.1:8080/public/css/navbar.css" rel="stylesheet"/>

    <!-- Jquery -->
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!-- Javascript -->
    <script src="http://127.0.0.1:8080/public/js/global.js"></script>

    <!-- Swal -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Isotope -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.grid').isotope({
                itemSelector: '.grid-item',
                layoutMode: 'masonry', 
            });
        })
    </script>
</head>
<body>
    <div class="content">
        <?php $this->load->view('others/navbar'); ?>
        <h2 class="text-center" style="font-weight:600;">My Marker
            <?php 
                if($this->session->userdata('open_pin_list_category')){
                    echo "<span class='btn-dark btn-main-page rounded-pill' style='font-size: var(--textJumbo);'>{$this->session->userdata('open_pin_list_category')}</span>";
                }
            ?>
        </h2><br>
        <div class="d-flex justify-content-between">
            <div class="d-flex justify-content-start w-100">
                <a class="btn btn-success btn-menu-main" href="/AddController" id='add-marker-btn'><i class="fa-solid fa-plus"></i><?php if(!$is_mobile_device){ echo " Add Marker";} ?></a>
                <?php $this->load->view('list/toggle_view'); ?>
                <a class="btn btn-dark btn-menu-main" href="/ListController/print_pin" style='bottom:calc(7*var(--spaceXLG));' id='print-btn'><i class="fa-solid fa-print"></i><?php if(!$is_mobile_device){ echo " Print";} ?></a>
                <?php 
                    if($this->session->userdata('role_key') == 1){
                        echo '<a class="btn btn-dark btn-menu-main" style="bottom:calc(10*var(--spaceXLG));" data-bs-target="#manageCategory" id="set-category-modal-btn" data-bs-toggle="modal"><i class="fa-solid fa-gear"></i>'; if(!$is_mobile_device){ echo " Set Category";} echo'</a>';
                    }
                    $this->load->view('list/manage_category');
                    if($is_mobile_device){
                        $this->load->view('list/search');
                    }
                    if($is_mobile_device && $this->session->userdata('role_key') == 1){
                        echo '<a class="btn btn-danger btn-menu-main" style="bottom:calc(13*var(--spaceXLG));" href="/TrashController" id="trash-btn"><i class="fa-solid fa-trash"></i>'; if(!$is_mobile_device){ echo " Trash";} echo'</a>';
                    }
                ?>
            </div>
            <?php
                if(!$is_mobile_device && $this->session->userdata('role_key') == 1){
                    echo '<a class="btn btn-danger btn-menu-main" style="bottom:calc(13*var(--spaceXLG));" href="/TrashController" id="trash-btn"><i class="fa-solid fa-trash"></i>'; if(!$is_mobile_device){ echo " Trash";} echo'</a>';
                }
                if(!$is_mobile_device){
                    echo '<br><br><br>';
                }
            ?>
        </div>
        <?php 
            if(!$is_mobile_device){
                $this->load->view('list/search');
                echo '<br>';
            }
            $this->load->view('list/list'); 
        ?>
        <hr>
    </div>
    <?php $this->load->view('others/footer'); ?>
</body>
</html>