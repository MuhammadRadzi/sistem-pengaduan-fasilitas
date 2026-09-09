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
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="app-body">
    <div class="app-shell">
        <aside class="app-sidebar" aria-label="Navigasi utama">
            <div class="sidebar-group">
                <a href="index.php" class="sidebar-link active" title="Dashboard" aria-label="Dashboard"><i class="bi bi-house-door"></i></a>
                <a href="facilities.php" class="sidebar-link" title="Daftar Fasilitas" aria-label="Daftar Fasilitas"><i class="bi bi-building"></i></a>
                <a href="complaints.php" class="sidebar-link" title="Laporan Pengaduan" aria-label="Laporan Pengaduan"><i class="bi bi-clipboard2-check"></i></a>
            </div>
        </aside>

        <main class="app-main">
            <header class="page-head">
                <div>
                    <h1 class="page-title">Dashboard</h1>
                    <p class="page-subtitle">Ringkasan sistem pengaduan dan fasilitas sekolah.</p>
                </div>
            </header>

            <nav class="page-tabs" aria-label="Navigasi halaman">
                <a class="page-tab active" href="index.php">Dashboard</a>
                <a class="page-tab" href="facilities.php">Daftar Fasilitas</a>
                <a class="page-tab" href="complaints.php">Laporan</a>
            </nav>

            <section class="dashboard-intro">
                <div class="panel hero-panel">
                    <div>
                        <span class="hero-kicker"><i class="bi bi-shield-check"></i> Sistem Pengaduan Fasilitas</span>
                        <h2 class="hero-title">Kelola laporan kerusakan fasilitas dengan lebih rapi.</h2>
                        <p class="hero-text">Catat fasilitas sekolah, kirim laporan masalah, dan pantau tindak lanjut dalam satu dashboard yang sederhana.</p>
                    </div>
                </div>

                <div class="stat-grid">
                    <div class="panel stat-card">
                        <div class="stat-top">
                            <div>
                                <div class="stat-value"><?= count($facilities) ?></div>
                                <div class="stat-label">Fasilitas terdaftar</div>
                            </div>
                            <span class="stat-icon"><i class="bi bi-buildings"></i></span>
                        </div>
                    </div>
                    <div class="panel stat-card">
                        <div class="stat-top">
                            <div>
                                <div class="stat-value"><?= count($complaints) ?></div>
                                <div class="stat-label">Total pengaduan</div>
                            </div>
                            <span class="stat-icon"><i class="bi bi-chat-left-text"></i></span>
                        </div>
                    </div>
                </div>
            </section>
                   </main>
    </div>

</body>
</html>