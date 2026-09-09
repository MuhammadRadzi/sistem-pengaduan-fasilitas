<?php
require_once 'vendor/autoload.php';

use App\Models\Facility;

$facilityModel = new Facility();
$error = '';
$success = '';

// Handle Create / Update / Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $name = trim($_POST['name'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($name) || empty($location)) {
                $error = 'Nama fasilitas dan lokasi wajib diisi!';
            } else {
                $facilityModel->create($name, $location, $description);
                $success = 'Data fasilitas berhasil ditambahkan!';
            }
        } elseif ($_POST['action'] === 'edit') {
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($name) || empty($location)) {
                $error = 'Nama fasilitas dan lokasi wajib diisi!';
            } else {
                $facilityModel->update($id, $name, $location, $description);
                $success = 'Data fasilitas berhasil diperbarui!';
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $facilityModel->delete($id);
                $success = 'Data fasilitas berhasil dihapus!';
            }
        }
    }
}

$facilities = $facilityModel->getAll();
$editData = null;
if (isset($_GET['edit'])) {
    $editData = $facilityModel->getById($_GET['edit']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Fasilitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="app-body">
    <div class="app-shell">
        <aside class="app-sidebar" aria-label="Navigasi utama">
            <div class="sidebar-group">
                <a href="index.php" class="sidebar-link" title="Dashboard" aria-label="Dashboard"><i class="bi bi-house-door"></i></a>
                <a href="facilities.php" class="sidebar-link active" title="Daftar Fasilitas" aria-label="Daftar Fasilitas"><i class="bi bi-building"></i></a>
                <a href="complaints.php" class="sidebar-link" title="Laporan Pengaduan" aria-label="Laporan Pengaduan"><i class="bi bi-clipboard2-check"></i></a>
            </div>
        </aside>

        <main class="app-main">
            <header class="page-head">
                <div>
                    <h1 class="page-title">Dashboard</h1>
                    <p class="page-subtitle">Kelola data fasilitas sekolah.</p>
                </div>
            </header>

            <nav class="page-tabs" aria-label="Navigasi halaman">
                <a class="page-tab" href="index.php">Dashboard</a>
                <a class="page-tab active" href="facilities.php">Daftar Fasilitas</a>
                <a class="page-tab" href="complaints.php">Laporan</a>
            </nav>

            <?php if ($error): ?>
                <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="content-grid-facilities">
                <section class="panel">
                    <div class="panel-body">
                        <h2 class="panel-title"><i class="bi bi-<?= $editData ? 'pencil-square' : 'plus-circle' ?>"></i> <?= $editData ? 'Edit Fasilitas' : 'Tambah Fasilitas' ?></h2>
                        <p class="panel-caption mb-3"><?= $editData ? 'Perbarui informasi fasilitas yang dipilih.' : 'Tambahkan fasilitas sekolah baru.' ?></p>

                        <form method="POST" action="">
                            <input type="hidden" name="action" value="<?= $editData ? 'edit' : 'add' ?>">
                            <?php if ($editData): ?>
                                <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-tag me-1"></i> Nama Fasilitas</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editData['name'] ?? '') ?>" required placeholder="Contoh: AC Ruang Kelas">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-geo-alt me-1"></i> Lokasi</label>
                                <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($editData['location'] ?? '') ?>" required placeholder="Contoh: Lantai 2">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-card-text me-1"></i> Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Keterangan tambahan..."><?= htmlspecialchars($editData['description'] ?? '') ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save me-1"></i> <?= $editData ? 'Simpan Perubahan' : 'Tambah Fasilitas' ?></button>
                            <?php if ($editData): ?>
                                <a href="facilities.php" class="btn btn-soft w-100 mt-2"><i class="bi bi-x-circle me-1"></i> Batal</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </section>

                <section class="panel">
                    <div class="panel-body">
                        <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                            <div>
                                <h2 class="panel-title"><i class="bi bi-list-ul"></i> Daftar Fasilitas Sekolah</h2>
                                <p class="panel-caption"><?= count($facilities) ?> fasilitas tersimpan.</p>
                            </div>
                        </div>
                        <div class="table-responsive table-wrap">
                            <table class="table table-hover facilities-table align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Lokasi</th>
                                        <th>Deskripsi</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($facilities)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted empty-state"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada data fasilitas.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($facilities as $index => $fac): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><span class="cell-title"><i class="bi bi-building me-1 text-secondary"></i><?= htmlspecialchars($fac['name']) ?></span></td>
                                                <td><i class="bi bi-geo-alt me-1 text-secondary"></i><?= htmlspecialchars($fac['location']) ?></td>
                                                <td><?= htmlspecialchars($fac['description'] ?? '-') ?></td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <a href="facilities.php?edit=<?= $fac['id'] ?>" class="btn btn-sm btn-warning icon-btn" title="Edit"><i class="bi bi-pencil"></i></a>
                                                        <form method="POST" action="" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus fasilitas ini?')">
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="id" value="<?= $fac['id'] ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger icon-btn" title="Hapus"><i class="bi bi-trash"></i></button>
                                                        </form>
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
            </div>
        </main>
    </div>
</body>
</html>
