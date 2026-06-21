<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$aspek = [
    "Multimedia pembelajaran ini mudah digunakan oleh siswa dan guru.",
    "Menu dan materi dalam multimedia ini mudah ditemukan.",
    "Tampilan multimedia ini menarik, rapi, dan dapat diakses di berbagai perangkat.",
    "Materi Web yang disajikan lengkap dan mudah dipahami.",
    "Informasi dan penjelasan dalam multimedia ini jelas, akurat, dan bermanfaat.",
    "Tulisan, teks, dan visualisasi dalam multimedia ini jelas dan mudah dibaca.",
    "Fitur latihan dan kuis membantu siswa memahami materi web.",
    "Fitur live coding memudahkan siswa berlatih menulis kode web secara langsung.",
    "Multimedia ini mendorong siswa belajar HTML secara mandiri dan aktif.",
    "Secara keseluruhan, pengguna puas dengan multimedia pembelajaran web dasar ini."
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Instrumen Penilaian</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Instrumen Penilaian Produk</h1>
                <p>Format penilaian kelayakan media menggunakan skala Likert 1 sampai 5.</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary" onclick="window.print()">Cetak Instrumen</button>
            </div>
        </div>

        <div class="notice-box" style="margin-bottom:18px;">
            <strong>Keterangan Skor:</strong><br>
            5 = Sangat Setuju, 4 = Setuju, 3 = Netral, 2 = Tidak Setuju, 1 = Sangat Tidak Setuju.
        </div>

        <div class="table-wrapper">
            <table class="instrument-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Aspek Penilaian</th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($aspek as $i => $item): ?>
                        <tr>
                            <td><?= $i + 1; ?></td>
                            <td><?= $item; ?></td>
                            <td><input type="radio" name="aspek<?= $i; ?>"></td>
                            <td><input type="radio" name="aspek<?= $i; ?>"></td>
                            <td><input type="radio" name="aspek<?= $i; ?>"></td>
                            <td><input type="radio" name="aspek<?= $i; ?>"></td>
                            <td><input type="radio" name="aspek<?= $i; ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="card" style="margin-top:18px;">
            <h3>Rumus Perhitungan Rata-rata</h3>
            <p>Skor rata-rata dihitung menggunakan rumus: x̄ = Σx / n</p>
            <p>Keterangan: x̄ adalah skor rata-rata, Σx adalah jumlah skor penilaian, dan n adalah jumlah responden.</p>
        </div>
    </main>
</div>
</body>
</html>
