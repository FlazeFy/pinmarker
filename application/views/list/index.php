<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinMarker | List</title>

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

    <!-- Swal -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <div>
                <a class="btn btn-dark rounded-pill btn-main-page" href="/AddController"><i class="fa-solid fa-plus"></i> Add New Marker</a>
                    <?php 
                        if($this->session->userdata('is_catalog_view') == false && !$this->session->userdata('open_pin_list_category')){
                            echo "
                            <form class='d-inline' method='POST' action='/ListController/view_toogle'>
                                <button class='btn btn-dark rounded-pill btn-main-page'><i class='fa-solid fa-folder-open'></i> Catalog View</button>
                            </form>";
                        } else if(!$this->session->userdata('open_pin_list_category')) {
                            echo "
                            <form class='d-inline' method='POST' action='/ListController/view_toogle'>
                                <button class='btn btn-dark rounded-pill btn-main-page'><i class='fa-solid fa-list'></i> List View</button>
                            </form>";
                        } else {
                            echo "
                            <form class='d-inline' method='POST' action='/ListController/view_catalog_detail/back'>
                                <button class='btn btn-dark rounded-pill btn-main-page'><i class='fa-solid fa-arrow-left'></i> Back</button>
                            </form>";
                        }
                    ?>
                <a class="btn btn-dark rounded-pill btn-main-page" href="/ListController/print_pin"><i class="fa-solid fa-print"></i> Print</a>
                <a class="btn btn-dark rounded-pill btn-main-page" data-bs-target="#manageCategory" data-bs-toggle="modal"><i class="fa-solid fa-gear"></i> Manage Category</a>
                <?php $this->load->view('list/manage_category'); ?>
                <?php
                    if($is_mobile_device){
                        echo '<a class="btn btn-dark rounded-pill btn-main-page" href="/TrashController"><i class="fa-solid fa-trash"></i> Trash</a>';
                    }
                ?>
            </div>
            <?php
                if(!$is_mobile_device){
                    echo '<div>
                        <a class="btn btn-dark rounded-pill btn-main-page" href="/TrashController"><i class="fa-solid fa-trash"></i> Trash</a>
                    </div>';
                }
            ?>
        </div>
        <?php $this->load->view('list/list'); ?>
        <hr>
    </div>
    <?php $this->load->view('others/footer'); ?>
</body>
</html>