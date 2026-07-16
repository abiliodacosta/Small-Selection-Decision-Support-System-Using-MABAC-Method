<?php
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
