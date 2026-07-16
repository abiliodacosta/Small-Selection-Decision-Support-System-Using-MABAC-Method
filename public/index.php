<?php
session_start();
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/app.php';
require_once ROOT_PATH . '/app/Helpers/functions.php';

// Simple Router
$url = isset($_GET['url']) && $_GET['url'] !== '' ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);

if ($url === '') {
    $urlParts = isset($_SESSION['user_id']) ? ['dashboard'] : ['auth'];
} else {
    $urlParts = explode('/', $url);
}

$controllerName = ucfirst($urlParts[0]) . 'Controller';
$methodName = isset($urlParts[1]) ? $urlParts[1] : 'index';

// Auth Middleware
if (strtolower($urlParts[0]) !== 'auth' && !isset($_SESSION['user_id'])) {
    redirect('');
}

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
