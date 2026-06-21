<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
include "../../includes/tahap22_bootstrap.php";
hanya_role('guru');

$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='siswa'"))['total'];
$total_materi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM materi"))['total'];
$total_kuis = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kuis"))['total'];
$rata_nilai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT AVG(skor) AS rata FROM nilai"))['rata'];
$total_absensi_hari_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM absensi WHERE tanggal=CURDATE()"))['total'];
$total_jadwal_mendatang = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM jadwal_kelas WHERE tanggal >= CURDATE()"))['total'];
$total_pengumpulan_tugas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengumpulan_tugas"))['total'];

$grafik_siswa = mysqli_query($koneksi, "
    SELECT users.nama, COALESCE(ROUND(AVG(nilai.skor)), 0) AS rata_nilai
    FROM users
    LEFT JOIN nilai ON users.id_user = nilai.id_user
    WHERE users.role='siswa'
    GROUP BY users.id_user, users.nama
    ORDER BY rata_nilai DESC
    LIMIT 5
");

$nilai = mysqli_query($koneksi, "
    SELECT nilai.*, users.nama, users.kelas, materi.judul_materi 
    FROM nilai 
    JOIN users ON nilai.id_user = users.id_user
    JOIN materi ON nilai.id_materi = materi.id_materi
    ORDER BY nilai.tanggal DESC
    LIMIT 8
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Guru</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Dashboard Guru</h1>
                <p>Pantau aktivitas belajar siswa dan kelola pembelajaran web dasar.</p>
            </div>
            <div class="user-pill">
                <div class="avatar">G</div>
                Guru
            </div>
        </div>

        <section class="stats-grid">
            <div class="stat-card"><span>Total Siswa</span><h2><?= $total_siswa; ?></h2></div>
            <div class="stat-card"><span>Total Materi</span><h2><?= $total_materi; ?></h2></div>
            <div class="stat-card"><span>Total Soal</span><h2><?= $total_kuis; ?></h2></div>
            <div class="stat-card"><span>Rata-rata Nilai</span><h2><?= $rata_nilai ? round($rata_nilai) : 0; ?></h2></div>
        </section>

        <div class="chart-card">
            <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <div>
                    <h3>Grafik Nilai Siswa</h3>
                    <p>Menampilkan 5 siswa dengan rata-rata nilai tertinggi.</p>
                </div>
                <div class="page-actions no-print">
                    <a href="ranking.php" class="btn btn-primary">Lihat Ranking</a>
                    <button class="btn btn-outline" onclick="window.print()">Export PDF</button>
                </div>
            </div>

            <div class="simple-bar-chart">
                <?php while($g = mysqli_fetch_assoc($grafik_siswa)): ?>
                    <div class="chart-row">
                        <div class="chart-label"><?= htmlspecialchars($g['nama']); ?></div>
                        <div class="chart-track"><div class="chart-fill" style="width:<?= $g['rata_nilai']; ?>%;"></div></div>
                        <strong><?= $g['rata_nilai']; ?></strong>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <section class="stats-grid no-print">
            <a class="stat-card" href="kelola_tugas.php" style="text-decoration:none;"><span>Tugas</span><h2>📚</h2></a>
            <a class="stat-card" href="forum.php" style="text-decoration:none;"><span>Forum</span><h2>💬</h2></a>
            <a class="stat-card" href="ranking.php" style="text-decoration:none;"><span>Ranking</span><h2>🏆</h2></a>
            <a class="stat-card" href="import_soal_word.php" style="text-decoration:none;"><span>Import Soal</span><h2>📄</h2></a>
        </section>

        <section class="stats-grid">
            <a class="stat-card" href="absensi.php" style="text-decoration:none;"><span>Absensi Hari Ini</span><h2><?= $total_absensi_hari_ini; ?></h2></a>
            <a class="stat-card" href="jadwal.php" style="text-decoration:none;"><span>Jadwal Mendatang</span><h2><?= $total_jadwal_mendatang; ?></h2></a>
            <a class="stat-card" href="kelola_tugas.php" style="text-decoration:none;"><span>Tugas Terkumpul</span><h2><?= $total_pengumpulan_tugas; ?></h2></a>
            <a class="stat-card" href="rekap_aktivitas.php" style="text-decoration:none;"><span>Rekap Aktivitas</span><h2>📊</h2></a>
        </section>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Materi</th>
                        <th>Nilai</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($nilai)): ?>
                        <tr>
                            <td><?= $row['nama']; ?></td>
                            <td><?= $row['kelas']; ?></td>
                            <td><?= $row['judul_materi']; ?></td>
                            <td><span class="badge badge-primary"><?= $row['skor']; ?></span></td>
                            <td><?= $row['tanggal']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>
