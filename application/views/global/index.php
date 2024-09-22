<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinMarker | Global List</title>

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

    <!-- Javascript -->
    <script src="http://127.0.0.1:8080/public/js/global.js"></script>
    
    <!-- Jquery -->
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!--Full calendar.-->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

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
        <h2 class="text-center" style="font-weight:600;">Global List</h2><br>
        <a class="btn btn-success btn-main-page rounded-pill" href="/AddGlobalListController" id='add-global-list-btn'><i class="fa-solid fa-plus"></i> Add Global List</a>
        <?php $this->load->view('global/global_list'); ?>
    </div>

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
</body>
<?php $this->load->view('others/footer'); ?>
</html>