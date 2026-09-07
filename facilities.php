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
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-tools text-warning me-2"></i>Pengaduan Fasilitas</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                <a class="nav-link active" href="facilities.php"><i class="bi bi-building-gear me-1"></i> Kelola Fasilitas</a>
                <a class="nav-link" href="complaints.php"><i class="bi bi-chat-square-text me-1"></i> Daftar Pengaduan</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4"><i class="bi bi-building-fill-gear me-2"></i>Kelola Data Fasilitas</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger shadow-sm"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success shadow-sm"><i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="row">
            <!-- Form Input / Edit -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="card-title mb-3"><i class="bi bi-<?= $editData ? 'pencil-square text-warning' : 'plus-circle text-primary' ?> me-2"></i><?= $editData ? 'Edit Fasilitas' : 'Tambah Fasilitas' ?></h4>
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

                            <button type="submit" class="btn btn-primary w-100 shadow-sm"><i class="bi bi-save me-1"></i> <?= $editData ? 'Simpan Perubahan' : 'Tambah Fasilitas' ?></button>
                            <?php if ($editData): ?>
                                <a href="facilities.php" class="btn btn-secondary w-100 mt-2"><i class="bi bi-x-circle me-1"></i> Batal</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Data Fasilitas -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="card-title mb-3"><i class="bi bi-list-ul me-2"></i>Daftar Fasilitas Sekolah</h4>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Lokasi</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($facilities)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada data fasilitas.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($facilities as $index => $fac): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><strong><i class="bi bi-building text-primary me-1"></i><?= htmlspecialchars($fac['name']) ?></strong></td>
                                                <td><i class="bi bi-geo-alt text-muted me-1"></i><?= htmlspecialchars($fac['location']) ?></td>
                                                <td><?= htmlspecialchars($fac['description'] ?? '-') ?></td>
                                                <td>
                                                    <a href="facilities.php?edit=<?= $fac['id'] ?>" class="btn btn-sm btn-warning shadow-sm"><i class="bi bi-pencil"></i></a>
                                                    <form method="POST" action="" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus fasilitas ini?')">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?= $fac['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm"><i class="bi bi-trash"></i></button>
                                                    </form>
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
</body>

</html>