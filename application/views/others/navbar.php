<style>
    .navbar {
        margin: 0;
        margin-bottom: 20px !important;
        border-radius: 0 0 20px 20px;
        padding: 20px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        background: white;
    }
    .nav-item {
        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        transition: all 0.4s;
        border-bottom: 2px solid white;
        margin-right: 10px;
    }
    .nav-item:hover {
        border-bottom: 2px solid black;
    }
    .navbar-brand {
        font-weight: bold;
    }
    .nav-link.active {
        font-weight: 600;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Pinmarker</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="#">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">Maps</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Global-Collection</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">History</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Setting
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="#">My Profile</a></li>
                        <li><a class="dropdown-item" href="#">Help Center</a></li>
                        <li><a class="dropdown-item" href="#">Feedback</a></li>
                        <li><a class="dropdown-item" href="#">Sign Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>