<?php 
    $this->load->helper('url'); 
    $full_url = current_url();
    $path = parse_url($full_url, PHP_URL_PATH);
    $segments = explode('/', trim($path, '/'));
    $cleanedUrl = $segments[0] ?? null;   
?>

<button class="sidebar-toggle-btn d-lg-none" id="sidebarToggleBtn">
    <i class="fa-solid fa-bars"></i>
</button>

<div class="sidebar-overlay d-none" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-logo">
            <i class="fa-solid fa-map-pin"></i>
        </div>
        <div>
            <a class="sidebar-title pointer" href="/LandingController">PinMarker</a>
            <div class="sidebar-sub">Marks Your World</div>
        </div>
    </div>
    <nav class="sidebar-nav flex-grow-1">
        <a href="/AddVisitController" class="btn btn-success mb-2">
            <i class="fa-solid fa-circle-plus"></i> Add Visit
        </a>
        <div class="nav-group-label">Overview</div>
        <a href="/DashboardController" class="nav-item <?= ($cleanedUrl === "DashboardController") ? "active" : ""; ?>">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>
        <a href="/MapsController" class="nav-item <?= ($cleanedUrl === "MapsController") ? "active" : ""; ?>">
            <i class="fa-solid fa-map"></i> Maps
        </a>
        <a href="/GlobalListController" class="nav-item <?= ($cleanedUrl === "GlobalListController") ? "active" : ""; ?>">
            <i class="fa-solid fa-folder-open"></i> Collections
        </a>
        <a href="/ListController" class="nav-item <?= ($cleanedUrl === "ListController" || $cleanedUrl === "DetailController") ? "active" : ""; ?>">
            <i class="fa-solid fa-list"></i> My Marker
        </a>
        <a href="/HistoryController" class="nav-item <?= (in_array($cleanedUrl, ["HistoryController", "AddVisitController"])) ? "active" : ""; ?>">
            <i class="fa-solid fa-clock-rotate-left"></i> History
        </a>
        <a href="/TrackController" class="nav-item">
            <i class="fa-solid fa-route"></i> Track
        </a>
        <div class="nav-group-label mt-3">Analytics</div>
        <a href="/SuggestionController" class="nav-item">
            <i class="fa-solid fa-robot"></i> Trip Suggestion
        </a>
        <a href="/PersonController" class="nav-item <?= ($cleanedUrl === "PersonController") ? "active" : ""; ?>">
            <i class="fa-solid fa-users"></i> Person
        </a>
        <a href="/ScheduleController" class="nav-item <?= ($cleanedUrl === "ScheduleController") ? "active" : ""; ?>">
            <i class="fa-solid fa-clock"></i> Schedule
        </a>
        <div class="nav-group-label mt-3">Account</div>
        <a href="/MyProfileController" class="nav-item">
            <i class="fa-solid fa-gear"></i> Settings
        </a>
    </nav>
    <div class="sidebar-bottom">
        <a href="/LoginController/logout" class="nav-item nav-logout mt-2">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
    </div>
</aside>

<script>
    $(() => {
        const $sidebar = $('#sidebar')
        const $overlay = $('#sidebarOverlay')
        const $toggleBtn = $('#sidebarToggleBtn')

        const openSidebar = () => {
            $sidebar.addClass('sidebar-open')
            $toggleBtn.addClass('bg-danger').html('<i class="fa-solid fa-xmark"></i>')
            $overlay.removeClass('d-none')
        }

        const closeSidebar = () => {
            $sidebar.removeClass('sidebar-open')
            $toggleBtn.removeClass('bg-danger').html('<i class="fa-solid fa-bars"></i>')
            $overlay.addClass('d-none')
        }

        $toggleBtn.on('click', () => {
            $sidebar.hasClass('sidebar-open') ? closeSidebar() : openSidebar()
        })

        $overlay.on('click', () => closeSidebar())

        $(window).on('resize', () => {
            if ($(window).width() >= 992) closeSidebar()
        })
    })
</script>