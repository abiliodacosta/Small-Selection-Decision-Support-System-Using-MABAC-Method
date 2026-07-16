
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
