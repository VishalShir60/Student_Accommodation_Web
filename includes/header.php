<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? $_SESSION['user_name'] : '';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentStay | Find Your Perfect Student Accommodation & PG</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- React 18 & Babel CDN (For React Component Integration) -->
    <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top bg-white border-bottom shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <div class="brand-icon">
                    <i class="fa-solid fa-building-user"></i>
                </div>
                <span class="bg-gradient text-primary">Student<span class="text-dark">Stay</span></span>
                <span class="badge bg-light text-secondary border ms-2 d-none d-lg-inline-block font-semibold small" style="font-size: 0.72rem;">
                    <i class="fa-solid fa-code text-primary me-1"></i> Developed by Vishal Shirsath
                </span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'index.php' || $current_page === '') ? 'active' : '' ?>" href="index.php">
                            <i class="fa-solid fa-compass me-1 text-primary"></i> Explore PGs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'shortlist.php') ? 'active' : '' ?> position-relative" href="shortlist.php">
                            <i class="fa-solid fa-heart me-1 text-danger"></i> Shortlist
                            <span id="nav-shortlist-badge" class="badge rounded-pill bg-danger ms-1" style="display: none;">0</span>
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <?php if ($is_logged_in): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle rounded-pill px-3 font-semibold" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-circle-user me-1"></i> <?= htmlspecialchars($user_name) ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3 border-0 mt-2">
                                <li>
                                    <a class="dropdown-item py-2" href="shortlist.php">
                                        <i class="fa-solid fa-heart me-2 text-danger"></i> My Shortlist
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item py-2 text-danger" href="logout.php">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Log Out
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Log In
                        </a>
                        <a href="signup.php" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                            <i class="fa-solid fa-user-plus me-1"></i> Sign Up
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
