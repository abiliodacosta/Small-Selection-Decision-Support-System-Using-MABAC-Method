
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
                <th>Code Uma Kain</th>
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
                <td><span class="badge bg-secondary"><?= htmlspecialchars($c['household_code'] ?? '') ?></span></td>
                <td><span class="fw-bold"><?= htmlspecialchars($c['name']) ?></span></td>
                <td><?= htmlspecialchars($c['nik']) ?></td>
                <td><?= htmlspecialchars($c['address']) ?></td>
                <td><span class="badge bg-info"><?= $c['family_members'] ?></span></td>
                <td class="text-nowrap">
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#viewModal<?= $c['id'] ?>" title="View Details"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editModal<?= $c['id'] ?>" title="Edit"><i class="fas fa-edit"></i></button>
                        <a href="<?= APP_URL ?>/candidates/delete/<?= $c['id'] ?>" class="btn btn-sm btn-danger btn-delete" title="Delete"><i class="fas fa-trash"></i></a>
                    </div>
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
            <label>Code Uma Kain</label>
            <input type="text" name="household_code" class="form-control" required>
        </div>
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

<!-- Dynamic Modals for View and Edit -->
<?php foreach($candidates as $c): ?>
<!-- View Modal -->
<div class="modal fade" id="viewModal<?= $c['id'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Candidate Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Code Uma Kain:</strong> <?= htmlspecialchars($c['household_code'] ?? '') ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($c['name']) ?></p>
        <p><strong>NIK / ID:</strong> <?= htmlspecialchars($c['nik']) ?></p>
        <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($c['address'])) ?></p>
        <p><strong>Family Members:</strong> <?= $c['family_members'] ?></p>
        <p><strong>Registered:</strong> <?= $c['created_at'] ?? 'N/A' ?></p>
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
    <form action="<?= APP_URL ?>/candidates/update/<?= $c['id'] ?>" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Candidate</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label>Code Uma Kain</label>
            <input type="text" name="household_code" class="form-control" value="<?= htmlspecialchars($c['household_code'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($c['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>NIK / ID</label>
            <input type="text" name="nik" class="form-control" value="<?= htmlspecialchars($c['nik']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control" required><?= htmlspecialchars($c['address']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Total Family Members</label>
            <input type="number" name="family_members" class="form-control" value="<?= $c['family_members'] ?>" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update Candidate</button>
      </div>
    </form>
  </div>
</div>
<?php endforeach; ?>
