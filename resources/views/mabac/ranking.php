<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-trophy text-warning me-2"></i> MABAC Ranking Results</h4>
        <button class="btn btn-success" onclick="window.print()">
            <i class="fas fa-print me-2"></i> Print Results
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover datatable w-100">
            <thead class="bg-primary text-white">
                <tr>
                    <th width="10%">Rank</th>
                    <th>Candidate Name</th>
                    <th>Code Uma Kain / NIK</th>
                    <th>Total Score (S)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; foreach($ranking as $r): ?>
                <tr>
                    <td>
                        <?php if($rank == 1): ?>
                            <span class="badge bg-warning text-dark fs-6"><i class="fas fa-medal me-1"></i> 1</span>
                        <?php elseif($rank == 2): ?>
                            <span class="badge bg-secondary fs-6"><i class="fas fa-medal me-1"></i> 2</span>
                        <?php elseif($rank == 3): ?>
                            <span class="badge bg-danger fs-6"><i class="fas fa-medal me-1"></i> 3</span>
                        <?php else: ?>
                            <span class="fw-bold"><?= $rank ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="fw-bold"><?= htmlspecialchars($r['candidate']['name']) ?></td>
                    <td><?= htmlspecialchars($r['candidate']['household_code'] ?? $r['candidate']['nik']) ?></td>
                    <td class="fw-bold text-primary fs-5"><?= number_format($r['score'], 4) ?></td>
                    <td>
                        <?php if($r['score'] > 0): ?>
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Recommended</span>
                        <?php else: ?>
                            <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Not Recommended</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php $rank++; endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 alert alert-info">
        <h5><i class="fas fa-info-circle me-2"></i> About MABAC Algorithm</h5>
        <p class="mb-0">
            The ranking is based on the <strong>Final Score (S)</strong>, which is the sum of distances from the border approximation area (Matrix Q). Candidates with a positive score (S > 0) are generally considered strong alternatives.
        </p>
    </div>
</div>
