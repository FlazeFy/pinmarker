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