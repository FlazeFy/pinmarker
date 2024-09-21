<style>
    .navbar {
        margin: 0;
        margin-bottom: 20px !important;
        border-radius: 0 0 20px 20px;
        padding: 20px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        opacity: 1 !important;
        z-index: 999;
    }
    .nav-item {
        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        transition: all 0.4s;
        border-bottom: 2px solid transparent;
        margin-right: 10px;
    }
    .nav-item:hover {
        border-bottom: 2px solid white;
    }
    .navbar-brand {
        font-weight: bold;
    }
    .nav-link.active {
        font-weight: 600;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark position-sticky w-100" style="top: 0;">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><?php 
            if($is_mobile_device){
                echo ucfirst($active_page);
            } else {
                echo "PinMarker";
            }
        ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <?php 
                    if($is_signed){
                        echo '
                            <li class="nav-item">
                                <a class="nav-link '; if($active_page == 'dashboard'){ echo 'active'; } echo'" aria-current="page" href="/DashboardController">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link '; if($active_page == 'maps'){ echo 'active'; } echo'" href="/MapsController">Maps</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link '; if($active_page == 'global_list'){ echo 'active'; } echo'" href="/GlobalListController">Global-Collection</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link '; if($active_page == 'list'){ echo 'active'; } echo'" href="/ListController">List</a>
                            </li>';

                            if($this->session->userdata('role_key') == 1){
                                echo '
                                <li class="nav-item">
                                    <a class="nav-link '; if($active_page == 'history'){ echo 'active'; } echo'" href="/HistoryController">History</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link '; if($active_page == 'track'){ echo 'active'; } echo'" href="/TrackController">Track</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Setting
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <li><a class="dropdown-item '; if($active_page == 'myprofile'){ echo 'active'; } echo'" href="/MyProfileController">My Profile</a></li>
                                        <li><a class="dropdown-item" href="#">Help Center</a></li>
                                        <li><a class="dropdown-item '; if($active_page == 'feedback'){ echo 'active'; } echo'" href="/FeedbackController">Feedback</a></li>
                                        <li><a class="dropdown-item text-white" style="background:var(--dangerBG) !important;" data-bs-toggle="modal" data-bs-target="#signOutModal">Sign Out</a></li>
                                    </ul>
                                </li>
                                ';
                            } else {
                                echo '
                                <li class="nav-item">
                                    <a class="nav-link '; if($active_page == 'myprofile'){ echo 'active'; } echo'" href="/MyProfileController">Manage</a>
                                </li>';
                            }
                            
                    } else {
                        echo '
                            <li class="nav-item">
                                <a class="nav-link" href="/LoginController#login-section">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link '; if($active_page == 'global_list'){ echo 'active'; } echo'" href="/DetailGlobalController/view/'; echo $this->session->userdata('search_global_id'); echo'">Global-Collection</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link '; if($active_page == 'feedback'){ echo 'active'; } echo'" aria-current="page" href="/FeedbackController">Feedback</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/RegisterController">Register</a>
                            </li>
                        ';
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Signout Modal -->
<div class="modal fade" id="signOutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content shadow" style="border-radius:15px; border: 3px solid black;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sign Out</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure want to leave this app?</p>
            </div>
            <div class="modal-footer">
                <form action="/LoginController/logout" method="POST">
                    <button type="submit" class="btn btn-danger rounded-pill px-3 py-2"><i class="fa-regular fa-circle-xmark"></i> Yes, Sign Out</button>
                </form>
            </div>
        </div>
    </div>
</div>