<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-table text-primary me-2"></i> <?= htmlspecialchars($title) ?></h4>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover w-100 <?= $step !== 'g' ? 'datatable' : '' ?>">
            <thead class="table-dark">
                <tr>
                    <?php if($step === 'g'): ?>
                        <th>Criteria Code</th>
                        <th>Border Area Value (G)</th>
                    <?php else: ?>
                        <th>Candidate / Code</th>
                        <?php foreach($criteria as $cr): ?>
                            <th><?= $cr['code'] ?></th>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if($step === 'g'): ?>
                    <?php foreach($criteria as $cr): ?>
                    <tr>
                        <td class="fw-bold"><span class="badge bg-secondary"><?= $cr['code'] ?></span> - <?= htmlspecialchars($cr['name']) ?></td>
                        <td><?= number_format($matrix_g[$cr['id']], 4) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php 
                        $matrix = [];
                        if($step === 'x') $matrix = $matrix_x;
                        if($step === 'n') $matrix = $matrix_n;
                        if($step === 'v') $matrix = $matrix_v;
                        if($step === 'q') $matrix = $matrix_q;
                    ?>
                    <?php foreach($candidates as $c): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($c['name']) ?> <br><small class="text-muted"><?= htmlspecialchars($c['household_code'] ?? $c['nik']) ?></small></td>
                        <?php foreach($criteria as $cr): ?>
                            <td><?= number_format($matrix[$c['id']][$cr['id']], 4) ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
