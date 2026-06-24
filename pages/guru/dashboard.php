<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
include "../../includes/tahap22_bootstrap.php";
hanya_role('guru');

$nama_user = $_SESSION['user']['nama'] ?? 'Guru';
$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='siswa'"))['total'];
$total_materi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM materi"))['total'];
$total_kuis = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kuis"))['total'];
$rata_nilai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT AVG(skor) AS rata FROM nilai"))['rata'];
$total_absensi_hari_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM absensi WHERE tanggal=CURDATE()"))['total'];
$total_jadwal_mendatang = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM jadwal_kelas WHERE tanggal >= CURDATE()"))['total'];
$total_pengumpulan_tugas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengumpulan_tugas"))['total'];

$nilai = mysqli_query($koneksi, "
    SELECT nilai.*, users.nama, users.kelas, materi.judul_materi 
    FROM nilai 
    JOIN users ON nilai.id_user = users.id_user
    JOIN materi ON nilai.id_materi = materi.id_materi
    ORDER BY nilai.tanggal DESC
    LIMIT 4
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Guru | SmartLearn</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <header class="syntactic-topbar">
            <div class="syntactic-title">
                <h1>Dashboard Guru</h1>
                <p>Ringkasan kondisi pembelajaran dan aktivitas siswa.</p>
            </div>
            <div class="syntactic-search">
                <span class="material-symbols-outlined">search</span>
                <input type="text" placeholder="Cari aktivitas..." onfocus="window.location='rekap_aktivitas.php'">
            </div>
            <div class="syntactic-bell"><span class="material-symbols-outlined">notifications</span></div>
            <div class="syntactic-user">
                <div>
                    <strong><?= htmlspecialchars($nama_user); ?></strong>
                    <span>INSTRUKTUR UTAMA</span>
                </div>
                <div class="syntactic-avatar"><?= strtoupper(substr($nama_user,0,1)); ?></div>
            </div>
        </header>

        <section class="syntax-stat-grid">
            <div class="syntax-stat-card">
                <div class="syntax-stat-icon"><span class="material-symbols-outlined">groups</span></div>
                <span class="label">Total Siswa</span>
                <h2><?= number_format($total_siswa); ?></h2>
                <p class="small-muted" style="margin:10px 0 0;color:var(--syntax-secondary)!important;font-weight:800;">↗ +12% bln ini</p>
            </div>
            <div class="syntax-stat-card">
                <div class="syntax-stat-icon cyan"><span class="material-symbols-outlined">menu_book</span></div>
                <span class="label">Total Materi</span>
                <h2><?= number_format($total_materi); ?></h2>
                <p class="small-muted" style="margin:10px 0 0;">Bab terselesaikan</p>
            </div>
            <div class="syntax-stat-card">
                <div class="syntax-stat-icon purple"><span class="material-symbols-outlined">quiz</span></div>
                <span class="label">Total Soal</span>
                <h2><?= number_format($total_kuis); ?></h2>
                <p class="small-muted" style="margin:10px 0 0;">Bank soal terverifikasi</p>
            </div>
            <div class="syntax-stat-card" style="border-color:#c3c0ff!important;">
                <div class="syntax-stat-icon"><span class="material-symbols-outlined">insert_chart</span></div>
                <span class="label">Rata-rata Nilai</span>
                <h2><?= $rata_nilai ? round($rata_nilai,1) : 0; ?></h2>
                <p class="small-muted" style="margin:10px 0 0;color:var(--syntax-secondary)!important;font-weight:800;">↗ Unggul vs Target</p>
            </div>
        </section>

        <section class="syntax-grid-2">
            <div class="syntax-card">
                <div class="syntax-card-header">
                    <h3 style="font-size:18px;"><span class="material-symbols-outlined" style="vertical-align:middle;color:var(--syntax-primary);">bolt</span> Aktivitas Hari Ini</h3>
                </div>
                <div class="syntax-action-list">
                    <a class="syntax-action-item" href="absensi.php">
                        <span class="icon"><span class="material-symbols-outlined">how_to_reg</span></span>
                        <span><h4>Absensi Kelas</h4><p>Selesai: <?= $total_absensi_hari_ini; ?> siswa</p></span>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                    <a class="syntax-action-item" href="jadwal.php">
                        <span class="icon" style="background:#e2dfff;color:var(--syntax-primary);"><span class="material-symbols-outlined">schedule</span></span>
                        <span><h4>Jadwal: <?= $total_jadwal_mendatang; ?></h4><p>Agenda mendatang aktif</p></span>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                    <a class="syntax-action-item" href="kelola_tugas.php">
                        <span class="icon" style="background:#eee4ff;color:var(--syntax-purple);"><span class="material-symbols-outlined">assignment_turned_in</span></span>
                        <span><h4>Tugas Terkumpul</h4><p><?= $total_pengumpulan_tugas; ?> pengumpulan</p></span>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                    <a class="syntax-action-item" href="rekap_aktivitas.php">
                        <span class="icon" style="background:#eaf1ff;color:#586176;"><span class="material-symbols-outlined">history</span></span>
                        <span><h4>Rekap Aktivitas</h4><p>Monitoring siswa</p></span>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                </div>
            </div>

            <div class="syntax-card">
                <div class="syntax-card-header">
                    <h3 style="font-size:18px;"><span class="material-symbols-outlined" style="vertical-align:middle;color:var(--syntax-primary);">trending_up</span> Grafik Perkembangan Nilai Siswa</h3>
                    <div>
                        <span class="badge badge-primary">RERATA: <?= $rata_nilai ? round($rata_nilai,1) : 0; ?></span>
                        <span class="badge badge-success">KUANTITAS: TINGGI</span>
                    </div>
                </div>
                <div class="syntax-bar-chart">
                    <div class="syntax-bar" style="height:42%;opacity:.35;"></div>
                    <div class="syntax-bar" style="height:58%;opacity:.45;"></div>
                    <div class="syntax-bar" style="height:52%;opacity:.65;"></div>
                    <div class="syntax-bar" style="height:70%;opacity:.82;"></div>
                    <div class="syntax-bar" style="height:76%;opacity:1;"></div>
                </div>
            </div>
        </section>

        <section class="syntax-card" style="padding:0;overflow:hidden;">
            <div class="syntax-card-header" style="padding:22px;margin:0;background:#eef4ff;border-bottom:1px solid #d3d7e8;">
                <h3 style="font-size:18px;">Riwayat Nilai Terbaru</h3>
                <a class="syntax-link" href="laporan.php">Lihat Semua Data</a>
            </div>

            <div class="table-wrapper" style="border:0;border-radius:0;box-shadow:none;margin:0;">
                <table class="syntax-table">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Materi Pelajaran</th>
                            <th>Nilai</th>
                            <th>Waktu Pengerjaan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$nilai || mysqli_num_rows($nilai) == 0): ?>
                            <tr><td colspan="6">Belum ada nilai terbaru.</td></tr>
                        <?php endif; ?>
                        <?php if ($nilai): ?>
                            <?php while($row = mysqli_fetch_assoc($nilai)): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($row['nama']); ?></strong></td>
                                    <td><?= htmlspecialchars($row['kelas'] ?: '-'); ?></td>
                                    <td><?= htmlspecialchars($row['judul_materi']); ?></td>
                                    <td><strong style="color:<?= $row['skor'] >= 75 ? 'var(--syntax-secondary)' : '#dc2626'; ?>"><?= $row['skor']; ?></strong></td>
                                    <td><?= $row['tanggal']; ?></td>
                                    <td><span class="syntax-status <?= $row['skor'] >= 75 ? '' : 'remedial'; ?>"><?= $row['skor'] >= 75 ? 'PASSED' : 'REMEDIAL'; ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <a href="kelola_materi.php" class="fab-add" title="Tambah materi"><span class="material-symbols-outlined">add</span></a>
    </main>
</div>
</body>
</html>
