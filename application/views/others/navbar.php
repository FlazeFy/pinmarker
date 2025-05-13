<style>
    .navbar {
        margin: 0;
        margin-bottom: 20px !important;
        border-radius: 0 0 20px 20px;
        padding: 20px;
        opacity: 1 !important;
        z-index: 999;
        background: var(--whiteColor);
    }
    .nav-item {
        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        transition: all 0.4s;
        border-bottom: 2px solid transparent;
        margin-right: var(--spaceLG);
    }
    .nav-link {
        color: var(--secondaryColor) !important;
        font-weight: 500;
        padding: var(--spaceSM) var(--spaceMD) !important;
        border-radius: var(--roundedMD);
        letter-spacing: 0.075em;
    }
    .nav-item:hover {
        color: var(--primaryColor);
        border-bottom: 2px solid var(--primaryColor);
    }
    .navbar-brand {
        font-weight: 800;
        font-size: var(--textXJumbo);
        letter-spacing: 0.1em;
        color: var(--secondaryColor);
    }
    .nav-link.active {
        font-weight: 600;
        color: var(--primaryColor);
        border: 2px solid var(--primaryColor);
    }
</style>

<nav class="navbar navbar-expand-lg position-sticky w-100" style="top: 0;">
    <div class="container-fluid d-flex justify-content-between p-0" style="box-shadow:none;">
        <a class="navbar-brand" href="#"><?php 
            if($is_mobile_device){
                echo ucfirst($active_page);
            } else {
                echo "PinMarker";
            }
        ?></a>
        <ul class="navbar-nav">
            <?php 
                if($is_signed){
                    echo '
                        <li class="nav-item">
                            <a class="nav-link '; if($active_page == 'dashboard'){ echo 'active'; } echo'" aria-current="page" href="/DashboardController" id="dashboard-page-btn">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link '; if($active_page == 'maps'){ echo 'active'; } echo'" href="/MapsController" id="maps-page-btn">Maps</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link '; if($active_page == 'global_list'){ echo 'active'; } echo'" href="/GlobalListController" id="global-page-btn">Global-Collection</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link '; if($active_page == 'list'){ echo 'active'; } echo'" href="/ListController" id="list-page-btn">List</a>
                        </li>';

                        if($this->session->userdata('role_key') == 1){
                            echo '
                            <li class="nav-item">
                                <a class="nav-link '; if($active_page == 'history'){ echo 'active'; } echo'" href="/HistoryController" id="history-page-btn">History</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link '; if($active_page == 'track'){ echo 'active'; } echo'" href="/TrackController" id="track-page-btn">Track</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="setting-menu-btn" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Setting
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="setting-menu-btn">
                                    <li><a class="dropdown-item '; if($active_page == 'myprofile'){ echo 'active'; } echo'" href="/MyProfileController">My Profile</a></li>
                                    <li><a class="dropdown-item '; if($active_page == 'help'){ echo 'active'; } echo'" href="/HelpController">Help Center</a></li>
                                    <li><a class="dropdown-item '; if($active_page == 'feedback'){ echo 'active'; } echo'" href="/FeedbackController">Feedback</a></li>
                                </ul>
                            </li>
                            ';
                        } else {
                            echo '
                            <li class="nav-item">
                                <a class="nav-link '; if($active_page == 'myprofile'){ echo 'active'; } echo'" href="/MyProfileController" id="manage-page-btn">Manage</a>
                            </li>';
                        }
                        
                } else {
                    echo '
                        <li class="nav-item">
                            <a class="nav-link" href="/LoginController#login-section" id="login-page-btn">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link '; if($active_page == 'global_list'){ echo 'active'; } echo'" href="/DetailGlobalController/view/'; echo $this->session->userdata('search_global_id'); echo'" id="global-page-btn">Global-Collection</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link '; if($active_page == 'feedback'){ echo 'active'; } echo'" aria-current="page" href="/FeedbackController" id="feedback-page-btn">Feedback</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/RegisterController" id="register-page-btn">Register</a>
                        </li>
                    ';
                }
            ?>
        </ul>
        <a class="btn btn-danger text-white" style="background:var(--dangerBG) !important;" data-bs-toggle="modal" data-bs-target="#signOutModal"><i class="fa-regular fa-circle-xmark"></i> Sign Out</a>
    </div>
</nav>

<!-- Signout Modal -->
<div class="modal fade" id="signOutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content shadow" style="border-radius:15px; border: 3px solid black;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sign Out</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id='close-sign-out-modal-btn'></button>
            </div>
            <div class="modal-body">
                <p>Are you sure want to leave this app?</p>
            </div>
            <div class="modal-footer">
                <form action="/LoginController/logout" method="POST">
                    <button type="submit" class="btn btn-danger px-3 py-2" id='submit-sign-out-btn'><i class="fa-regular fa-circle-xmark"></i> Yes, Sign Out!</button>
                </form>
            </div>
        </div>
    </div>
</div>