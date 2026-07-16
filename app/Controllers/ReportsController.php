<?php
class ReportsController {
    private $db;
    public function __construct() {
        if (!isset($_SESSION['user_id'])) redirect('auth');
        require_once ROOT_PATH . '/app/Models/Database.php';
        $this->db = new Database();
    }
    public function index() {
        require_once ROOT_PATH . '/app/Controllers/MabacController.php';
        $mabac = new MabacController();
        $data = $mabac->getMabacData();
        
        $results = $data['ranking'];
        $labels = [];
        $chartData = [];
        
        foreach($results as $r) {
            $labels[] = $r['candidate']['name'];
            $chartData[] = $r['score'];
        }
        
        view('layouts/header', ['title' => 'MABAC Reports']);
        view('reports/index', [
            'results' => $results, 
            'labels' => json_encode($labels),
            'chartData' => json_encode($chartData)
        ]);
        view('layouts/footer');
    }
}
