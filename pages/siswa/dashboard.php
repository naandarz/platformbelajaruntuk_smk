<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
include "../../includes/tahap22_bootstrap.php";
hanya_role('siswa');

$id_user = $_SESSION['user']['id_user'];
$total_materi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM materi"))['total'];
$total_selesai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM progres_belajar WHERE id_user=$id_user AND status='selesai'"))['total'];
$rata_nilai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT AVG(skor) AS rata FROM nilai WHERE id_user=$id_user"))['rata'];
$total_latihan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM latihan_kode WHERE id_user=$id_user"))['total'];
$best_game = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT MAX(skor) AS skor FROM game_scores WHERE id_user=$id_user"))['skor'];
$total_tugas_kumpul = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengumpulan_tugas WHERE id_user=$id_user"))['total'];
$persen = $total_materi > 0 ? round(($total_selesai / $total_materi) * 100) : 0;
$today = date('Y-m-d');
$kelas_siswa = mysqli_real_escape_string($koneksi, $_SESSION['user']['kelas'] ?? '');
$absen_hari_ini_dash = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM absensi WHERE id_user=$id_user AND tanggal='$today'"));
$jadwal_terdekat_dash = mysqli_query($koneksi, "
    SELECT * FROM jadwal_kelas
    WHERE tanggal >= CURDATE()
      AND (kelas IS NULL OR kelas='' OR kelas='$kelas_siswa')
    ORDER BY tanggal ASC, jam_mulai ASC
    LIMIT 3
");

$materi = mysqli_query($koneksi, "SELECT * FROM materi ORDER BY urutan ASC LIMIT 5");

$materi_lanjut = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT materi.*
    FROM materi
    LEFT JOIN progres_belajar 
        ON materi.id_materi = progres_belajar.id_materi 
        AND progres_belajar.id_user = $id_user 
        AND progres_belajar.status='selesai'
    WHERE progres_belajar.id_progres IS NULL
    ORDER BY materi.urutan ASC
    LIMIT 1
"));

$latihan_terakhir = mysqli_query($koneksi, "
    SELECT latihan_kode.*, materi.judul_materi
    FROM latihan_kode
    JOIN materi ON latihan_kode.id_materi = materi.id_materi
    WHERE latihan_kode.id_user=$id_user
    ORDER BY latihan_kode.tanggal_simpan DESC
    LIMIT 3
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Dashboard Siswa</h1>
                <p>Selamat datang, <?= $_SESSION['user']['nama']; ?>. Lanjutkan belajar web dasar hari ini.</p>
            </div>
            <div class="user-pill">
                <div class="avatar"><?= strtoupper(substr($_SESSION['user']['nama'], 0, 1)); ?></div>
                <?= $_SESSION['user']['role']; ?>
            </div>
        </div>

        <section class="stats-grid">
            <div class="stat-card">
                <span>Total Materi</span>
                <h2><?= $total_materi; ?></h2>
            </div>
            <div class="stat-card">
                <span>Materi Selesai</span>
                <h2><?= $total_selesai; ?></h2>
            </div>
            <div class="stat-card">
                <span>Progress</span>
                <h2><?= $persen; ?>%</h2>
            </div>
            <div class="stat-card">
                <span>Latihan Coding</span>
                <h2><?= $total_latihan; ?></h2>
            </div>
            <div class="stat-card">
                <span>Skor Game</span>
                <h2><?= $best_game ? $best_game : 0; ?></h2>
            </div>
        </section>

        <div class="chart-card">
            <h3>Grafik Perkembangan Saya</h3>
            <p>Ringkasan visual progress belajar, nilai, game, dan tugas.</p>
            <div class="simple-bar-chart">
                <div class="chart-row">
                    <div class="chart-label">Progress</div>
                    <div class="chart-track"><div class="chart-fill" style="width:<?= $persen; ?>%;"></div></div>
                    <strong><?= $persen; ?>%</strong>
                </div>
                <div class="chart-row">
                    <div class="chart-label">Nilai</div>
                    <div class="chart-track"><div class="chart-fill" style="width:<?= $rata_nilai ? round($rata_nilai) : 0; ?>%;"></div></div>
                    <strong><?= $rata_nilai ? round($rata_nilai) : 0; ?></strong>
                </div>
                <div class="chart-row">
                    <div class="chart-label">Game</div>
                    <div class="chart-track"><div class="chart-fill" style="width:<?= min(100, ($best_game ? round($best_game / 2) : 0)); ?>%;"></div></div>
                    <strong><?= $best_game ? $best_game : 0; ?></strong>
                </div>
                <div class="chart-row">
                    <div class="chart-label">Tugas</div>
                    <div class="chart-track"><div class="chart-fill" style="width:<?= min(100, $total_tugas_kumpul * 20); ?>%;"></div></div>
                    <strong><?= $total_tugas_kumpul; ?></strong>
                </div>
            </div>
        </div>

        <?php if ($materi_lanjut): ?>
            <div class="card next-card" style="margin-bottom:18px;">
                <span class="badge" style="background:rgba(255,255,255,0.2); color:white;">Rekomendasi Belajar</span>
                <h2 style="margin:14px 0 8px;"><?= $materi_lanjut['judul_materi']; ?></h2>
                <p><?= $materi_lanjut['deskripsi']; ?></p>
                <br>
                <a href="detail_materi.php?id=<?= $materi_lanjut['id_materi']; ?>" class="btn btn-outline">Lanjutkan Materi</a>
            </div>
        <?php else: ?>
            <div class="card next-card" style="margin-bottom:18px;">
                <h2>Selamat! Semua materi sudah selesai.</h2>
                <p>Kamu bisa mengulang kuis atau memperbanyak latihan coding HTML.</p>
                <br>
                <a href="live_coding.php" class="btn btn-outline">Latihan Lagi</a>
            </div>
        <?php endif; ?>

        <section class="content-grid">
            <div class="card">
                <h3>Materi Web</h3>
                <p>Pilih materi dan mulai belajar secara bertahap.</p>
                <br>

                <div class="module-list">
                    <?php while ($row = mysqli_fetch_assoc($materi)): ?>
                        <div class="module-item">
                            <div>
                                <h4><?= $row['urutan']; ?>. <?= $row['judul_materi']; ?></h4>
                                <p><?= $row['deskripsi']; ?></p>
                            </div>
                            <a class="btn btn-primary" href="detail_materi.php?id=<?= $row['id_materi']; ?>">Belajar</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="card">
                <h3>Progress Belajar</h3>
                <p>Kamu sudah menyelesaikan <?= $persen; ?>% dari seluruh materi.</p>
                <div class="progress">
                    <div class="progress-bar" style="width:<?= $persen; ?>%;"></div>
                </div>
                <br>
                <a href="live_coding.php" class="btn btn-primary" style="width:100%;">Coba Live Coding Web</a>
                <br><br>
                <a href="game.php" class="btn btn-outline" style="width:100%;">Main Game Coding</a>

                <br><br>
                <h3>Latihan Terakhir</h3>
                <br>

                <?php if (mysqli_num_rows($latihan_terakhir) == 0): ?>
                    <div class="empty-state">Belum ada latihan tersimpan.</div>
                <?php endif; ?>

                <?php while($row = mysqli_fetch_assoc($latihan_terakhir)): ?>
                    <p>💻 <?= $row['judul_materi']; ?></p>
                    <small><?= $row['tanggal_simpan']; ?></small>
                    <br><br>
                <?php endwhile; ?>
            </div>
        </section>
    </main>
</div>
</body>
</html>
