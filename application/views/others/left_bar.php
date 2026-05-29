<aside class="sidebar">
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
        <a href="/DetailController/add_visit" class="btn btn-success mb-2">
            <i class="fa-solid fa-circle-plus"></i> Add New Visit
        </a>
        <div class="nav-group-label">Overview</div>
        <a href="/DashboardController" class="nav-item active">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>
        <a href="/MapsController" class="nav-item">
            <i class="fa-solid fa-map"></i> Maps
        </a>
        <a href="/GlobalListController" class="nav-item">
            <i class="fa-solid fa-folder-open"></i> Collections
        </a>
        <a href="/ListController" class="nav-item">
            <i class="fa-solid fa-list"></i> List
        </a>
        <a href="/HistoryController" class="nav-item">
            <i class="fa-solid fa-clock-rotate-left"></i> History
        </a>
        <a href="/TrackController" class="nav-item">
            <i class="fa-solid fa-route"></i> Track
        </a>
        <div class="nav-group-label mt-3">Analytics</div>
        <a href="/TrackController" class="nav-item">
            <i class="fa-solid fa-users"></i> Person
        </a>
        <a href="/TrackController" class="nav-item">
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