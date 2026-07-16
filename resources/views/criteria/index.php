
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
            <?php $totalWeight = 0; foreach($criteria as $c): $totalWeight += $c['weight']; ?>
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
                <td class="text-nowrap">
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#viewModal<?= $c['id'] ?>" title="View Details"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editModal<?= $c['id'] ?>" title="Edit"><i class="fas fa-edit"></i></button>
                        <a href="<?= APP_URL ?>/criteria/delete/<?= $c['id'] ?>" class="btn btn-sm btn-danger btn-delete" title="Delete"><i class="fas fa-trash"></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="table-light">
            <tr>
                <td colspan="3" class="text-end fw-bold">Total Pesu (Weight):</td>
                <td class="fw-bold <?= $totalWeight == 100 ? 'text-primary' : 'text-danger' ?>"><?= number_format($totalWeight, 2) ?> %</td>
                <td></td>
            </tr>
        </tfoot>
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

<!-- Dynamic Modals for View and Edit -->
<?php foreach($criteria as $c): ?>
<!-- View Modal -->
<div class="modal fade" id="viewModal<?= $c['id'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Criterion Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Code:</strong> <?= htmlspecialchars($c['code']) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($c['name']) ?></p>
        <p><strong>Type:</strong> <?= $c['type'] ?></p>
        <p><strong>Weight:</strong> <?= $c['weight'] ?>%</p>
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
    <form action="<?= APP_URL ?>/criteria/update/<?= $c['id'] ?>" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Criterion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label>Code (e.g. C6)</label>
            <input type="text" name="code" class="form-control" value="<?= htmlspecialchars($c['code']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($c['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control" required>
                <option value="Benefit" <?= $c['type'] == 'Benefit' ? 'selected' : '' ?>>Benefit</option>
                <option value="Cost" <?= $c['type'] == 'Cost' ? 'selected' : '' ?>>Cost</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Weight (%)</label>
            <input type="number" step="0.01" name="weight" class="form-control" value="<?= $c['weight'] ?>" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update Criterion</button>
      </div>
    </form>
  </div>
</div>
<?php endforeach; ?>
