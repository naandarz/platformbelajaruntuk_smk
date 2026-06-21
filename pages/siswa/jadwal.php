<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
include "../../includes/tahap22_bootstrap.php";
hanya_role('siswa');

$id_user = $_SESSION['user']['id_user'];
$kelas = mysqli_real_escape_string($koneksi, $_SESSION['user']['kelas'] ?? '');

$jadwal = mysqli_query($koneksi, "
    SELECT jadwal_kelas.*, materi.judul_materi, users.nama AS pembuat
    FROM jadwal_kelas
    LEFT JOIN materi ON jadwal_kelas.id_materi = materi.id_materi
    LEFT JOIN users ON jadwal_kelas.dibuat_oleh = users.id_user
    WHERE jadwal_kelas.tanggal >= CURDATE()
      AND (jadwal_kelas.kelas IS NULL OR jadwal_kelas.kelas='' OR jadwal_kelas.kelas='$kelas')
    ORDER BY jadwal_kelas.tanggal ASC, jadwal_kelas.jam_mulai ASC
    LIMIT 25
");

$jadwal_terlewat = mysqli_query($koneksi, "
    SELECT jadwal_kelas.*, materi.judul_materi
    FROM jadwal_kelas
    LEFT JOIN materi ON jadwal_kelas.id_materi = materi.id_materi
    WHERE jadwal_kelas.tanggal < CURDATE()
      AND (jadwal_kelas.kelas IS NULL OR jadwal_kelas.kelas='' OR jadwal_kelas.kelas='$kelas')
    ORDER BY jadwal_kelas.tanggal DESC, jadwal_kelas.jam_mulai DESC
    LIMIT 5
");

$jadwal_hari_ini_count = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COUNT(*) AS total FROM jadwal_kelas
    WHERE tanggal=CURDATE()
      AND (kelas IS NULL OR kelas='' OR kelas='$kelas')
"))['total'];

$jadwal_minggu_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COUNT(*) AS total FROM jadwal_kelas
    WHERE tanggal BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
      AND (kelas IS NULL OR kelas='' OR kelas='$kelas')
"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Kelas</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Jadwal Kelas</h1>
                <p>Lihat jadwal pembelajaran, praktik, kuis, dan tugas yang sesuai dengan kelasmu.</p>
            </div>
            <a href="absensi.php" class="btn btn-outline">Absensi</a>
        </div>

        <section class="stats-grid">
            <div class="stat-card"><span>Kelas</span><h2><?= htmlspecialchars($_SESSION['user']['kelas'] ?: '-'); ?></h2></div>
            <div class="stat-card"><span>Jadwal Hari Ini</span><h2><?= $jadwal_hari_ini_count; ?></h2></div>
            <div class="stat-card"><span>7 Hari Ke Depan</span><h2><?= $jadwal_minggu_ini; ?></h2></div>
            <div class="stat-card"><span>Status</span><h2>Aktif</h2></div>
        </section>

        <section class="student-page-grid">
            <div class="student-clean-card">
                <h3>Jadwal Mendatang</h3>
                <p class="small-muted">Urutan jadwal berdasarkan tanggal terdekat.</p>
                <br>

                <?php if (!$jadwal || mysqli_num_rows($jadwal) == 0): ?>
                    <div class="empty-state">Belum ada jadwal mendatang.</div>
                <?php endif; ?>

                <?php if ($jadwal): ?>
                    <div class="schedule-list">
                        <?php while($j = mysqli_fetch_assoc($jadwal)): ?>
                            <div class="schedule-card schedule-timeline">
                                <span class="timeline-dot"></span>
                                <span class="badge badge-primary"><?= htmlspecialchars($j['kategori']); ?></span>
                                <h3><?= htmlspecialchars($j['judul']); ?></h3>
                                <div class="schedule-meta">
                                    <span class="badge badge-neutral">📅 <?= date('d M Y', strtotime($j['tanggal'])); ?></span>
                                    <span class="badge badge-success">⏰ <?= substr($j['jam_mulai'],0,5); ?> - <?= substr($j['jam_selesai'],0,5); ?></span>
                                    <span class="badge badge-warning">🏫 <?= htmlspecialchars($j['kelas'] ?: 'Semua Kelas'); ?></span>
                                </div>
                                <p class="small-muted">
                                    Materi: <?= htmlspecialchars($j['judul_materi'] ?: '-'); ?><br>
                                    Keterangan: <?= nl2br(htmlspecialchars($j['keterangan'] ?: '-')); ?>
                                </p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>

            <aside class="student-clean-card">
                <h3>Jadwal Terlewat</h3>
                <p class="small-muted">Riwayat 5 jadwal terakhir yang sudah lewat.</p>
                <br>

                <?php if (!$jadwal_terlewat || mysqli_num_rows($jadwal_terlewat) == 0): ?>
                    <div class="empty-state">Belum ada jadwal terlewat.</div>
                <?php endif; ?>

                <?php if ($jadwal_terlewat): ?>
                    <div class="schedule-list">
                        <?php while($j = mysqli_fetch_assoc($jadwal_terlewat)): ?>
                            <div class="schedule-card">
                                <span class="badge badge-neutral"><?= date('d M Y', strtotime($j['tanggal'])); ?></span>
                                <h3><?= htmlspecialchars($j['judul']); ?></h3>
                                <p class="small-muted"><?= htmlspecialchars($j['judul_materi'] ?: '-'); ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </aside>
        </section>
    </main>
</div>
</body>
</html>
