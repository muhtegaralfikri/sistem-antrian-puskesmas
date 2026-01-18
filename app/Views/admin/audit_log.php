<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Log Aktivitas - Admin<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Log Aktivitas Sistem</h3>
                    <div class="card-tools">
                        <a href="/admin/audit-log/export?<?= http_build_query($filters) ?>" class="btn btn-sm btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card-body">
                    <form method="GET" action="" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Aksi</label>
                            <select name="action" class="form-select">
                                <option value="">Semua</option>
                                <?php foreach ($actions as $key => $label): ?>
                                    <option value="<?= $key ?>" <?= ($filters['action'] ?? '') === $key ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Entity</label>
                            <select name="entity_type" class="form-select">
                                <option value="">Semua</option>
                                <?php foreach ($entity_types as $key => $label): ?>
                                    <option value="<?= $key ?>" <?= ($filters['entity_type'] ?? '') === $key ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control" value="<?= $filters['start_date'] ?? '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control" value="<?= $filters['end_date'] ?? '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="/admin/audit-log" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Log Table -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>User</th>
                                    <th>Aksi</th>
                                    <th>Entity</th>
                                    <th>Deskripsi</th>
                                    <th>IP Address</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($logs)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x text-gray-300"></i>
                                            <p class="text-gray-500 mt-2">Tidak ada log ditemukan</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($logs as $log): ?>
                                        <tr>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    <?= htmlspecialchars($log['username'] ?? 'System') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?= $this->getActionBadgeClass($log['action']) ?>">
                                                    <?= $log['action'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($log['entity_type']): ?>
                                                    <span class="badge badge-info"><?= $log['entity_type'] ?></span>
                                                    <?php if ($log['entity_id']): ?>
                                                        <small class="text-muted">#<?= $log['entity_id'] ?></small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small><?= htmlspecialchars($log['description'] ?? '-') ?></small>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?= $log['ip_address'] ?? '-' ?></small>
                                            </td>
                                            <td>
                                                <a href="/admin/audit-log/view/<?= $log['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function getActionBadgeClass(action) {
    const badgeClasses = {
        'LOGIN': 'success',
        'LOGOUT': 'secondary',
        'CREATE': 'primary',
        'UPDATE': 'warning',
        'DELETE': 'danger',
        'CALL': 'info',
        'RECALL': 'info',
        'COMPLETE': 'success',
        'SKIP': 'warning',
        'RESET': 'danger',
    };
    return badgeClasses[action] || 'secondary';
}
</script>
<?= $this->endSection() ?>
