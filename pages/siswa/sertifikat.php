<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('siswa');

$id_user = $_SESSION['user']['id_user'];
$user = $_SESSION['user'];

$total_materi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM materi"))['total'];
$total_selesai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM progres_belajar WHERE id_user=$id_user AND status='selesai'"))['total'];
$rata_nilai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT AVG(skor) AS rata FROM nilai WHERE id_user=$id_user"))['rata'];
$total_latihan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM latihan_kode WHERE id_user=$id_user"))['total'];
$persen = $total_materi > 0 ? round(($total_selesai / $total_materi) * 100) : 0;

$layak = ($persen >= 100 && ($rata_nilai ? round($rata_nilai) : 0) >= 75);
$tanggal = date("d F Y");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Belajar</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar no-print">
            <div>
                <h1>Sertifikat Belajar</h1>
                <p>Sertifikat dapat dicetak setelah seluruh materi selesai dan rata-rata nilai minimal 75.</p>
            </div>
            <div class="page-actions">
                <?php if ($layak): ?>
                    <button class="btn btn-primary" onclick="window.print()">Cetak Sertifikat</button>
                <?php endif; ?>
                <a href="dashboard.php" class="btn btn-outline">Kembali</a>
            </div>
        </div>

        <?php if (!$layak): ?>
            <div class="card no-print" style="margin-bottom:18px;">
                <h3>Sertifikat Belum Terbuka</h3>
                <p>Selesaikan seluruh materi dan capai rata-rata nilai minimal 75 untuk membuka sertifikat.</p>
                <br>
                <section class="stats-grid">
                    <div class="stat-card"><span>Progress Materi</span><h2><?= $persen; ?>%</h2></div>
                    <div class="stat-card"><span>Materi Selesai</span><h2><?= $total_selesai; ?>/<?= $total_materi; ?></h2></div>
                    <div class="stat-card"><span>Rata-rata Nilai</span><h2><?= $rata_nilai ? round($rata_nilai) : 0; ?></h2></div>
                    <div class="stat-card"><span>Latihan Coding</span><h2><?= $total_latihan; ?></h2></div>
                </section>
                <a href="materi.php" class="btn btn-primary">Lanjutkan Belajar</a>
            </div>
        <?php endif; ?>

        <?php if ($layak): ?>
            <div class="certificate-wrapper">
                <div class="certificate-content">
                    <div class="logo" style="justify-content:center;">
                        <div class="logo-icon">&lt;/&gt;</div>
                        <span>HTML Learn RPL</span>
                    </div>

                    <h1 class="certificate-title">Sertifikat Penyelesaian</h1>
                    <p class="certificate-desc">Diberikan kepada</p>

                    <h2 class="certificate-name"><?= $user['nama']; ?></h2>

                    <p class="certificate-desc">
                        Telah menyelesaikan seluruh rangkaian pembelajaran web dasar pada platform
                        HTML Learn RPL, meliputi materi, kuis interaktif, dan latihan live coding.
                    </p>

                    <section class="stats-grid" style="margin-top:34px;">
                        <div class="stat-card"><span>Progress</span><h2><?= $persen; ?>%</h2></div>
                        <div class="stat-card"><span>Rata-rata Nilai</span><h2><?= round($rata_nilai); ?></h2></div>
                        <div class="stat-card"><span>Latihan Coding</span><h2><?= $total_latihan; ?></h2></div>
                        <div class="stat-card"><span>Materi Selesai</span><h2><?= $total_selesai; ?></h2></div>
                    </section>

                    <div class="signature-row">
                        <div class="signature-box">
                            <p><?= $tanggal; ?></p>
                            <div class="signature-line">Guru Pembimbing</div>
                        </div>
                        <div class="signature-box">
                            <p>HTML Learn RPL</p>
                            <div class="signature-line">Sistem LMS</div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>
</body>
</html>
