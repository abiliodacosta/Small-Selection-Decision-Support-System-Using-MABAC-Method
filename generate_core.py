import os

base_dir = 'c:/xampp/htdocs/app-dss'

files_content = {
    'public/.htaccess': '''RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]''',
    
    'public/index.php': '''<?php
session_start();
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/app.php';
require_once ROOT_PATH . '/app/Helpers/functions.php';

// Simple Router
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'dashboard';
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlParts = explode('/', $url);

$controllerName = ucfirst($urlParts[0]) . 'Controller';
$methodName = isset($urlParts[1]) ? $urlParts[1] : 'index';

$controllerFile = ROOT_PATH . '/app/Controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $methodName)) {
            call_user_func_array([$controller, $methodName], array_slice($urlParts, 2));
        } else {
            echo "Method not found";
        }
    } else {
        echo "Class not found";
    }
} else {
    echo "Controller not found";
}
''',
    
    'config/app.php': '''<?php
define('APP_NAME', 'DSS MABAC');
define('APP_URL', 'http://localhost/app-dss/public');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dss_mabac_db');
''',
    
    'app/Helpers/functions.php': '''<?php
function view($view, $data = []) {
    extract($data);
    $viewFile = ROOT_PATH . '/resources/views/' . $view . '.php';
    if (file_exists($viewFile)) {
        require_once $viewFile;
    } else {
        echo "View not found: " . $view;
    }
}

function redirect($url) {
    header('Location: ' . APP_URL . '/' . $url);
    exit;
}
''',
    
    'app/Controllers/DashboardController.php': '''<?php
class DashboardController {
    public function index() {
        view('layouts/header', ['title' => 'Dashboard']);
        view('dashboard/index');
        view('layouts/footer');
    }
}
''',
    
    'resources/views/layouts/header.php': '''<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; color: white; }
        .sidebar a { color: #ccc; text-decoration: none; padding: 10px 15px; display: block; }
        .sidebar a:hover { color: white; background-color: #343a40; }
        .main-content { padding: 20px; }
        .card { border-radius: 10px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar p-3" style="width: 250px;">
        <h4 class="mb-4">MABAC DSS</h4>
        <a href="<?= APP_URL ?>/dashboard"><i class="fas fa-home me-2"></i> Dashboard</a>
        <a href="<?= APP_URL ?>/candidates"><i class="fas fa-users me-2"></i> Candidates</a>
        <a href="<?= APP_URL ?>/criteria"><i class="fas fa-list me-2"></i> Criteria</a>
        <a href="<?= APP_URL ?>/evaluations"><i class="fas fa-star me-2"></i> Evaluations</a>
        <a href="<?= APP_URL ?>/dss"><i class="fas fa-calculator me-2"></i> MABAC DSS</a>
        <a href="<?= APP_URL ?>/reports"><i class="fas fa-file-alt me-2"></i> Reports</a>
    </div>
    <div class="main-content flex-grow-1">
''',
    
    'resources/views/layouts/footer.php': '''
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
''',
    
    'resources/views/dashboard/index.php': '''
<h2 class="mb-4">Dashboard</h2>
<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white text-center p-4">
            <h4>Total Candidates</h4>
            <h2>15</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white text-center p-4">
            <h4>Criteria</h4>
            <h2>5</h2>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-md-8">
        <div class="card p-3">
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['C1', 'C2', 'C3', 'C4', 'C5'],
                datasets: [{
                    label: 'Criteria Weights',
                    data: [30, 25, 20, 15, 10],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });
    });
</script>
'''
}

for path, content in files_content.items():
    full_path = os.path.join(base_dir, path)
    os.makedirs(os.path.dirname(full_path), exist_ok=True)
    with open(full_path, 'w', encoding='utf-8') as f:
        f.write(content)

print("Core files created.")
