<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinMarker | My Profile</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <script src="https://kit.fontawesome.com/328b2b4f87.js" crossorigin="anonymous"></script>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Jquery -->
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!--Apex Chart-->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Swal -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="http://127.0.0.1:8080/public/js/global.js"></script>
    <link href="http://127.0.0.1:8080/public/css/global.css" rel="stylesheet"/>

    <style>
        .gallery-btn {
            border: 2px solid black; border-radius: 15px;
            padding: var(--spaceMD);
            text-align: left;
            background: var(--whiteColor);
        }
        .gallery-btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="content">
        <?php $this->load->view('others/navbar'); ?>
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12">
                <?php $this->load->view('myprofile/edit_image'); ?>
                <?php $this->load->view('myprofile/profile'); ?>
            </div>
            <div class="col-lg-9 col-md-12 col-sm-12">
                <?php $this->load->view('myprofile/visit_activity'); ?>
                <?php $this->load->view('myprofile/track_distance_hourly'); ?>
                <?php $this->load->view('myprofile/date_visit'); ?>
                <?php $this->load->view('myprofile/my_gallery'); ?>
            </div>
        </div>
        <hr>
    </div>
    <?php $this->load->view('others/footer'); ?>
</body>
</html>