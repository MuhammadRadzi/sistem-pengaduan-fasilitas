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
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="app-body">
    <div class="app-shell">
        <aside class="app-sidebar" aria-label="Navigasi utama">
            <div class="sidebar-group">
                <a href="index.php" class="sidebar-link" title="Dashboard" aria-label="Dashboard"><i class="bi bi-house-door"></i></a>
                <a href="facilities.php" class="sidebar-link" title="Daftar Fasilitas" aria-label="Daftar Fasilitas"><i class="bi bi-building"></i></a>
                <a href="complaints.php" class="sidebar-link active" title="Laporan Pengaduan" aria-label="Laporan Pengaduan"><i class="bi bi-clipboard2-check"></i></a>
            </div>
        </aside>

        <main class="app-main">
            <header class="page-head">
                <div>
                    <h1 class="page-title">Dashboard</h1>
                    <p class="page-subtitle">Buat laporan dan pantau tindak lanjut pengaduan fasilitas.</p>
                </div>
            </header>

            <nav class="page-tabs" aria-label="Navigasi halaman">
                <a class="page-tab" href="index.php">Dashboard</a>
                <a class="page-tab" href="facilities.php">Daftar Fasilitas</a>
                <a class="page-tab active" href="complaints.php">Laporan</a>
            </nav>

            <?php if ($error): ?>
                <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <section class="panel section-gap">
                <div class="panel-body">
                    <h2 class="panel-title"><i class="bi bi-chat-left-plus"></i> Buat Laporan Pengaduan Baru</h2>
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="add">
                        <div class="complaint-form-grid">
                            <div>
                                <label class="form-label"><i class="bi bi-building me-1"></i> Pilih Fasilitas</label>
                                <select name="facility_id" class="form-select" required>
                                    <option value="">-- Pilih Fasilitas --</option>
                                    <?php foreach ($facilities as $fac): ?>
                                        <option value="<?= $fac['id'] ?>"><?= htmlspecialchars($fac['name']) ?> (<?= htmlspecialchars($fac['location']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label"><i class="bi bi-person me-1"></i> Nama Pelapor</label>
                                <input type="text" name="reporter_name" class="form-control" required placeholder="Nama Anda / Siswa">
                            </div>
                            <div>
                                <label class="form-label"><i class="bi bi-exclamation-octagon me-1"></i> Deskripsi Masalah / Kerusakan</label>
                                <input type="text" name="issue_description" class="form-control" required placeholder="Contoh: AC tidak dingin / lampu mati">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-3"><i class="bi bi-send me-1"></i> Kirim Laporan Pengaduan</button>
                    </form>
                </div>
            </section>

            <section class="panel">
                <div class="panel-body">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-3">
                        <div>
                            <h2 class="panel-title"><i class="bi bi-table"></i> Daftar Laporan Pengaduan</h2>
                            <p class="panel-caption">Alur: Fasilitas → Lapor Masalah → Diproses → Ditangani → Selesai</p>
                        </div>
                        <span class="badge bg-dark align-self-start align-self-sm-center"><?= count($complaints) ?> laporan</span>
                    </div>
                    <div class="table-responsive table-wrap">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Pelapor</th>
                                    <th>Fasilitas & Lokasi</th>
                                    <th>Masalah</th>
                                    <th>Status</th>
                                    <th>Tindak Lanjut</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($complaints)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted empty-state"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada laporan pengaduan.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($complaints as $index => $comp): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td>
                                                <div class="cell-title"><i class="bi bi-person-circle me-1 text-secondary"></i><?= htmlspecialchars($comp['reporter_name']) ?></div>
                                                <div class="cell-sub"><i class="bi bi-clock me-1"></i><?= $comp['created_at'] ?></div>
                                            </td>
                                            <td>
                                                <div class="cell-title"><i class="bi bi-building me-1 text-secondary"></i><?= htmlspecialchars($comp['facility_name']) ?></div>
                                                <div class="cell-sub"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($comp['location']) ?></div>
                                            </td>
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
                                                <div class="d-flex justify-content-center align-items-center gap-1">
                                                    <button type="button" class="btn btn-sm btn-primary icon-btn" data-bs-toggle="modal" data-bs-target="#updateModal<?= $comp['id'] ?>" title="Update status">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <form method="POST" action="" class="d-inline" onsubmit="return confirm('Hapus pengaduan ini?')">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?= $comp['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger icon-btn" title="Hapus"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </div>

                                                <div class="modal fade" id="updateModal<?= $comp['id'] ?>" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
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
                                                                    <button type="button" class="btn btn-soft" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Batal</button>
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
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
