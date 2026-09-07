<?php
require_once 'vendor/autoload.php';

use App\Models\Facility;
use App\Models\Complaint;

$facilityModel = new Facility();
$complaintModel = new Complaint();

$error = '';
$success = '';

// Handle Create Complaint
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $facility_id = $_POST['facility_id'] ?? '';
        $reporter_name = trim($_POST['reporter_name'] ?? '');
        $issue_description = trim($_POST['issue_description'] ?? '');

        if (empty($facility_id) || empty($reporter_name) || empty($issue_description)) {
            $error = 'Semua field wajib diisi!';
        } else {
            $complaintModel->create($facility_id, $reporter_name, $issue_description);
            $success = 'Laporan pengaduan berhasil dikirim!';
        }
    } elseif ($action === 'update_status') {
        $id = $_POST['id'] ?? '';
        $status = $_POST['status'] ?? '';
        $action_note = trim($_POST['action_note'] ?? '');

        if ($id && $status) {
            $complaintModel->updateStatusAndNote($id, $status, $action_note);
            $success = 'Status & tindak lanjut berhasil diperbarui!';
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? '';
        if ($id) {
            $complaintModel->delete($id);
            $success = 'Data pengaduan berhasil dihapus!';
        }
    }
}

$facilities = $facilityModel->getAll();
$complaints = $complaintModel->getAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengaduan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-tools text-warning me-2"></i>Pengaduan Fasilitas</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                <a class="nav-link" href="facilities.php"><i class="bi bi-building-gear me-1"></i> Kelola Fasilitas</a>
                <a class="nav-link active" href="complaints.php"><i class="bi bi-chat-square-text me-1"></i> Daftar Pengaduan</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-2"><i class="bi bi-journal-text me-2"></i>Manajemen Pengaduan Fasilitas</h2>
        <p class="text-muted mb-4"><i class="bi bi-info-circle me-1"></i> Alur: Fasilitas &rarr; Lapor Masalah &rarr; Diproses &rarr; Ditangani &rarr; Selesai</p>

        <?php if ($error): ?>
            <div class="alert alert-danger shadow-sm"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success shadow-sm"><i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- Form Lapor Masalah -->
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <h4 class="card-title mb-3"><i class="bi bi-chat-left-plus text-success me-2"></i>Buat Laporan Pengaduan Baru</h4>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><i class="bi bi-building me-1"></i> Pilih Fasilitas</label>
                            <select name="facility_id" class="form-select" required>
                                <option value="">-- Pilih Fasilitas --</option>
                                <?php foreach ($facilities as $fac): ?>
                                    <option value="<?= $fac['id'] ?>"><?= htmlspecialchars($fac['name']) ?> (<?= htmlspecialchars($fac['location']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><i class="bi bi-person me-1"></i> Nama Pelapor</label>
                            <input type="text" name="reporter_name" class="form-control" required placeholder="Nama Anda / Siswa">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><i class="bi bi-exclamation-octagon me-1"></i> Deskripsi Masalah / Kerusakan</label>
                            <input type="text" name="issue_description" class="form-control" required placeholder="Contoh: AC tidak dingin / lampu mati">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success shadow-sm"><i class="bi bi-send me-1"></i> Kirim Laporan Pengaduan</button>
                </form>
            </div>
        </div>

        <!-- Daftar Pengaduan -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title mb-3"><i class="bi bi-table me-2"></i>Daftar Laporan Pengaduan</h4>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Pelapor</th>
                                <th>Fasilitas & Lokasi</th>
                                <th>Masalah</th>
                                <th>Status</th>
                                <th>Tindak Lanjut</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($complaints)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada laporan pengaduan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($complaints as $index => $comp): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><i class="bi bi-person-circle text-secondary me-1"></i><?= htmlspecialchars($comp['reporter_name']) ?></strong><br><small class="text-muted"><i class="bi bi-clock me-1"></i><?= $comp['created_at'] ?></small></td>
                                        <td><i class="bi bi-building me-1"></i><?= htmlspecialchars($comp['facility_name']) ?><br><small class="text-muted"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($comp['location']) ?></small></td>
                                        <td><?= htmlspecialchars($comp['issue_description']) ?></td>
                                        <td>
                                            <?php
                                            $badgeClass = 'bg-secondary';
                                            if ($comp['status'] === 'Fasilitas') $badgeClass = 'bg-secondary';
                                            elseif ($comp['status'] === 'Lapor Masalah') $badgeClass = 'bg-warning text-dark';
                                            elseif ($comp['status'] === 'Diproses') $badgeClass = 'bg-info text-dark';
                                            elseif ($comp['status'] === 'Ditangani') $badgeClass = 'bg-primary';
                                            elseif ($comp['status'] === 'Selesai') $badgeClass = 'bg-success';
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($comp['status']) ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($comp['action_note'] ?? '-') ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <!-- Button Trigger Modal -->
                                                <button type="button" class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#updateModal<?= $comp['id'] ?>">
                                                    <i class="bi bi-pencil-square"></i> Status
                                                </button>
                                                <form method="POST" action="" class="d-inline" onsubmit="return confirm('Hapus pengaduan ini?')">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= $comp['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger shadow-sm"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </div>

                                            <!-- Modal Update Status & Tindak Lanjut -->
                                            <div class="modal fade" id="updateModal<?= $comp['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="POST" action="">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Update Status & Tindak Lanjut</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="action" value="update_status">
                                                                <input type="hidden" name="id" value="<?= $comp['id'] ?>">

                                                                <div class="mb-3">
                                                                    <label class="form-label"><i class="bi bi-flag me-1"></i> Status Pengaduan</label>
                                                                    <select name="status" class="form-select" required>
                                                                        <option value="Fasilitas" <?= $comp['status'] === 'Fasilitas' ? 'selected' : '' ?>>Fasilitas</option>
                                                                        <option value="Lapor Masalah" <?= $comp['status'] === 'Lapor Masalah' ? 'selected' : '' ?>>Lapor Masalah</option>
                                                                        <option value="Diproses" <?= $comp['status'] === 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                                                                        <option value="Ditangani" <?= $comp['status'] === 'Ditangani' ? 'selected' : '' ?>>Ditangani</option>
                                                                        <option value="Selesai" <?= $comp['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label"><i class="bi bi-journal-check me-1"></i> Keterangan / Tindak Lanjut</label>
                                                                    <textarea name="action_note" class="form-control" rows="3" placeholder="Contoh: Teknisi sedang mengganti lampu..."><?= htmlspecialchars($comp['action_note'] ?? '') ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Batal</button>
                                                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>