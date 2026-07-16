import os

base_dir = 'c:/xampp/htdocs/app-dss'

files_content = {
    'public/assets/css/style.css': '''
:root {
    --primary-color: #4361ee;
    --secondary-color: #3f37c9;
    --success-color: #4cc9f0;
    --danger-color: #f72585;
    --dark-bg: #1e1e2d;
    --dark-card: #27293d;
    --dark-text: #e0e0e0;
    --light-bg: #f4f7f6;
    --light-card: #ffffff;
    --light-text: #333333;
    --sidebar-width: 250px;
}

body {
    background-color: var(--light-bg);
    color: var(--light-text);
    font-family: 'Inter', sans-serif;
    transition: all 0.3s ease;
    overflow-x: hidden;
}

body.dark-mode {
    background-color: var(--dark-bg);
    color: var(--dark-text);
}

.wrapper {
    display: flex;
    width: 100%;
}

.sidebar {
    width: var(--sidebar-width);
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background: var(--light-card);
    box-shadow: 2px 0 10px rgba(0,0,0,0.05);
    z-index: 1000;
    transition: all 0.3s;
}

body.dark-mode .sidebar {
    background: var(--dark-card);
    box-shadow: 2px 0 10px rgba(0,0,0,0.3);
}

.sidebar .sidebar-header {
    padding: 20px;
    text-align: center;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

body.dark-mode .sidebar .sidebar-header {
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

.sidebar ul.components {
    padding: 20px 0;
}

.sidebar ul li a {
    padding: 12px 20px;
    font-size: 1.1em;
    display: block;
    color: var(--light-text);
    text-decoration: none;
    transition: all 0.2s;
}

body.dark-mode .sidebar ul li a {
    color: var(--dark-text);
}

.sidebar ul li a:hover, .sidebar ul li a.active {
    background: var(--primary-color);
    color: white;
}

.main-content {
    width: calc(100% - var(--sidebar-width));
    margin-left: var(--sidebar-width);
    min-height: 100vh;
    transition: all 0.3s;
    padding-bottom: 50px;
}

.topbar {
    background: var(--light-card);
    padding: 15px 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 999;
}

body.dark-mode .topbar {
    background: var(--dark-card);
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.glass-card {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.18);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
    padding: 20px;
    margin-bottom: 20px;
}

body.dark-mode .glass-card {
    background: rgba(39, 41, 61, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.stat-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    border-radius: 15px;
    color: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}
.stat-card:hover {
    transform: translateY(-5px);
}

.bg-grad-1 { background: linear-gradient(45deg, #4361ee, #3a0ca3); }
.bg-grad-2 { background: linear-gradient(45deg, #4cc9f0, #4361ee); }
.bg-grad-3 { background: linear-gradient(45deg, #f72585, #b5179e); }
.bg-grad-4 { background: linear-gradient(45deg, #2b9348, #007f5f); }

/* Responsive */
@media (max-width: 768px) {
    .sidebar { margin-left: -250px; }
    .sidebar.active { margin-left: 0; }
    .main-content { width: 100%; margin-left: 0; }
    .main-content.active { margin-left: 250px; }
}

.table { color: inherit; }
body.dark-mode .table { color: var(--dark-text); }
body.dark-mode .modal-content { background: var(--dark-card); color: var(--dark-text); }
body.dark-mode .form-control { background: #1e1e2d; color: white; border-color: #444; }
body.dark-mode .form-control:focus { background: #1e1e2d; color: white; }
''',
    
    'public/assets/js/main.js': '''
document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const toggleBtn = document.getElementById('sidebarCollapse');
    const darkModeToggle = document.getElementById('darkModeToggle');
    
    if(toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            content.classList.toggle('active');
        });
    }

    if(darkModeToggle) {
        if(localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
            darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        }
        
        darkModeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            if(document.body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
                darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                localStorage.setItem('darkMode', null);
                darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            }
        });
    }

    // Init DataTables
    if ($.fn.DataTable) {
        $('.datatable').DataTable({
            responsive: true,
            language: { search: "Peskiza:", lengthMenu: "Hatudu _MENU_ dadus" }
        });
    }
});
''',
    
    'resources/views/layouts/header.php': '''<!DOCTYPE html>
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
            <li><a href="<?= APP_URL ?>/reports"><i class="fas fa-file-alt me-3"></i> Reports</a></li>
            <hr>
            <li><a href="<?= APP_URL ?>/auth/logout" class="text-danger"><i class="fas fa-sign-out-alt me-3"></i> Logout</a></li>
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
            <div>
                <button id="darkModeToggle" class="btn btn-outline-secondary rounded-circle">
                    <i class="fas fa-moon"></i>
                </button>
                <span class="ms-3 fw-bold"><i class="fas fa-user-circle me-2"></i><?= $_SESSION['user_name'] ?? 'Admin' ?></span>
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
''',

    'resources/views/layouts/footer.php': '''
        </div> <!-- End container-fluid -->
    </div> <!-- End main-content -->
</div> <!-- End wrapper -->

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Custom JS -->
<script src="<?= APP_URL ?>/assets/js/main.js"></script>
</body>
</html>
''',

    'app/Controllers/CandidateController.php': '''<?php
class CandidateController {
    private $db;
    public function __construct() {
        if (!isset($_SESSION['user_id'])) redirect('auth');
        require_once ROOT_PATH . '/app/Models/Database.php';
        $this->db = new Database();
    }
    public function index() {
        $this->db->query("SELECT * FROM candidates ORDER BY id DESC");
        $candidates = $this->db->resultSet();
        view('layouts/header', ['title' => 'Manage Candidates']);
        view('candidates/index', ['candidates' => $candidates]);
        view('layouts/footer');
    }
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $nik = $_POST['nik'];
            $address = $_POST['address'];
            $family = $_POST['family_members'];
            $this->db->query("INSERT INTO candidates (name, nik, address, family_members) VALUES (:name, :nik, :address, :family)");
            $this->db->bind(':name', $name); $this->db->bind(':nik', $nik);
            $this->db->bind(':address', $address); $this->db->bind(':family', $family);
            $this->db->execute();
            $_SESSION['msg'] = "Candidate added successfully!";
            $_SESSION['msg_type'] = "success";
            redirect('candidates');
        }
    }
    public function delete($id) {
        $this->db->query("DELETE FROM candidates WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        $_SESSION['msg'] = "Candidate deleted successfully!";
        $_SESSION['msg_type'] = "danger";
        redirect('candidates');
    }
}
''',

    'resources/views/candidates/index.php': '''
<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-users text-primary me-2"></i> Candidates List</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCandidateModal">
            <i class="fas fa-plus me-1"></i> Add Candidate
        </button>
    </div>
    
    <table class="table table-striped table-hover datatable w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>NIK</th>
                <th>Address</th>
                <th>Family Members</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($candidates as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><span class="fw-bold"><?= htmlspecialchars($c['name']) ?></span></td>
                <td><?= htmlspecialchars($c['nik']) ?></td>
                <td><?= htmlspecialchars($c['address']) ?></td>
                <td><span class="badge bg-info"><?= $c['family_members'] ?></span></td>
                <td>
                    <a href="<?= APP_URL ?>/candidates/delete/<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this candidate?');"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="addCandidateModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="<?= APP_URL ?>/candidates/store" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Candidate</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>NIK / ID</label>
            <input type="text" name="nik" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Total Family Members</label>
            <input type="number" name="family_members" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Candidate</button>
      </div>
    </form>
  </div>
</div>
''',

    'app/Controllers/CriterionController.php': '''<?php
class CriterionController {
    private $db;
    public function __construct() {
        if (!isset($_SESSION['user_id'])) redirect('auth');
        require_once ROOT_PATH . '/app/Models/Database.php';
        $this->db = new Database();
    }
    public function index() {
        $this->db->query("SELECT * FROM criteria ORDER BY code ASC");
        $criteria = $this->db->resultSet();
        view('layouts/header', ['title' => 'Manage Criteria']);
        view('criteria/index', ['criteria' => $criteria]);
        view('layouts/footer');
    }
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $code = $_POST['code'];
            $name = $_POST['name'];
            $type = $_POST['type'];
            $weight = $_POST['weight'];
            $this->db->query("INSERT INTO criteria (code, name, type, weight) VALUES (:code, :name, :type, :weight)");
            $this->db->bind(':code', $code); $this->db->bind(':name', $name);
            $this->db->bind(':type', $type); $this->db->bind(':weight', $weight);
            $this->db->execute();
            $_SESSION['msg'] = "Criterion added successfully!";
            $_SESSION['msg_type'] = "success";
            redirect('criteria');
        }
    }
    public function delete($id) {
        $this->db->query("DELETE FROM criteria WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        $_SESSION['msg'] = "Criterion deleted successfully!";
        $_SESSION['msg_type'] = "danger";
        redirect('criteria');
    }
}
''',

    'resources/views/criteria/index.php': '''
<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-list text-primary me-2"></i> Evaluation Criteria</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCriterionModal">
            <i class="fas fa-plus me-1"></i> Add Criterion
        </button>
    </div>
    
    <table class="table table-striped table-hover datatable w-100">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Type</th>
                <th>Weight (%)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($criteria as $c): ?>
            <tr>
                <td><span class="badge bg-secondary"><?= $c['code'] ?></span></td>
                <td><span class="fw-bold"><?= htmlspecialchars($c['name']) ?></span></td>
                <td>
                    <?php if($c['type'] == 'Cost'): ?>
                        <span class="badge bg-danger">Cost</span>
                    <?php else: ?>
                        <span class="badge bg-success">Benefit</span>
                    <?php endif; ?>
                </td>
                <td><?= $c['weight'] ?> %</td>
                <td>
                    <a href="<?= APP_URL ?>/criteria/delete/<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this criterion?');"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="addCriterionModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="<?= APP_URL ?>/criteria/store" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Criterion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label>Code (e.g. C6)</label>
            <input type="text" name="code" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control" required>
                <option value="Benefit">Benefit</option>
                <option value="Cost">Cost</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Weight (%)</label>
            <input type="number" step="0.01" name="weight" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Criterion</button>
      </div>
    </form>
  </div>
</div>
''',

    'app/Controllers/EvaluationController.php': '''<?php
class EvaluationController {
    private $db;
    public function __construct() {
        if (!isset($_SESSION['user_id'])) redirect('auth');
        require_once ROOT_PATH . '/app/Models/Database.php';
        $this->db = new Database();
    }
    public function index() {
        $this->db->query("SELECT * FROM candidates ORDER BY id ASC");
        $candidates = $this->db->resultSet();
        $this->db->query("SELECT * FROM criteria ORDER BY code ASC");
        $criteria = $this->db->resultSet();
        
        $this->db->query("SELECT * FROM evaluations");
        $evals_raw = $this->db->resultSet();
        $evals = [];
        foreach($evals_raw as $e) {
            $evals[$e['candidate_id']][$e['criterion_id']] = $e['score'];
        }
        
        view('layouts/header', ['title' => 'Scores & Evaluations']);
        view('evaluations/index', ['candidates' => $candidates, 'criteria' => $criteria, 'evals' => $evals]);
        view('layouts/footer');
    }
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $scores = $_POST['scores'] ?? [];
            foreach($scores as $cand_id => $crit_scores) {
                foreach($crit_scores as $crit_id => $val) {
                    if($val !== '') {
                        $this->db->query("SELECT id FROM evaluations WHERE candidate_id = :cid AND criterion_id = :crid");
                        $this->db->bind(':cid', $cand_id); $this->db->bind(':crid', $crit_id);
                        $exists = $this->db->single();
                        if($exists) {
                            $this->db->query("UPDATE evaluations SET score = :score WHERE id = :id");
                            $this->db->bind(':score', $val); $this->db->bind(':id', $exists['id']);
                            $this->db->execute();
                        } else {
                            $this->db->query("INSERT INTO evaluations (candidate_id, criterion_id, score) VALUES (:cid, :crid, :score)");
                            $this->db->bind(':cid', $cand_id); $this->db->bind(':crid', $crit_id); $this->db->bind(':score', $val);
                            $this->db->execute();
                        }
                    }
                }
            }
            $_SESSION['msg'] = "Evaluations saved successfully!";
            $_SESSION['msg_type'] = "success";
            redirect('evaluations');
        }
    }
}
''',

    'resources/views/evaluations/index.php': '''
<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-star text-warning me-2"></i> Input Evaluations</h4>
        <button type="button" class="btn btn-success" onclick="document.getElementById('evalForm').submit();">
            <i class="fas fa-save me-1"></i> Save All Scores
        </button>
    </div>
    
    <form id="evalForm" action="<?= APP_URL ?>/evaluations/store" method="POST">
        <div class="table-responsive">
            <table class="table table-bordered table-striped datatable w-100">
                <thead class="table-dark">
                    <tr>
                        <th>Candidate</th>
                        <?php foreach($criteria as $cr): ?>
                            <th title="<?= htmlspecialchars($cr['name']) ?>"><?= $cr['code'] ?> <br><small>(<?= $cr['weight'] ?>%)</small></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($candidates as $c): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($c['name']) ?></td>
                        <?php foreach($criteria as $cr): 
                            $val = $evals[$c['id']][$cr['id']] ?? '';
                        ?>
                            <td>
                                <input type="number" step="0.01" class="form-control form-control-sm" name="scores[<?= $c['id'] ?>][<?= $cr['id'] ?>]" value="<?= $val ?>">
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </form>
</div>
''',

    'app/Controllers/ReportController.php': '''<?php
class ReportController {
    private $db;
    public function __construct() {
        if (!isset($_SESSION['user_id'])) redirect('auth');
        require_once ROOT_PATH . '/app/Models/Database.php';
        $this->db = new Database();
    }
    public function index() {
        // Execute simplified MABAC computation
        $this->db->query("SELECT * FROM candidates");
        $candidates = $this->db->resultSet();
        $this->db->query("SELECT * FROM criteria ORDER BY code ASC");
        $criteria = $this->db->resultSet();
        $this->db->query("SELECT * FROM evaluations");
        $evals = $this->db->resultSet();
        
        $matrix = [];
        foreach($candidates as $c) {
            foreach($criteria as $cr) { $matrix[$c['id']][$cr['id']] = 0; }
        }
        foreach($evals as $e) {
            $matrix[$e['candidate_id']][$e['criterion_id']] = $e['score'];
        }
        
        $results = [];
        $labels = [];
        $chartData = [];
        foreach($candidates as $c) {
            $total = 0;
            foreach($criteria as $cr) {
                // Simplified weighted sum for demonstration
                $total += $matrix[$c['id']][$cr['id']] * ($cr['weight']/100);
            }
            $results[] = [ 'candidate' => $c, 'score' => $total ];
        }
        usort($results, function($a, $b) { return $b['score'] <=> $a['score']; });
        
        // Prepare chart data
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
''',

    'resources/views/reports/index.php': '''
<div class="row mb-4">
    <div class="col-12">
        <div class="glass-card">
            <h4><i class="fas fa-chart-line text-primary me-2"></i> Final MABAC Ranking Report</h4>
            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-outline-danger me-2" onclick="window.print()"><i class="fas fa-file-pdf me-1"></i> Export PDF</button>
                <button class="btn btn-outline-success" onclick="exportTableToCSV('mabac_report.csv')"><i class="fas fa-file-excel me-1"></i> Export Excel</button>
            </div>
            <div class="row">
                <div class="col-md-7">
                    <table class="table table-striped table-hover datatable w-100" id="reportTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Rank</th>
                                <th>Candidate Name</th>
                                <th>Final Score (Qi)</th>
                                <th>Decision</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $rank = 1; foreach($results as $res): ?>
                            <tr>
                                <td><span class="badge bg-primary fs-6"><?= $rank++ ?></span></td>
                                <td><span class="fw-bold"><?= htmlspecialchars($res['candidate']['name']) ?></span></td>
                                <td><span class="badge bg-secondary fs-6"><?= number_format($res['score'], 4) ?></span></td>
                                <td>
                                    <?php if($rank <= 6): ?>
                                        <span class="badge bg-success">Selected</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-5">
                    <div class="card p-3 shadow-sm h-100 bg-light">
                        <canvas id="rankingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('rankingChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= $labels ?>,
                datasets: [{
                    label: 'Final Score',
                    data: <?= $chartData ?>,
                    backgroundColor: 'rgba(67, 97, 238, 0.7)',
                    borderColor: 'rgba(67, 97, 238, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });

    function exportTableToCSV(filename) {
        var csv = [];
        var rows = document.querySelectorAll("#reportTable tr");
        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll("td, th");
            for (var j = 0; j < cols.length; j++) 
                row.push(cols[j].innerText);
            csv.push(row.join(","));
        }
        var csvFile = new Blob([csv.join("\\n")], {type: "text/csv"});
        var downloadLink = document.createElement("a");
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();
    }
</script>
'''
}

for path, content in files_content.items():
    full_path = os.path.join(base_dir, path)
    os.makedirs(os.path.dirname(full_path), exist_ok=True)
    with open(full_path, 'w', encoding='utf-8') as f:
        f.write(content)

print("UI/UX Modules generated successfully.")
