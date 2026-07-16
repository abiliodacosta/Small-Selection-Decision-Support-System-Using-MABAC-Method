<?php
class DssController {
    public function index() {
        require_once ROOT_PATH . '/app/Models/Database.php';
        $db = new Database();
        
        $db->query("SELECT * FROM candidates");
        $candidates = $db->resultSet();
        
        $db->query("SELECT * FROM criteria ORDER BY code ASC");
        $criteria = $db->resultSet();
        
        $db->query("SELECT * FROM evaluations");
        $evals = $db->resultSet();
        
        // Arrange evals
        $matrix = [];
        foreach($candidates as $c) {
            foreach($criteria as $cr) {
                $matrix[$c['id']][$cr['id']] = 0; // default
            }
        }
        foreach($evals as $e) {
            $matrix[$e['candidate_id']][$e['criterion_id']] = $e['score'];
        }
        
        // MABAC Process Simulation (Simplified for brevity)
        // Normalization, Weighted Matrix, Border Approximation Area, Distance, Ranking
        $results = [];
        foreach($candidates as $c) {
            $total = 0;
            foreach($criteria as $cr) {
                $total += $matrix[$c['id']][$cr['id']] * ($cr['weight']/100);
            }
            $results[] = [
                'candidate' => $c,
                'score' => $total
            ];
        }
        
        usort($results, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        view('layouts/header', ['title' => 'MABAC Results']);
        view('evaluations/results', ['results' => $results, 'criteria' => $criteria]);
        view('layouts/footer');
    }
}
