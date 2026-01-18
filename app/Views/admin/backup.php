<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Backup & Restore - Admin<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Info:</strong> Backup database disimpan di folder <code>writable/backups</code>.
                Saat restore, emergency backup akan dibuat otomatis.
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Backup Baru</h5>
                    <p class="card-text">Buat backup database sekarang</p>
                    <button onclick="createBackup()" class="btn btn-light">
                        <i class="fas fa-plus"></i> Buat Backup
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Backup</h5>
                    <h2 class="mb-0"><?= count($backups) ?></h2>
                    <p class="card-text mb-0"><?= number_format($total_size, 2) ?> MB</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Bersihkan Backup Lama</h5>
                    <p class="card-text">Hapus backup lebih dari 30 hari</p>
                    <button onclick="cleanBackups()" class="btn btn-dark">
                        <i class="fas fa-broom"></i> Bersihkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Backup</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nama File</th>
                                    <th>Ukuran</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($backups)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-archive fa-2x text-gray-300"></i>
                                            <p class="text-gray-500 mt-2">Belum ada backup</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($backups as $backup): ?>
                                        <tr>
                                            <td>
                                                <i class="fas fa-database text-primary"></i>
                                                <?= htmlspecialchars($backup['name']) ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    <?= number_format($backup['size'], 2) ?> MB
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('d/m/Y H:i:s', strtotime($backup['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/admin/backup/download?file=<?= urlencode($backup['name']) ?>"
                                                       class="btn btn-outline-primary" title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <button onclick="restoreBackup('<?= htmlspecialchars($backup['name'], ENT_QUOTES) ?>')"
                                                            class="btn btn-outline-warning" title="Restore">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                    <button onclick="deleteBackup('<?= htmlspecialchars($backup['name'], ENT_QUOTES) ?>')"
                                                            class="btn btn-outline-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
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

<!-- Restore Confirmation Modal -->
<div class="modal fade" id="restoreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Konfirmasi Restore
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Anda akan me-restore database dari backup:</p>
                <p class="alert alert-warning">
                    <strong id="restoreFilename"></strong>
                </p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Perhatian:</strong> Data saat ini akan ditimpa. Emergency backup akan dibuat otomatis.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning" onclick="confirmRestore()">
                    <i class="fas fa-undo"></i> Restore
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let restoreTarget = '';

function createBackup() {
    if (!confirm('Buat backup database sekarang?')) return;

    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

    fetch('/admin/backup/create', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('Backup berhasil dibuat!');
            location.reload();
        } else {
            alert('Gagal: ' + data.message);
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    })
    .catch(err => {
        alert('Error: ' + err.message);
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function restoreBackup(filename) {
    restoreTarget = filename;
    document.getElementById('restoreFilename').textContent = filename;
    $('#restoreModal').modal('show');
}

function confirmRestore() {
    const fd = new FormData();
    fd.append('filename', restoreTarget);

    fetch('/admin/backup/restore', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: fd
    })
    .then(r => r.json())
    .then(data => {
        $('#restoreModal').modal('hide');
        if (data.success) {
            alert('Database berhasil direstore! Emergency backup: ' + data.emergency_backup);
            location.reload();
        } else {
            alert('Gagal: ' + data.message);
        }
    })
    .catch(err => {
        $('#restoreModal').modal('hide');
        alert('Error: ' + err.message);
    });
}

function deleteBackup(filename) {
    if (!confirm('Hapus backup "' + filename + '"?')) return;

    const fd = new FormData();
    fd.append('filename', filename);

    fetch('/admin/backup/delete', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: fd
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('Backup berhasil dihapus');
            location.reload();
        } else {
            alert('Gagal: ' + data.message);
        }
    })
    .catch(err => {
        alert('Error: ' + err.message);
    });
}

function cleanBackups() {
    if (!confirm('Hapus backup lebih dari 30 hari?')) return;

    const fd = new FormData();
    fd.append('days', '30');

    fetch('/admin/backup/clean', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: fd
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Gagal: ' + data.message);
        }
    })
    .catch(err => {
        alert('Error: ' + err.message);
    });
}
</script>
<?= $this->endSection() ?>
