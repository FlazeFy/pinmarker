<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinMarker | My Profile</title>
    <link rel="icon" type="image/png" href="http://127.0.0.1:8080/public/images/logo_white.png"/>

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
    <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>

    <!--Apex Chart-->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Swal -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="http://127.0.0.1:8080/public/js/global.js"></script>
    <link href="http://127.0.0.1:8080/public/css/global.css" rel="stylesheet"/>
</head>
<body>
    <div class="content">
        <?php $this->load->view('others/navbar'); ?>
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12">
                <?php 
                    if($this->session->userdata('role_key') == 1){
                        $this->load->view('myprofile/edit_image'); 
                    } else {
                        echo "<h4>Profile</h4><hr>";
                    }
                ?>
                <?php $this->load->view('myprofile/profile'); ?>
            </div>
            <div class="col-lg-9 col-md-12 col-sm-12">
                <?php 
                    if($this->session->userdata('role_key') == 1){
                        $this->load->view('myprofile/visit_activity');
                        $this->load->view('myprofile/date_visit');
                        $this->load->view('myprofile/track_distance_hourly'); 
                        $this->load->view('myprofile/my_gallery');
                    } else {
                        echo "
                            <nav class='nav sub-tab'>
                                <div class='nav-item active' id='user-manage-section-btn-holder'>
                                    <a aria-current='page' id='user-manage-section-btn'>User Manage</a>
                                </div>
                                <div class='nav-item' id='dct-section-btn-holder'>
                                    <a aria-current='page' id='dct-section-btn'>Dictionary</a>
                                </div>
                                <div class='nav-item' id='gallery-section-btn-holder'>
                                    <a aria-current='page' id='gallery-section-btn'>Gallery</a>
                                </div>
                                <div class='nav-item' id='feedback-section-btn-holder'>
                                    <a aria-current='page' id='feedback-section-btn'>Feedback</a>
                                </div>
                                <div class='nav-item' id='help-section-btn-holder'>
                                    <a aria-current='page' id='help-section-btn'>Help Center</a>
                                </div>
                                <div class=''>
                                    <a aria-current='page' class='btn text-white btn-danger py-2' id='sign-out-btn' style='border-radius:var(--roundedLG);' data-bs-toggle='modal' data-bs-target='#signOutModal'><i class='fa-regular fa-circle-xmark'></i> Sign Out</a>
                                </div>
                            </nav>
                            <div id='user_manage_section'>";
                                $this->load->view('myprofile/user_manage'); 
                                echo "
                            </div>
                            <div id='dct_manage_section' class='d-none'>";
                                $this->load->view('myprofile/dct_manage'); 
                                echo "
                            </div>
                            <div id='feedback_manage_section' class='d-none'>";
                                $this->load->view('myprofile/feedback_manage'); 
                                echo "
                            </div>
                        ";
                    }
                ?>
            </div>
        </div>
        <hr>
    </div>
    <?php $this->load->view('others/footer'); ?>
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
    <script>
        $(document).on('click', '#user-manage-section-btn-holder,#dct-section-btn-holder,#feedback-section-btn-holder', function() {  
            $('.nav-item').removeClass('active')
            $(this).closest('.nav-item').addClass('active')

            if(this.id == 'user-manage-section-btn-holder'){
                $('#user_manage_section').removeClass().css({'display':'block'})
                $('#dct_manage_section').removeClass().css({'display':'none'})
                $('#feedback_manage_section').removeClass().css({'display':'none'})
            } else if(this.id == 'dct-section-btn-holder'){
                $('#user_manage_section').removeClass().css({'display':'none'})
                $('#dct_manage_section').removeClass().css({'display':'block'})
                $('#feedback_manage_section').removeClass().css({'display':'none'})
            } else if(this.id == 'feedback-section-btn-holder'){
                $('#user_manage_section').removeClass().css({'display':'none'})
                $('#dct_manage_section').removeClass().css({'display':'none'})
                $('#feedback_manage_section').removeClass().css({'display':'block'})
            }
        })
    </script>
</body>
</html>