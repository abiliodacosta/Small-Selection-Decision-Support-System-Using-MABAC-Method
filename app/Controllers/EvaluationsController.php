<?php
class EvaluationsController {
    private $db;
    public function __construct() {
        if (!isset($_SESSION['user_id'])) redirect('auth');
        require_once ROOT_PATH . '/app/Models/Database.php';
        $this->db = new Database();
    }
    
    public function index() {
        // Get all criteria
        $this->db->query("SELECT * FROM criteria ORDER BY code ASC");
        $criteria = $this->db->resultSet();
        
        // Get evaluated candidates
        $this->db->query("SELECT DISTINCT c.* FROM candidates c JOIN evaluations e ON c.id = e.candidate_id ORDER BY c.id ASC");
        $evaluated_candidates = $this->db->resultSet();
        
        // Get unevaluated candidates
        $this->db->query("SELECT * FROM candidates WHERE id NOT IN (SELECT DISTINCT candidate_id FROM evaluations) ORDER BY name ASC");
        $unevaluated_candidates = $this->db->resultSet();
        
        // Get all evaluations mapped
        $this->db->query("SELECT * FROM evaluations");
        $evals_raw = $this->db->resultSet();
        $evals = [];
        foreach($evals_raw as $e) {
            $evals[$e['candidate_id']][$e['criterion_id']] = $e['score'];
        }
        
        view('layouts/header', ['title' => 'Scores & Evaluations']);
        view('evaluations/index', [
            'evaluated_candidates' => $evaluated_candidates,
            'unevaluated_candidates' => $unevaluated_candidates,
            'criteria' => $criteria,
            'evals' => $evals
        ]);
        view('layouts/footer');
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $candidate_id = $_POST['candidate_id'];
            $scores = $_POST['scores'] ?? []; // format: scores[criterion_id] = value
            
            foreach($scores as $crit_id => $val) {
                if($val !== '') {
                    $this->db->query("INSERT INTO evaluations (candidate_id, criterion_id, score) VALUES (:cid, :crid, :score)");
                    $this->db->bind(':cid', $candidate_id); 
                    $this->db->bind(':crid', $crit_id); 
                    $this->db->bind(':score', $val);
                    $this->db->execute();
                }
            }
            $_SESSION['msg'] = "Evaluation added successfully!";
            $_SESSION['msg_type'] = "success";
            redirect('evaluations');
        }
    }
    
    public function update($candidate_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $scores = $_POST['scores'] ?? [];
            
            foreach($scores as $crit_id => $val) {
                if($val !== '') {
                    $this->db->query("SELECT id FROM evaluations WHERE candidate_id = :cid AND criterion_id = :crid");
                    $this->db->bind(':cid', $candidate_id); $this->db->bind(':crid', $crit_id);
                    $exists = $this->db->single();
                    if($exists) {
                        $this->db->query("UPDATE evaluations SET score = :score WHERE id = :id");
                        $this->db->bind(':score', $val); $this->db->bind(':id', $exists['id']);
                        $this->db->execute();
                    } else {
                        $this->db->query("INSERT INTO evaluations (candidate_id, criterion_id, score) VALUES (:cid, :crid, :score)");
                        $this->db->bind(':cid', $candidate_id); $this->db->bind(':crid', $crit_id); $this->db->bind(':score', $val);
                        $this->db->execute();
                    }
                }
            }
            $_SESSION['msg'] = "Evaluation updated successfully!";
            $_SESSION['msg_type'] = "success";
            redirect('evaluations');
        }
    }
    
    public function delete($candidate_id) {
        $this->db->query("DELETE FROM evaluations WHERE candidate_id = :cid");
        $this->db->bind(':cid', $candidate_id);
        $this->db->execute();
        
        $_SESSION['msg'] = "Evaluation deleted successfully!";
        $_SESSION['msg_type'] = "success";
        redirect('evaluations');
    }
}
