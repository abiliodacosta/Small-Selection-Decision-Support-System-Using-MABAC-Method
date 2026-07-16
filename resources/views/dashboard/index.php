<?php
$chartLabels = [];
$chartWeights = [];
foreach($criteria as $cr) {
    $chartLabels[] = $cr['code'];
    $chartWeights[] = (float)$cr['weight'];
}

$rankLabels = [];
$rankScores = [];
foreach(array_slice($ranking, 0, 10) as $r) { // Top 10 for chart
    $rankLabels[] = $r['candidate']['name'];
    $rankScores[] = (float)$r['score'];
}
?>

<!-- Welcome Banner -->
<div class="glass-card bg-grad-1 text-white mb-4 position-relative overflow-hidden" style="border: none; padding: 10px 20px;">
    <div class="row align-items-center position-relative z-1">
        <div class="col-md-9 p-3">
            <h6 class="text-white-50 text-uppercase fw-bold mb-1"><i class="fas fa-cube me-2"></i>MABAC DSS System</h6>
            <h3 class="fw-bold mb-2">Sistema Seleção ba Uma Kbiit Laek uza Metode MABAC</h3>
            <p class="mb-0 fs-6">Bem-vindo, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></strong>! Monitoriza kandidatu, kriteria, no relatóriu iha painel ida ne'e.</p>
        </div>
        <div class="col-md-3 text-end d-none d-md-block pe-4">
            <i class="fas fa-home fa-4x text-white opacity-25"></i>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-card bg-grad-2" onclick="window.location.href='<?= APP_URL ?>/candidates'" style="cursor: pointer;">
            <div>
                <h6 class="text-white-50 text-uppercase fw-bold mb-1">Candidates</h6>
                <h2 class="fw-bold mb-0"><?= $total_candidates ?></h2>
            </div>
            <div class="fs-1 opacity-50">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card bg-grad-3" onclick="window.location.href='<?= APP_URL ?>/criteria'" style="cursor: pointer;">
            <div>
                <h6 class="text-white-50 text-uppercase fw-bold mb-1">Criteria</h6>
                <h2 class="fw-bold mb-0"><?= $total_criteria ?></h2>
            </div>
            <div class="fs-1 opacity-50">
                <i class="fas fa-list-ul"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card bg-grad-4" onclick="window.location.href='<?= APP_URL ?>/evaluations'" style="cursor: pointer;">
            <div>
                <h6 class="text-white-50 text-uppercase fw-bold mb-1">Evaluated</h6>
                <h2 class="fw-bold mb-0"><?= $total_evaluated ?></h2>
            </div>
            <div class="fs-1 opacity-50">
                <i class="fas fa-star-half-alt"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card bg-grad-1" onclick="window.location.href='<?= APP_URL ?>/mabac/ranking'" style="cursor: pointer;">
            <div>
                <h6 class="text-white-50 text-uppercase fw-bold mb-1">Ranked</h6>
                <h2 class="fw-bold mb-0"><?= count($ranking) ?></h2>
            </div>
            <div class="fs-1 opacity-50">
                <i class="fas fa-trophy"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- MABAC Ranking Graph -->
    <div class="col-md-8 mb-4">
        <div class="glass-card h-100 d-flex flex-column">
            <h5 class="mb-4"><i class="fas fa-trophy text-warning me-2"></i> Top 10 Candidates Ranking</h5>
            <div style="position: relative; height: 300px; width: 100%; flex-grow: 1;">
                <canvas id="rankingChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Criteria Weights Graph -->
    <div class="col-md-4 mb-4">
        <div class="glass-card h-100 d-flex flex-column">
            <h5 class="mb-4"><i class="fas fa-chart-pie text-primary me-2"></i> Criteria Weights</h5>
            <div style="position: relative; height: 300px; width: 100%; flex-grow: 1;">
                <canvas id="criteriaChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // Gradient for Bar Chart
        var ctxRank = document.getElementById('rankingChart').getContext('2d');
        var gradientRank = ctxRank.createLinearGradient(0, 0, 0, 400);
        gradientRank.addColorStop(0, 'rgba(67, 97, 238, 0.9)');
        gradientRank.addColorStop(1, 'rgba(76, 201, 240, 0.4)');

        // Criteria Doughnut Chart
        var ctxCriteria = document.getElementById('criteriaChart');
        if(ctxCriteria) {
            new Chart(ctxCriteria.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode($chartLabels) ?>,
                    datasets: [{
                        data: <?= json_encode($chartWeights) ?>,
                        backgroundColor: [
                            '#4361ee', '#4cc9f0', '#f72585', '#3a0ca3', '#007f5f', '#f8961e', '#277da1'
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true } },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            cornerRadius: 8
                        }
                    }
                }
            });
        }

        // Candidates Ranking Bar Chart
        if(document.getElementById('rankingChart')) {
            new Chart(ctxRank, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($rankLabels) ?>,
                    datasets: [{
                        label: ' Final Score (S)',
                        data: <?= json_encode($rankScores) ?>,
                        backgroundColor: gradientRank,
                        borderColor: 'transparent',
                        borderWidth: 0,
                        borderRadius: 8,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)', drawBorder: false }
                        },
                        x: {
                            grid: { display: false, drawBorder: false }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }
    });
</script>
