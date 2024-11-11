<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinMarker | Track</title>
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

    <!-- Javascript -->
    <script src="http://127.0.0.1:8080/public/js/global.js"></script>

    <!-- Jquery -->
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!--Full calendar.-->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

    <!-- Jquery DataTables -->
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap dataTables Javascript -->
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <!-- Swal -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript" charset="utf-8">
        $(document).ready(function () {
            $('#tb_history_track').DataTable();
        });
    </script>
</head>
<body>
    <div class="content">
        <?php $this->load->view('others/navbar'); ?>
        <h2 class="text-center" style="font-weight:600;">Tracking <?php if($this->session->userdata('filter_date_track') == null){ echo '<span class="btn bg-danger text-white px-3 py-1" style="font-size:16px; margin-top:-6px;"><i class="fa-solid fa-satellite-dish"></i> Now Live</span>'; }?></h2><br>
        <?php if (!$is_mobile_device): ?>
            <div class="d-flex justify-content-start">
                <a class="btn btn-dark mb-4 py-3 px-4 me-2" data-bs-toggle="modal" data-bs-target="#historyTrackModal" onclick="getHistoryTrack()" id='detail-track-btn'><i class="fa-solid fa-table"></i> Detail</a>
                <a class="btn btn-dark mb-4 py-3 px-4 me-2" data-bs-toggle="modal" data-bs-target="#relatedPinTrackModal" id='related-track-btn'><i class="fa-solid fa-table"></i> Related Pin x Track</a>
                <form action="TrackController/reset_filter_date" method="POST">
                    <button class="btn btn-danger py-3 px-4 me-2 mb-4" id='reset-filter-btn'><i class="fa-solid fa-rotate"></i> Reset</button>
                </form>
                <?php $this->load->view('track/day_route'); ?>
                <?php $this->load->view('track/view_mode'); ?>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-4 p-0 ps-2">
                    <a class="btn btn-dark mb-2 p-2 w-100" data-bs-toggle="modal" data-bs-target="#historyTrackModal" onclick="getHistoryTrack()" id='detail-track-btn'><i class="fa-solid fa-table"></i> Detail</a>
                </div>
                <div class="col-5 px-1">
                    <a class="btn btn-dark mb-2 p-2 w-100" data-bs-toggle="modal" data-bs-target="#relatedPinTrackModal" id='related-track-btn'><i class="fa-solid fa-table"></i> Pin x Track</a>
                </div>
                <div class="col-2 p-0">
                    <form action="TrackController/reset_filter_date" method="POST">
                        <button class="btn btn-danger p-2 mb-2 w-100" id='reset-filter-btn'><i class="fa-solid fa-rotate"></i></button>
                    </form>
                </div>
                <div class="col-6">
                    <?php $this->load->view('track/day_route'); ?>
                </div>
                <div class="col-6">
                    <?php $this->load->view('track/view_mode'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php $this->load->view('track/history_track'); ?>
        <?php $this->load->view('track/related_pin_track'); ?>
        <?php $this->load->view('track/maps_board'); ?>
    </div>
    <?php $this->load->view('others/footer'); ?>
</body>
</html>