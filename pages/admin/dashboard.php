<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
include "../../includes/tahap22_bootstrap.php";
hanya_role('admin');

$total_user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users"))['total'];
$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='siswa'"))['total'];
$total_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='guru'"))['total'];
$total_materi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM materi"))['total'];
$total_helpdesk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM admin_helpdesk_logs"))['total'];
$total_absensi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM absensi"))['total'];
$total_jadwal = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM jadwal_kelas"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Dashboard Admin</h1>
                <p>Kelola data utama sistem SmartLearn.</p>
            </div>
        </div>

        <section class="stats-grid">
            <div class="stat-card"><span>Total User</span><h2><?= $total_user; ?></h2></div>
            <div class="stat-card"><span>Total Siswa</span><h2><?= $total_siswa; ?></h2></div>
            <div class="stat-card"><span>Total Guru</span><h2><?= $total_guru; ?></h2></div>
            <div class="stat-card"><span>Total Materi</span><h2><?= $total_materi; ?></h2></div>
        </section>

        <section class="stats-grid">
            <a class="stat-card" href="absensi.php" style="text-decoration:none;"><span>Total Absensi</span><h2><?= $total_absensi; ?></h2></a>
            <a class="stat-card" href="jadwal.php" style="text-decoration:none;"><span>Total Jadwal</span><h2><?= $total_jadwal; ?></h2></a>
            <a class="stat-card" href="rekap_aktivitas.php" style="text-decoration:none;"><span>Rekap Aktivitas</span><h2>📊</h2></a>
            <a class="stat-card" href="ui_settings.php" style="text-decoration:none;"><span>Pengaturan UI</span><h2>🎨</h2></a>
        </section>

        <div class="card">
            <h3>Ringkasan Sistem</h3>
            <p>Sistem ini digunakan untuk mengelola pembelajaran web dasar, mulai dari materi HTML/CSS/JavaScript, kuis, nilai, absensi, jadwal, hingga laporan perkembangan belajar siswa.</p>
        </div>
    </main>
</div>
</body>
</html>
