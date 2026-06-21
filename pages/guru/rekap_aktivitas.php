<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
include "../../includes/tahap22_bootstrap.php";
hanya_role('guru');

$kelas = mysqli_real_escape_string($koneksi, $_GET['kelas'] ?? '');
$keyword = mysqli_real_escape_string($koneksi, $_GET['keyword'] ?? '');

$where = "WHERE users.role='siswa'";
if ($kelas != "") $where .= " AND users.kelas='$kelas'";
if ($keyword != "") $where .= " AND (users.nama LIKE '%$keyword%' OR users.email LIKE '%$keyword%')";

$total_materi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM materi"))['total'];

$data = mysqli_query($koneksi, "
    SELECT 
        users.id_user,
        users.nama,
        users.email,
        users.kelas,
        COALESCE(p.materi_selesai,0) AS materi_selesai,
        COALESCE(n.rata_nilai,0) AS rata_nilai,
        COALESCE(t.total_tugas,0) AS total_tugas,
        COALESCE(l.total_latihan,0) AS total_latihan,
        COALESCE(a.total_absensi,0) AS total_absensi,
        COALESCE(g.skor_game,0) AS skor_game,
        GREATEST(
            COALESCE(p.last_activity,'1970-01-01'),
            COALESCE(n.last_activity,'1970-01-01'),
            COALESCE(t.last_activity,'1970-01-01'),
            COALESCE(l.last_activity,'1970-01-01'),
            COALESCE(a.last_activity,'1970-01-01')
        ) AS aktivitas_terakhir
    FROM users
    LEFT JOIN (
        SELECT id_user, COUNT(DISTINCT id_materi) AS materi_selesai, MAX(tanggal_selesai) AS last_activity
        FROM progres_belajar
        WHERE status='selesai'
        GROUP BY id_user
    ) p ON users.id_user=p.id_user
    LEFT JOIN (
        SELECT id_user, ROUND(AVG(skor)) AS rata_nilai, MAX(tanggal) AS last_activity
        FROM nilai
        GROUP BY id_user
    ) n ON users.id_user=n.id_user
    LEFT JOIN (
        SELECT id_user, COUNT(*) AS total_tugas, MAX(tanggal_upload) AS last_activity
        FROM pengumpulan_tugas
        GROUP BY id_user
    ) t ON users.id_user=t.id_user
    LEFT JOIN (
        SELECT id_user, COUNT(*) AS total_latihan, MAX(tanggal_simpan) AS last_activity
        FROM latihan_kode
        GROUP BY id_user
    ) l ON users.id_user=l.id_user
    LEFT JOIN (
        SELECT id_user, COUNT(*) AS total_absensi, MAX(created_at) AS last_activity
        FROM absensi
        GROUP BY id_user
    ) a ON users.id_user=a.id_user
    LEFT JOIN (
        SELECT id_user, MAX(skor) AS skor_game
        FROM game_scores
        GROUP BY id_user
    ) g ON users.id_user=g.id_user
    $where
    ORDER BY aktivitas_terakhir DESC, users.nama ASC
");

$kelas_list = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM users WHERE role='siswa' AND kelas IS NOT NULL AND kelas!='' ORDER BY kelas ASC");

$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='siswa'"))['total'];
$total_absensi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM absensi"))['total'];
$total_pengumpulan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengumpulan_tugas"))['total'];
$total_jadwal = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM jadwal_kelas WHERE tanggal >= CURDATE()"))['total'];

$top_progress = mysqli_query($koneksi, "
    SELECT users.nama, COUNT(DISTINCT progres_belajar.id_materi) AS materi_selesai
    FROM users
    LEFT JOIN progres_belajar ON users.id_user=progres_belajar.id_user AND progres_belajar.status='selesai'
    WHERE users.role='siswa'
    GROUP BY users.id_user, users.nama
    ORDER BY materi_selesai DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Aktivitas Belajar</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Rekap Aktivitas Belajar</h1>
                <p>Ringkasan aktivitas siswa dari materi, kuis, tugas, live coding, game, dan absensi.</p>
            </div>
            <button class="btn btn-primary" onclick="window.print()">Export PDF</button>
        </div>

        <section class="stats-grid">
            <div class="stat-card"><span>Total Siswa</span><h2><?= $total_siswa; ?></h2></div>
            <div class="stat-card"><span>Total Absensi</span><h2><?= $total_absensi; ?></h2></div>
            <div class="stat-card"><span>Tugas Terkumpul</span><h2><?= $total_pengumpulan; ?></h2></div>
            <div class="stat-card"><span>Jadwal Mendatang</span><h2><?= $total_jadwal; ?></h2></div>
        </section>

        <section class="content-grid">
            <div>
                <div class="filter-card no-print">
                    <form method="GET" class="filter-form">
                        <div class="form-group">
                            <label>Cari Siswa</label>
                            <input type="text" name="keyword" class="form-control" value="<?= htmlspecialchars($keyword); ?>" placeholder="Cari nama/email...">
                        </div>
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
                        <button class="btn btn-primary" type="submit">Filter</button>
                        <a href="rekap_aktivitas.php" class="btn btn-outline">Reset</a>
                    </form>
                </div>

                <div class="student-clean-card" style="margin-top:18px;">
                    <h3>Top Progress Materi</h3>
                    <p class="small-muted">Siswa dengan materi selesai terbanyak.</p>
                    <br>
                    <div class="simple-bar-chart">
                        <?php while($top = mysqli_fetch_assoc($top_progress)): ?>
                            <?php $p = $total_materi > 0 ? round(($top['materi_selesai'] / $total_materi) * 100) : 0; ?>
                            <div class="chart-row">
                                <div class="chart-label"><?= htmlspecialchars($top['nama']); ?></div>
                                <div class="chart-track"><div class="chart-fill" style="width:<?= $p; ?>%;"></div></div>
                                <strong><?= $p; ?>%</strong>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <div class="student-clean-card">
                <h3>Aktivitas Siswa</h3>
                <p class="small-muted">Data digabung dari seluruh fitur utama SmartLearn.</p>
                <br>

                <?php if (!$data || mysqli_num_rows($data) == 0): ?>
                    <div class="empty-state">Belum ada data aktivitas.</div>
                <?php endif; ?>

                <?php if ($data): ?>
                    <div class="recap-list">
                        <?php while($row = mysqli_fetch_assoc($data)): ?>
                            <?php
                                $progress = $total_materi > 0 ? round(($row['materi_selesai'] / $total_materi) * 100) : 0;
                                $aktivitas = $row['aktivitas_terakhir'] && $row['aktivitas_terakhir'] != '1970-01-01' ? $row['aktivitas_terakhir'] : '-';
                            ?>
                            <div class="recap-card">
                                <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                                    <div>
                                        <span class="badge badge-primary"><?= htmlspecialchars($row['kelas'] ?: '-'); ?></span>
                                        <h3><?= htmlspecialchars($row['nama']); ?></h3>
                                        <p class="small-muted"><?= htmlspecialchars($row['email']); ?><br>Aktivitas terakhir: <?= $aktivitas; ?></p>
                                    </div>
                                    <div style="min-width:160px;">
                                        <div class="clean-progress"><span style="width:<?= $progress; ?>%;"></span></div>
                                        <p class="small-muted" style="margin-top:8px;">Progress materi <?= $progress; ?>%</p>
                                    </div>
                                </div>

                                <div class="recap-grid">
                                    <div class="recap-mini"><span>Materi</span><strong><?= $row['materi_selesai']; ?></strong></div>
                                    <div class="recap-mini"><span>Nilai</span><strong><?= $row['rata_nilai']; ?></strong></div>
                                    <div class="recap-mini"><span>Tugas</span><strong><?= $row['total_tugas']; ?></strong></div>
                                    <div class="recap-mini"><span>Live Coding</span><strong><?= $row['total_latihan']; ?></strong></div>
                                    <div class="recap-mini"><span>Absensi</span><strong><?= $row['total_absensi']; ?></strong></div>
                                    <div class="recap-mini"><span>Game</span><strong><?= $row['skor_game']; ?></strong></div>
                                </div>
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
