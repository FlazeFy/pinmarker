<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title_page ?? ''; ?></title>
    <link rel="icon" type="image/png" href="http://127.0.0.1:8080/public/images/logo_white.png"/>

    <?php 
        $this->load->helper('url'); 
        $full_url = current_url();
        $cleanedUrl = str_replace("http://127.0.0.1:8080/", "", $full_url);    
    ?>

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
    
    <?php if(preg_match('(DetailController|GlobalListController|GlobalMapsController|AddController|AddVisitController|DetailVisitController|MapsController|TrackController|DetailPersonController)', $cleanedUrl)): ?>
        <!-- Maps CSS -->
        <link href="http://127.0.0.1:8080/public/css/maps.css" rel="stylesheet"/>
    <?php endif; ?>

    <?php if(preg_match('(LoginController|GlobalListController|DetailGlobalController|DetailPersonController)', $cleanedUrl)): ?>
        <!-- Pin CSS -->
        <link href="http://127.0.0.1:8080/public/css/pin.css" rel="stylesheet"/>
    <?php endif; ?>

    <?php if(preg_match('(RegisterController|ForgetController)', $cleanedUrl)): ?>
        <!-- Register & Forget Pass CSS -->
        <link href="http://127.0.0.1:8080/public/css/register.css" rel="stylesheet"/>
    <?php endif; ?>

    <link href="http://127.0.0.1:8080/public/css/button.css" rel="stylesheet"/>
    <link href="http://127.0.0.1:8080/public/css/form.css" rel="stylesheet"/>
    <link href="http://127.0.0.1:8080/public/css/navbar.css" rel="stylesheet"/>
    
    <!-- Jquery -->
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- Swal -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="http://127.0.0.1:8080/public/js/global.js"></script>

    <?php if(preg_match('(GlobalController|ListController|LoginController)', $cleanedUrl)): ?>
        <!-- Isotope JS -->
        <script src="http://127.0.0.1:8080/public/js/isotope.js"></script>
    <?php endif; ?>

    <?php if(preg_match('(DetailController|GlobalListController|GlobalMapsController|AddController|AddVisitController|DetailVisitController|MapsController|TrackController|DetailPersonController)', $cleanedUrl)): ?>
        <!-- Maps JS -->
        <script src="http://127.0.0.1:8080/public/js/maps.js"></script>
    <?php endif; ?>

    <?php if(preg_match('(DashboardController|MyProfileController|DetailController|DetailPersonController|PersonController)', $cleanedUrl)): ?>
        <!--Apex Chart-->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <?php endif; ?>

    <?php if(preg_match('(LoginController|ListController|GlobalListController|DetailGlobalController|MyProfileController)', $cleanedUrl)): ?>
        <!-- Isotope -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
        <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
    <?php endif; ?>

    <?php if($cleanedUrl == 'HistoryController'): ?>
        <!--Full calendar.-->
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <?php endif; ?>
</head>
<body>
    <?php if(preg_match('(DetailController|GlobalListController|GlobalMapsController|AddController|AddVisitController|DetailVisitController|MapsController|TrackController|DetailPersonController)', $cleanedUrl)): ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>
    <?php endif; ?>

    <div class="content">
        <?php preg_match('(LoginController|RegisterController|ForgetController|GlobalMapsController)', $cleanedUrl) ? null : $this->load->view('others/navbar'); ?>
        <?php echo $content ?? ''; ?>
    </div>
    <?php 
        if($this->session->flashdata('message_success')){
            echo "
                <script>
                    Swal.fire({
                        title: 'Success!',
                        html: '".$this->session->flashdata('message_success')."',
                        icon: 'success'
                    });
                </script>
            ";
        }
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
    ?>

    <script>
        $( document ).ready(function() {
            const date_holder = document.querySelectorAll('.date-target');

            date_holder.forEach(e => {
                const date = new Date(e.textContent);
                e.textContent = getDateToContext(e.textContent, "calendar");
            });
        });
    </script>

    <?php $this->load->view('others/footer'); ?>
</body>
</html>