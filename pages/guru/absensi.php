<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
include "../../includes/tahap22_bootstrap.php";
hanya_role('guru');

$tanggal = mysqli_real_escape_string($koneksi, $_GET['tanggal'] ?? date('Y-m-d'));
$kelas = mysqli_real_escape_string($koneksi, $_GET['kelas'] ?? '');
$status_filter = mysqli_real_escape_string($koneksi, $_GET['status'] ?? '');

$whereUser = "WHERE users.role='siswa'";
if ($kelas != "") $whereUser .= " AND users.kelas='$kelas'";

$whereStatus = "";
if ($status_filter == 'hadir') $whereStatus = "AND absensi.id_absensi IS NOT NULL";
if ($status_filter == 'belum') $whereStatus = "AND absensi.id_absensi IS NULL";

$data = mysqli_query($koneksi, "
    SELECT users.id_user, users.nama, users.kelas, absensi.status, absensi.catatan, absensi.created_at
    FROM users
    LEFT JOIN absensi ON users.id_user = absensi.id_user AND absensi.tanggal='$tanggal'
    $whereUser
    $whereStatus
    ORDER BY users.kelas ASC, users.nama ASC
");

$kelas_list = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM users WHERE role='siswa' AND kelas IS NOT NULL AND kelas!='' ORDER BY kelas ASC");

$total_siswa_q = "SELECT COUNT(*) AS total FROM users $whereUser";
$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, $total_siswa_q))['total'];

$hadir_q = "
    SELECT COUNT(*) AS total 
    FROM users 
    JOIN absensi ON users.id_user=absensi.id_user AND absensi.tanggal='$tanggal'
    $whereUser
";
$total_hadir = mysqli_fetch_assoc(mysqli_query($koneksi, $hadir_q))['total'];
$total_belum = max(0, $total_siswa - $total_hadir);
$persen = $total_siswa > 0 ? round(($total_hadir / $total_siswa) * 100) : 0;

$grafik = mysqli_query($koneksi, "
    SELECT users.kelas, COUNT(users.id_user) AS total_siswa,
        SUM(CASE WHEN absensi.id_absensi IS NOT NULL THEN 1 ELSE 0 END) AS total_hadir
    FROM users
    LEFT JOIN absensi ON users.id_user=absensi.id_user AND absensi.tanggal='$tanggal'
    WHERE users.role='siswa'
    GROUP BY users.kelas
    ORDER BY users.kelas ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Rekap Absensi</h1>
                <p>Pantau kehadiran siswa berdasarkan tanggal dan kelas.</p>
            </div>
            <button class="btn btn-primary" onclick="window.print()">Export PDF</button>
        </div>

        <section class="stats-grid">
            <div class="stat-card"><span>Total Siswa</span><h2><?= $total_siswa; ?></h2></div>
            <div class="stat-card"><span>Hadir</span><h2><?= $total_hadir; ?></h2></div>
            <div class="stat-card"><span>Belum Absen</span><h2><?= $total_belum; ?></h2></div>
            <div class="stat-card"><span>Persentase</span><h2><?= $persen; ?>%</h2></div>
        </section>

        <section class="content-grid">
            <div>
                <div class="filter-card no-print">
                    <form method="GET" class="filter-form">
                        <div class="form-group"><label>Tanggal</label><input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($tanggal); ?>"></div>
                        <div class="form-group">
                            <label>Kelas</label>
                            <select name="kelas" class="form-control">
                                <option value="">Semua Kelas</option>
                                <?php while($k = mysqli_fetch_assoc($kelas_list)): ?>
                                    <option value="<?= htmlspecialchars($k['kelas']); ?>" <?= $kelas == $k['kelas'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($k['kelas']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">Semua</option>
                                <option value="hadir" <?= $status_filter == 'hadir' ? 'selected' : ''; ?>>Hadir</option>
                                <option value="belum" <?= $status_filter == 'belum' ? 'selected' : ''; ?>>Belum Absen</option>
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit">Filter</button>
                        <a href="absensi.php" class="btn btn-outline">Reset</a>
                    </form>
                </div>

                <div class="student-clean-card" style="margin-top:18px;">
                    <h3>Grafik Kehadiran per Kelas</h3>
                    <p class="small-muted">Grafik berdasarkan tanggal yang dipilih.</p>
                    <br>

                    <div class="simple-bar-chart">
                        <?php while($g = mysqli_fetch_assoc($grafik)): ?>
                            <?php $p = $g['total_siswa'] > 0 ? round(($g['total_hadir'] / $g['total_siswa']) * 100) : 0; ?>
                            <div class="chart-row">
                                <div class="chart-label"><?= htmlspecialchars($g['kelas'] ?: 'Tanpa Kelas'); ?></div>
                                <div class="chart-track"><div class="chart-fill" style="width:<?= $p; ?>%;"></div></div>
                                <strong><?= $p; ?>%</strong>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <div class="student-clean-card">
                <h3>Daftar Absensi Siswa</h3>
                <p class="small-muted">Tanggal: <?= date('d M Y', strtotime($tanggal)); ?></p>
                <br>

                <?php if (!$data || mysqli_num_rows($data) == 0): ?>
                    <div class="empty-state">Tidak ada data sesuai filter.</div>
                <?php endif; ?>

                <?php if ($data): ?>
                    <div class="attendance-list">
                        <?php while($row = mysqli_fetch_assoc($data)): ?>
                            <div class="attendance-card">
                                <span class="attendance-status <?= $row['status'] ? 'hadir' : 'belum'; ?>">
                                    <?= $row['status'] ? '✓ Hadir' : '× Belum Absen'; ?>
                                </span>
                                <h3><?= htmlspecialchars($row['nama']); ?></h3>
                                <p class="small-muted">
                                    Kelas: <?= htmlspecialchars($row['kelas'] ?: '-'); ?><br>
                                    Waktu: <?= $row['created_at'] ?: '-'; ?><br>
                                    Catatan: <?= htmlspecialchars($row['catatan'] ?: '-'); ?>
                                </p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>
</body>
</html>
