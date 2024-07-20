<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinMarker | Add Marker</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <script src="https://kit.fontawesome.com/328b2b4f87.js" crossorigin="anonymous"></script>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('public/css/global.css') ?>">

    <!-- Swal -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="content">
        <?php $this->load->view('others/navbar'); ?>
        <h2 class="text-center" style="font-weight:600;">Add New Marker</h2>
        <a class="btn btn-dark mb-4 rounded-pill py-3 px-4" href="/MapsController"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <a class="btn btn-dark mb-4 rounded-pill py-3 px-4"><i class="fa-solid fa-upload"></i> Import Marker</a>
        <?php $this->load->view('add/form'); ?>
        <hr>
    </div>
    <?php $this->load->view('others/footer'); ?>
</body>
</html>