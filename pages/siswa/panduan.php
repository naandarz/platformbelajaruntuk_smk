<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('siswa');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panduan Siswa</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Panduan Siswa</h1>
                <p>Ikuti alur berikut agar proses belajar HTML lebih terarah.</p>
            </div>
        </div>

        <section class="guide-grid">
            <div class="guide-step">
                <div class="guide-number">1</div>
                <h3>Buka Materi Web</h3>
                <p>Mulai dari materi pertama. Baca penjelasan dan amati contoh kode yang tersedia.</p>
            </div>

            <div class="guide-step">
                <div class="guide-number">2</div>
                <h3>Coba Live Coding</h3>
                <p>Gunakan fitur Live Coding untuk menulis kode web dan melihat hasilnya secara langsung.</p>
            </div>

            <div class="guide-step">
                <div class="guide-number">3</div>
                <h3>Simpan Latihan</h3>
                <p>Simpan hasil latihan coding agar muncul di riwayat belajar dan dapat dilihat guru.</p>
            </div>

            <div class="guide-step">
                <div class="guide-number">4</div>
                <h3>Kerjakan Kuis</h3>
                <p>Kerjakan kuis setelah mempelajari materi. Nilai minimal 75 akan menandai materi selesai otomatis.</p>
            </div>

            <div class="guide-step">
                <div class="guide-number">5</div>
                <h3>Lihat Riwayat</h3>
                <p>Pantau nilai, materi selesai, dan latihan coding yang sudah pernah kamu simpan.</p>
            </div>

            <div class="guide-step">
                <div class="guide-number">6</div>
                <h3>Cetak Sertifikat</h3>
                <p>Sertifikat dapat dicetak jika seluruh materi selesai dan rata-rata nilai minimal 75.</p>
            </div>
        </section>
    </main>
</div>
</body>
</html>
