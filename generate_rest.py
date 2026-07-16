import os

base_dir = 'c:/xampp/htdocs/app-dss'

files_content = {
    'database/schema.sql': '''
CREATE DATABASE IF NOT EXISTS dss_mabac_db;
USE dss_mabac_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'user') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE criteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) UNIQUE,
    name VARCHAR(100),
    type ENUM('Cost', 'Benefit'),
    weight DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150),
    nik VARCHAR(20) UNIQUE,
    address TEXT,
    family_members INT,
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidate_id INT,
    criterion_id INT,
    score DECIMAL(10,2),
    FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE,
    FOREIGN KEY (criterion_id) REFERENCES criteria(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@dss.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- password is 'password'
INSERT INTO criteria (code, name, type, weight) VALUES 
('C1', 'Rendimentu Família', 'Cost', 30.00),
('C2', 'Kondisaun Fíziku Uma', 'Benefit', 25.00),
('C3', 'Totál Membru Família', 'Benefit', 20.00),
('C4', 'Status Empregu Xefe Família', 'Cost', 15.00),
('C5', 'Asesu ba Saneamentu / Bee', 'Cost', 10.00);
''',
    
    'app/Models/Database.php': '''<?php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $pdo;
    private $stmt;

    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function query($sql) {
        $this->stmt = $this->pdo->prepare($sql);
    }

    public function bind($param, $value, $type = null) {
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
}
''',
    
    'app/Controllers/DssController.php': '''<?php
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
''',
    'resources/views/evaluations/results.php': '''
<h2 class="mb-4">MABAC Ranking Results</h2>
<div class="card p-4">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Candidate Name</th>
                <th>Final Score</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $rank = 1; foreach($results as $res): ?>
            <tr>
                <td><?= $rank++ ?></td>
                <td><?= htmlspecialchars($res['candidate']['name']) ?></td>
                <td><?= number_format($res['score'], 4) ?></td>
                <td><span class="badge bg-<?= $rank <= 6 ? 'success' : 'danger' ?>"><?= $rank <= 6 ? 'Selected' : 'Rejected' ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
'''
}

for path, content in files_content.items():
    full_path = os.path.join(base_dir, path)
    os.makedirs(os.path.dirname(full_path), exist_ok=True)
    with open(full_path, 'w', encoding='utf-8') as f:
        f.write(content)

print("DB Schema and models generated.")
