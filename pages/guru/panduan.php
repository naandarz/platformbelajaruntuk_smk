<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panduan Guru</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Panduan Guru</h1>
                <p>Gunakan panduan ini untuk mengelola pembelajaran web dasar di LMS.</p>
            </div>
        </div>

        <section class="guide-grid">
            <div class="guide-step">
                <div class="guide-number">1</div>
                <h3>Kelola Materi</h3>
                <p>Tambahkan materi web, deskripsi, isi pembelajaran, contoh kode, dan urutan materi.</p>
            </div>

            <div class="guide-step">
                <div class="guide-number">2</div>
                <h3>Kelola Kuis</h3>
                <p>Buat soal evaluasi untuk setiap materi. Tentukan opsi jawaban dan kunci jawaban benar.</p>
            </div>

            <div class="guide-step">
                <div class="guide-number">3</div>
                <h3>Pantau Latihan</h3>
                <p>Buka menu Latihan Siswa untuk melihat kode web yang disimpan siswa dari fitur Live Coding.</p>
            </div>

            <div class="guide-step">
                <div class="guide-number">4</div>
                <h3>Lihat Laporan</h3>
                <p>Gunakan menu Laporan Nilai untuk melihat progress, rata-rata nilai, dan aktivitas belajar siswa.</p>
            </div>

            <div class="guide-step">
                <div class="guide-number">5</div>
                <h3>Export Data</h3>
                <p>Unduh laporan dalam format CSV sebagai dokumentasi hasil belajar siswa.</p>
            </div>

            <div class="guide-step">
                <div class="guide-number">6</div>
                <h3>Instrumen Penilaian</h3>
                <p>Cetak instrumen penilaian untuk validasi ahli media, ahli materi, atau respon pengguna.</p>
            </div>
        </section>
    </main>
</div>
</body>
</html>
