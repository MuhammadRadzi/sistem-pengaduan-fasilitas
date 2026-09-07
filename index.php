<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

use App\Models\Facility;
use App\Models\Complaint;

$facilityModel = new Facility();
$complaintModel = new Complaint();

$facilities = $facilityModel->getAll();
$complaints = $complaintModel->getAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengaduan Fasilitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-tools text-warning me-2"></i>Pengaduan Fasilitas</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" href="index.php"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                <a class="nav-link" href="facilities.php"><i class="bi bi-building-gear me-1"></i> Kelola Fasilitas</a>
                <a class="nav-link" href="complaints.php"><i class="bi bi-chat-square-text me-1"></i> Daftar Pengaduan</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="p-5 mb-4 bg-white rounded-3 shadow-sm border">
            <div class="container-fluid py-3">
                <h1 class="display-5 fw-bold text-primary"><i class="bi bi-shield-exclamation me-2"></i>Sistem Pengaduan Fasilitas</h1>
                <p class="col-md-8 fs-4 text-muted">Sistem untuk mencatat dan mengelola laporan kerusakan atau masalah fasilitas sekolah.</p>
                <hr class="my-4">
                <div class="d-flex gap-3">
                    <a href="facilities.php" class="btn btn-primary btn-lg shadow-sm"><i class="bi bi-building-add me-2"></i>Kelola Fasilitas</a>
                    <a href="complaints.php" class="btn btn-success btn-lg shadow-sm"><i class="bi bi-journal-plus me-2"></i>Buat & Lihat Pengaduan</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded p-3 me-3 fs-3 shadow-sm"><i class="bi bi-buildings"></i></div>
                            <div>
                                <h3 class="card-title mb-0">Total Fasilitas: <?= count($facilities) ?></h3>
                                <p class="text-muted mb-0">Data fasilitas sekolah terdaftar.</p>
                            </div>
                        </div>
                        <a href="facilities.php" class="btn btn-outline-primary btn-sm mt-2"><i class="bi bi-arrow-right-circle me-1"></i>Lihat Fasilitas</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success text-white rounded p-3 me-3 fs-3 shadow-sm"><i class="bi bi-clipboard2-data"></i></div>
                            <div>
                                <h3 class="card-title mb-0">Total Pengaduan: <?= count($complaints) ?></h3>
                                <p class="text-muted mb-0">Laporan masalah atau kerusakan.</p>
                            </div>
                        </div>
                        <a href="complaints.php" class="btn btn-outline-success btn-sm mt-2"><i class="bi bi-arrow-right-circle me-1"></i>Lihat Pengaduan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-4 text-muted">
        <p class="mb-0">&copy; 2026 - Sistem Informasi Pengaduan Fasilitas</p>
    </footer>
</body>

</html>