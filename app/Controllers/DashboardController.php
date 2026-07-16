<?php
class DashboardController {
    private $db;
    public function __construct() {
        if (!isset($_SESSION['user_id'])) redirect('auth');
        require_once ROOT_PATH . '/app/Models/Database.php';
        $this->db = new Database();
    }
    
    public function index() {
        // Fetch real stats
        $this->db->query("SELECT COUNT(*) as total FROM candidates");
        $total_candidates = $this->db->single()['total'];
        
        $this->db->query("SELECT COUNT(*) as total FROM criteria");
        $total_criteria = $this->db->single()['total'];
        
        $this->db->query("SELECT COUNT(DISTINCT candidate_id) as total FROM evaluations");
        $total_evaluated = $this->db->single()['total'];

        // Fetch criteria for chart
        $this->db->query("SELECT code, name, weight FROM criteria ORDER BY code ASC");
        $criteria = $this->db->resultSet();

        // Fetch MABAC Ranking Data
        require_once ROOT_PATH . '/app/Controllers/MabacController.php';
        $mabac = new MabacController();
        $mabacData = $mabac->getMabacData();
        $ranking = $mabacData['ranking'];
        
        view('layouts/header', ['title' => 'Dashboard']);
        view('dashboard/index', [
            'total_candidates' => $total_candidates,
            'total_criteria' => $total_criteria,
            'total_evaluated' => $total_evaluated,
            'criteria' => $criteria,
            'ranking' => $ranking
        ]);
        view('layouts/footer');
    }
}
