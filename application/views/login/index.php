<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinMarker | Login</title>

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
    <?php 
        if($dt_active_search){
            echo '<link href="http://127.0.0.1:8080/public/css/pin.css" rel="stylesheet"/>';
        }
    ?>

    <!-- Javascript -->
    <script src="http://127.0.0.1:8080/public/js/global.js"></script>

    <!-- Jquery -->
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!--Apex Chart-->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

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
        <?php $this->load->view('login/global'); ?>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-12 position-relative">
                <?php if (!$is_mobile_device): ?>
                    <a class="position-absolute btn btn-dark rounded-pill px-4 py-2" href="#login-section" style="left:40%; top:10px;">Go to Login</a>
                    <div class="mx-auto" style="border: var(--spaceMini) solid black; width:30px; height:85vh; margin-top:-20px;"></div>
                <?php else: ?>
                    <hr>
                <?php endif; ?>
                <div class="position-relative">                
                    <?php $this->load->view('login/form'); ?>
                    <?php if (!$is_mobile_device): ?>
                        <div style="border: var(--spaceMini) solid black; width:120%; height:30px; margin-top:-45%; margin-left:20%; z-index:97;"></div>
                    <?php endif; ?>
                </div>          
            </div>
            <?php if ($is_mobile_device): ?>
                <br><hr><br>
            <?php endif; ?>
            <div class="col py-4 text-center">
                <img class='img img-fluid mt-3' style="height:40%;" src='http://127.0.0.1:8080/public/images/global.png'>
                <h2 class="fw-bold">SHARE YOUR PINNED LOCATION WITH EVERYONE</h2>
                <p class="text-secondary mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Nunc sed blandit libero volutpat sed cras ornare</p>
                <?php $this->load->view('login/socmed')?>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-lg-6 col-md-6 col-sm-12 col-12 position-relative py-3">
                <br><br>
                <img class='img img-fluid mt-3 mx-auto d-block' style="height:40%;" src='http://127.0.0.1:8080/public/images/track.png'>
                <h2 class="fw-bold">TRACKING YOUR JOURNEY</h2>
                <p class="text-secondary mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Nunc sed blandit libero volutpat sed cras ornare</p>

                <br><br>
                <h2 class="fw-bold" style="margin-top:10vh;">SOME FACTS!</h2>
                <div class="row mt-4">
                    <div class="col">
                        <h2 class="mb-0"><?= $dt_total_user[0]->total ?></h2>
                        <h5>Total User</h5>
                    </div>
                    <div class="col">
                        <h2 class="mb-0"><?= $dt_total_pin[0]->total ?></h2>
                        <h5>Total Marker</h5>
                    </div>
                    <div class="col">
                        <h2 class="mb-0"><?= $dt_total_visit[0]->total ?></h2>
                        <h5>Total Visit</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-12 pb-4">
                <?php if (!$is_mobile_device): ?>
                    <div class="mx-auto" style="border: var(--spaceMini) solid black; width:30px; height:95vh; margin-top:-30%;"></div>
                <?php endif; ?>
                <?php $this->load->view('login/feedback'); ?>
            </div>
        </div>
        <?php $this->load->view('login/platform'); ?>
    </div>
    <?php $this->load->view('others/footer'); ?>
</body>
</html>