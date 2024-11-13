<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinMarker | Custom</title>
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

    <!-- Richtext -->
    <link rel="stylesheet" href="http://127.0.0.1:8080/public/richtexteditor/rte_theme_default.css" />
    <script type="text/javascript" src="http://127.0.0.1:8080/public/richtexteditor/rte.js"></script>
    <script type="text/javascript" src="http://127.0.0.1:8080/public/richtexteditor/rte-upload.js"></script>
    <script type="text/javascript" src="http://127.0.0.1:8080/public/richtexteditor/plugins/all_plugins.js"></script>

    <!-- Swal -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>
    <div class="content">
        <?php $this->load->view('others/navbar'); ?>
        <a class="btn btn-danger mb-4 py-3 px-4 me-2" href="/DetailController/view/<?= $id ?>" id="back-page-btn"><i class="fa-solid fa-arrow-left"></i><?php if (!$is_mobile_device){ echo " Back"; } ?></a>
        <?php $this->load->view('custom_doc/workarea'); ?>

        <?php 
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