<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('admin');

$total_user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users"))['total'];
$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='siswa'"))['total'];
$total_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='guru'"))['total'];
$total_materi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM materi"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Dashboard Admin</h1>
                <p>Kelola data utama sistem HTML Learn RPL.</p>
            </div>
        </div>

        <section class="stats-grid">
            <div class="stat-card"><span>Total User</span><h2><?= $total_user; ?></h2></div>
            <div class="stat-card"><span>Total Siswa</span><h2><?= $total_siswa; ?></h2></div>
            <div class="stat-card"><span>Total Guru</span><h2><?= $total_guru; ?></h2></div>
            <div class="stat-card"><span>Total Materi</span><h2><?= $total_materi; ?></h2></div>
        </section>

        <div class="card">
            <h3>Ringkasan Sistem</h3>
            <p>Sistem ini digunakan untuk mengelola pembelajaran web dasar, mulai dari materi HTML/CSS/JavaScript, kuis, nilai, hingga laporan perkembangan belajar siswa.</p>
        </div>
    </main>
</div>
</body>
</html>
