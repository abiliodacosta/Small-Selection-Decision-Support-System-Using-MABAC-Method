<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-star text-warning me-2"></i> Evaluated Candidates</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEvaluationModal">
            <i class="fas fa-plus me-2"></i> Add Evaluation
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover datatable w-100">
            <thead class="table-dark">
                <tr>
                    <th width="10%">No</th>
                    <th>Candidate</th>
                    <th>Code Uma Kain / NIK</th>
                    <th width="20%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($evaluated_candidates as $c): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($c['name']) ?></td>
                    <td><?= htmlspecialchars($c['household_code'] ?? $c['nik']) ?></td>
                    <td class="text-nowrap">
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#viewModal<?= $c['id'] ?>" title="View Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $c['id'] ?>" title="Edit Evaluation">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="<?= APP_URL ?>/evaluations/delete/<?= $c['id'] ?>" class="btn btn-sm btn-danger btn-delete" title="Delete Evaluation">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modals for View and Edit (Output outside the table to prevent HTML breaking) -->
<?php foreach($evaluated_candidates as $c): ?>
<!-- View Modal -->
<div class="modal fade" id="viewModal<?= $c['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-info text-white">
        <h5 class="modal-title"><i class="fas fa-eye me-2"></i> Evaluation Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
        <h5 class="mb-3">Candidate: <?= htmlspecialchars($c['name']) ?></h5>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Criteria</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($criteria as $cr): ?>
                <tr>
                    <td><?= htmlspecialchars($cr['name']) ?> <small class="text-muted">(<?= $cr['code'] ?>)</small></td>
                    <td class="fw-bold"><?= htmlspecialchars($evals[$c['id']][$cr['id']] ?? '0') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal<?= $c['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-warning">
        <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Evaluation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="<?= APP_URL ?>/evaluations/update/<?= $c['id'] ?>" method="POST">
            <div class="modal-body">
                <h5 class="mb-3">Candidate: <?= htmlspecialchars($c['name']) ?></h5>
                <?php foreach($criteria as $cr): ?>
                <div class="mb-3">
                    <label class="form-label"><?= htmlspecialchars($cr['name']) ?> <span class="text-muted">(<?= $cr['code'] ?>)</span></label>
                    <input type="number" step="0.01" name="scores[<?= $cr['id'] ?>]" class="form-control" value="<?= htmlspecialchars($evals[$c['id']][$cr['id']] ?? '') ?>" required>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i> Update Scores</button>
            </div>
        </form>
    </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Add Modal -->
<div class="modal fade" id="addEvaluationModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-plus me-2"></i> Add New Evaluation</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= APP_URL ?>/evaluations/store" method="POST">
          <div class="modal-body">
              <?php if(empty($unevaluated_candidates)): ?>
                  <div class="alert alert-success">
                      <i class="fas fa-check-circle me-2"></i> All candidates have been evaluated!
                  </div>
              <?php else: ?>
                  <div class="mb-3">
                      <label class="form-label fw-bold">Select Candidate</label>
                      <select name="candidate_id" class="form-select" required>
                          <option value="">-- Choose a Candidate --</option>
                          <?php foreach($unevaluated_candidates as $uc): ?>
                              <option value="<?= $uc['id'] ?>"><?= htmlspecialchars($uc['name']) ?> (<?= htmlspecialchars($uc['household_code'] ?? $uc['nik']) ?>)</option>
                          <?php endforeach; ?>
                      </select>
                  </div>
                  <hr>
                  <h6 class="mb-3 text-primary">Input Scores</h6>
                  <?php foreach($criteria as $cr): ?>
                  <div class="mb-3">
                      <label class="form-label"><?= htmlspecialchars($cr['name']) ?> <span class="text-muted">(<?= $cr['code'] ?>)</span></label>
                      <input type="number" step="0.01" name="scores[<?= $cr['id'] ?>]" class="form-control" required placeholder="Enter score (e.g. 80.5)">
                  </div>
                  <?php endforeach; ?>
              <?php endif; ?>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <?php if(!empty($unevaluated_candidates)): ?>
                  <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i> Save Evaluation</button>
              <?php endif; ?>
          </div>
      </form>
    </div>
  </div>
</div>
