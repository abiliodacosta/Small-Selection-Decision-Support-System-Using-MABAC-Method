
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
        var csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
        var downloadLink = document.createElement("a");
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();
    }
</script>
