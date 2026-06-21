<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
include "../../includes/tahap22_bootstrap.php";
hanya_role('siswa');

$id_user = $_SESSION['user']['id_user'];
$today = date('Y-m-d');
$pesan = "";
$error = "";

if (isset($_POST['absen'])) {
    $catatan = mysqli_real_escape_string($koneksi, $_POST['catatan'] ?? '');
    mysqli_query($koneksi, "
        INSERT INTO absensi (id_user, tanggal, status, catatan)
        VALUES ($id_user, '$today', 'hadir', '$catatan')
        ON DUPLICATE KEY UPDATE status='hadir', catatan='$catatan', created_at=CURRENT_TIMESTAMP
    ");
    header("Location: absensi.php?status=hadir");
    exit;
}

if (isset($_GET['status']) && $_GET['status'] == 'hadir') {
    $pesan = "Absensi hari ini berhasil disimpan.";
}

$absen_hari_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT * FROM absensi WHERE id_user=$id_user AND tanggal='$today'
"));

$riwayat = mysqli_query($koneksi, "
    SELECT * FROM absensi
    WHERE id_user=$id_user
    ORDER BY tanggal DESC, created_at DESC
    LIMIT 12
");

$total_hadir = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM absensi WHERE id_user=$id_user AND status='hadir'"))['total'];
$total_bulan_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM absensi WHERE id_user=$id_user AND MONTH(tanggal)=MONTH(CURDATE()) AND YEAR(tanggal)=YEAR(CURDATE())"))['total'];

$jadwal_hari_ini = mysqli_query($koneksi, "
    SELECT jadwal_kelas.*, materi.judul_materi
    FROM jadwal_kelas
    LEFT JOIN materi ON jadwal_kelas.id_materi = materi.id_materi
    WHERE jadwal_kelas.tanggal='$today'
      AND (jadwal_kelas.kelas IS NULL OR jadwal_kelas.kelas='' OR jadwal_kelas.kelas='{$_SESSION['user']['kelas']}')
    ORDER BY jadwal_kelas.jam_mulai ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi Siswa</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Absensi</h1>
                <p>Catat kehadiran harian dan lihat riwayat absensimu.</p>
            </div>
            <a href="jadwal.php" class="btn btn-outline">Lihat Jadwal</a>
        </div>

        <?php if ($pesan): ?><div class="alert alert-success"><?= $pesan; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error; ?></div><?php endif; ?>

        <section class="student-page-grid">
            <div class="clean-stack">
                <div class="attendance-hero">
                    <span class="badge badge-success"><?= date('d M Y'); ?></span>
                    <h2 style="margin-top:14px;">Absensi Hari Ini</h2>
                    <p>
                        <?php if ($absen_hari_ini): ?>
                            Kamu sudah melakukan absensi hari ini pada <?= $absen_hari_ini['created_at']; ?>.
                        <?php else: ?>
                            Klik tombol hadir untuk mencatat kehadiran hari ini.
                        <?php endif; ?>
                    </p>

                    <form method="POST" style="margin-top:18px;">
                        <div class="form-group">
                            <label style="color:white;">Catatan Opsional</label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: hadir mengikuti pembelajaran hari ini..."><?= htmlspecialchars($absen_hari_ini['catatan'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" name="absen" class="btn btn-primary">
                            <?= $absen_hari_ini ? 'Update Absensi' : 'Saya Hadir Hari Ini'; ?>
                        </button>
                    </form>
                </div>

                <div class="student-clean-card">
                    <h3>Riwayat Absensi</h3>
                    <p class="small-muted">Menampilkan 12 data absensi terbaru.</p>
                    <br>

                    <div class="attendance-list">
                        <?php if (!$riwayat || mysqli_num_rows($riwayat) == 0): ?>
                            <div class="empty-state">Belum ada riwayat absensi.</div>
                        <?php endif; ?>

                        <?php if ($riwayat): ?>
                            <?php while($r = mysqli_fetch_assoc($riwayat)): ?>
                                <div class="attendance-card">
                                    <span class="attendance-status hadir">✓ Hadir</span>
                                    <h3><?= date('d M Y', strtotime($r['tanggal'])); ?></h3>
                                    <p class="small-muted">
                                        Waktu absen: <?= $r['created_at']; ?><br>
                                        Catatan: <?= htmlspecialchars($r['catatan'] ?: '-'); ?>
                                    </p>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <aside class="clean-stack">
                <div class="student-clean-card">
                    <h3>Ringkasan Absensi</h3>
                    <div class="student-summary-list">
                        <div class="student-summary-item"><span>Total Hadir</span><strong><?= $total_hadir; ?></strong></div>
                        <div class="student-summary-item"><span>Bulan Ini</span><strong><?= $total_bulan_ini; ?></strong></div>
                        <div class="student-summary-item">
                            <span>Status Hari Ini</span>
                            <strong><?= $absen_hari_ini ? 'Hadir' : 'Belum'; ?></strong>
                        </div>
                    </div>
                </div>

                <div class="student-clean-card">
                    <h3>Jadwal Hari Ini</h3>
                    <p class="small-muted">Jadwal yang sesuai dengan kelasmu.</p>
                    <br>

                    <?php if (!$jadwal_hari_ini || mysqli_num_rows($jadwal_hari_ini) == 0): ?>
                        <div class="empty-state">Tidak ada jadwal hari ini.</div>
                    <?php endif; ?>

                    <?php if ($jadwal_hari_ini): ?>
                        <div class="schedule-list">
                            <?php while($j = mysqli_fetch_assoc($jadwal_hari_ini)): ?>
                                <div class="schedule-card">
                                    <span class="badge badge-primary"><?= htmlspecialchars($j['kategori']); ?></span>
                                    <h3><?= htmlspecialchars($j['judul']); ?></h3>
                                    <p class="small-muted">
                                        <?= substr($j['jam_mulai'],0,5); ?> - <?= substr($j['jam_selesai'],0,5); ?><br>
                                        Materi: <?= htmlspecialchars($j['judul_materi'] ?: '-'); ?>
                                    </p>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </aside>
        </section>
    </main>
</div>
</body>
</html>
