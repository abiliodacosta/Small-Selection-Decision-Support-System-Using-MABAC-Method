<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
</head>
<body>
<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <h3 class="fw-bold text-primary m-0"><i class="fas fa-cube me-2"></i>MABAC DSS</h3>
        </div>
        <ul class="list-unstyled components">
            <li><a href="<?= APP_URL ?>/dashboard"><i class="fas fa-home me-3"></i> Dashboard</a></li>
            <li><a href="<?= APP_URL ?>/candidates"><i class="fas fa-users me-3"></i> Candidates</a></li>
            <li><a href="<?= APP_URL ?>/criteria"><i class="fas fa-list me-3"></i> Criteria</a></li>
            <li><a href="<?= APP_URL ?>/evaluations"><i class="fas fa-star me-3"></i> Evaluations</a></li>
            <li>
                <?php $isMabac = strpos($_SERVER['REQUEST_URI'], '/mabac/matrix') !== false; ?>
                <a href="#mabacSubmenu" data-bs-toggle="collapse" aria-expanded="<?= $isMabac ? 'true' : 'false' ?>" class="dropdown-toggle <?= $isMabac ? '' : 'collapsed' ?>">
                    <i class="fas fa-table me-3"></i> Matris MABAC
                </a>
                <ul class="collapse list-unstyled <?= $isMabac ? 'show' : '' ?>" id="mabacSubmenu">
                    <li><a href="<?= APP_URL ?>/mabac/matrix/x" class="ps-5 <?= strpos($_SERVER['REQUEST_URI'], '/mabac/matrix/x') !== false ? 'text-warning fw-bold' : '' ?>">Matrix X</a></li>
                    <li><a href="<?= APP_URL ?>/mabac/matrix/n" class="ps-5 <?= strpos($_SERVER['REQUEST_URI'], '/mabac/matrix/n') !== false ? 'text-warning fw-bold' : '' ?>">Matrix Normalizasaun</a></li>
                    <li><a href="<?= APP_URL ?>/mabac/matrix/v" class="ps-5 <?= strpos($_SERVER['REQUEST_URI'], '/mabac/matrix/v') !== false ? 'text-warning fw-bold' : '' ?>">Matriks Terbobot V</a></li>
                    <li><a href="<?= APP_URL ?>/mabac/matrix/g" class="ps-5 <?= strpos($_SERVER['REQUEST_URI'], '/mabac/matrix/g') !== false ? 'text-warning fw-bold' : '' ?>">Matrix G</a></li>
                    <li><a href="<?= APP_URL ?>/mabac/matrix/q" class="ps-5 <?= strpos($_SERVER['REQUEST_URI'], '/mabac/matrix/q') !== false ? 'text-warning fw-bold' : '' ?>">Matrix Q = V - G</a></li>
                </ul>
            </li>
            <li><a href="<?= APP_URL ?>/mabac/ranking"><i class="fas fa-trophy me-3"></i> Ranking</a></li>
            <li><a href="<?= APP_URL ?>/reports"><i class="fas fa-file-alt me-3"></i> Reports</a></li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content" class="main-content">
        <nav class="topbar">
            <div>
                <button type="button" id="sidebarCollapse" class="btn btn-primary">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="ms-3 fw-bold fs-5"><?= $title ?? 'Dashboard' ?></span>
            </div>
            <div class="d-flex align-items-center">
                <button id="darkModeToggle" class="btn btn-outline-secondary rounded-circle me-3">
                    <i class="fas fa-moon"></i>
                </button>
                
                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle rounded-pill px-3 fw-bold border-0 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle text-primary me-2 fs-5 align-middle"></i>
                        <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li>
                            <a class="dropdown-item text-danger fw-bold py-2" href="<?= APP_URL ?>/auth/logout">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <div class="container-fluid p-4">
            <!-- Toast Container for Notifications -->
            <div class="toast-container position-fixed top-0 end-0 p-3">
                <?php if(isset($_SESSION['msg'])): ?>
                <div class="toast align-items-center text-bg-<?= $_SESSION['msg_type'] ?? 'success' ?> border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                  <div class="d-flex">
                    <div class="toast-body">
                      <?= $_SESSION['msg'] ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                  </div>
                </div>
                <?php unset($_SESSION['msg']); unset($_SESSION['msg_type']); endif; ?>
            </div>
