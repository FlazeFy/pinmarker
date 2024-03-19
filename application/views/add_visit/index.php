<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinMarker | Add Visit</title>

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

    <style>
        .content {
            width: 1080px;
            display: block;
            margin-inline: auto;
            padding: 0 20px 20px 20px;
        }

        label {
            font-weight: 600;
        }
        input, select, textarea {
            border-radius: 15px !important;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1.5px solid black !important;
        }
        .form-check-label {
            font-weight: 500;
        }
        .msg-error-input {
            font-size: 12px;
            font-style: italic;
            text-decoration: none;
            color: black;
        }
    </style>
</head>
<body>
    <div class="content">
        <?php $this->load->view('others/navbar'); ?>
        <h2 class="text-center" style="font-weight:600;">Add New Visit</h2>
        <a class="btn btn-dark mb-4 rounded-pill py-3 px-4" href="/historycontroller"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <?php $this->load->view('add_visit/form'); ?>
        <hr>
    </div>
</body>
</html>