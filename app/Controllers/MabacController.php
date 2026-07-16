<?php
class MabacController {
    private $db;
    public function __construct() {
        if (!isset($_SESSION['user_id'])) redirect('auth');
        require_once ROOT_PATH . '/app/Models/Database.php';
        $this->db = new Database();
    }

    public function getMabacData() {
        $this->db->query("SELECT DISTINCT c.* FROM candidates c JOIN evaluations e ON c.id = e.candidate_id ORDER BY c.id ASC");
        $candidates = $this->db->resultSet();
        $this->db->query("SELECT * FROM criteria ORDER BY code ASC");
        $criteria = $this->db->resultSet();
        $this->db->query("SELECT * FROM evaluations");
        $evals = $this->db->resultSet();

        // Step 1: Matrix X
        $matrix_x = [];
        foreach($candidates as $c) {
            foreach($criteria as $cr) {
                $matrix_x[$c['id']][$cr['id']] = 0;
            }
        }
        foreach($evals as $e) {
            $matrix_x[$e['candidate_id']][$e['criterion_id']] = (float)$e['score'];
        }

        // Min and Max per criterion
        $min_x = [];
        $max_x = [];
        foreach($criteria as $cr) {
            $col = [];
            foreach($candidates as $c) {
                $col[] = $matrix_x[$c['id']][$cr['id']];
            }
            if (!empty($col)) {
                $min_x[$cr['id']] = min($col);
                $max_x[$cr['id']] = max($col);
            }
        }

        // Step 2: Matrix N
        $matrix_n = [];
        foreach($candidates as $c) {
            foreach($criteria as $cr) {
                $min = $min_x[$cr['id']];
                $max = $max_x[$cr['id']];
                $val = $matrix_x[$c['id']][$cr['id']];
                $diff = $max - $min;
                
                if ($diff == 0) {
                    $matrix_n[$c['id']][$cr['id']] = 0;
                } else {
                    if ($cr['type'] == 'Benefit') {
                        $matrix_n[$c['id']][$cr['id']] = ($val - $min) / $diff;
                    } else { // Cost
                        $matrix_n[$c['id']][$cr['id']] = ($max - $val) / $diff;
                    }
                }
            }
        }

        // Step 3: Matrix V
        $matrix_v = [];
        foreach($candidates as $c) {
            foreach($criteria as $cr) {
                $weight = $cr['weight'] / 100;
                $n_val = $matrix_n[$c['id']][$cr['id']];
                $matrix_v[$c['id']][$cr['id']] = $weight * ($n_val + 1);
            }
        }

        // Step 4: Matrix G
        $matrix_g = [];
        $m = count($candidates);
        foreach($criteria as $cr) {
            $product = 1;
            foreach($candidates as $c) {
                $product *= $matrix_v[$c['id']][$cr['id']];
            }
            if ($m > 0) {
                $matrix_g[$cr['id']] = pow($product, 1/$m);
            } else {
                $matrix_g[$cr['id']] = 0;
            }
        }

        // Step 5 & 6: Matrix Q and Ranking
        $matrix_q = [];
        $ranking = [];
        foreach($candidates as $c) {
            $total_q = 0;
            foreach($criteria as $cr) {
                $v = $matrix_v[$c['id']][$cr['id']];
                $g = $matrix_g[$cr['id']];
                $q = $v - $g;
                $matrix_q[$c['id']][$cr['id']] = $q;
                $total_q += $q;
            }
            $ranking[] = [
                'candidate' => $c,
                'score' => $total_q
            ];
        }

        usort($ranking, function($a, $b) {
            return $b['score'] <=> $a['score']; // Descending
        });

        return [
            'candidates' => $candidates,
            'criteria' => $criteria,
            'matrix_x' => $matrix_x,
            'matrix_n' => $matrix_n,
            'matrix_v' => $matrix_v,
            'matrix_g' => $matrix_g,
            'matrix_q' => $matrix_q,
            'ranking' => $ranking
        ];
    }

    public function matrix($step = 'x') {
        $data = $this->getMabacData();
        
        $titles = [
            'x' => 'Matrix X (Decision Matrix)',
            'n' => 'Matrix Normalizasaun (N)',
            'v' => 'Matriks Terbobot (V)',
            'g' => 'Matrix G (Border Approximation Area)',
            'q' => 'Matrix Q (Distance from Border Area)'
        ];

        if (!array_key_exists($step, $titles)) {
            $step = 'x';
        }

        $data['title'] = $titles[$step];
        $data['step'] = $step;

        view('layouts/header', ['title' => 'MABAC - ' . $titles[$step]]);
        view('mabac/matrix', $data);
        view('layouts/footer');
    }

    public function ranking() {
        $data = $this->getMabacData();
        view('layouts/header', ['title' => 'MABAC Ranking Results']);
        view('mabac/ranking', $data);
        view('layouts/footer');
    }
}
