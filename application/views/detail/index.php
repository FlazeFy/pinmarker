<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinMarker | Detail</title>
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
    <link href="<?= base_url('public/css/maps.css') ?>" rel="stylesheet"/>

    <!-- Jquery -->
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!-- Javascript -->
    <script src="http://127.0.0.1:8080/public/js/global.js"></script>
    <script src="http://127.0.0.1:8080/public/js/maps.js"></script>

    <!--Apex Chart-->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Swal -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    <?php $is_edit = $this->session->userdata('is_edit_mode'); ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

    <div class="content">
        <?php $this->load->view('others/navbar'); ?>
        <?php $this->load->view('detail/detail'); ?>

        <?php if (!$is_edit): ?>
            <hr>
            <?php $this->load->view('detail/props'); ?>
        <?php endif; ?>
    </div>

    <script>
        const date_holder = document.querySelectorAll('.date-target');

        date_holder.forEach(e => {
            const date = new Date(e.textContent);
            e.textContent = getDateToContext(e.textContent, "calendar");
        });
    </script>
    <?php $this->load->view('others/footer'); ?>
</body>
</html>