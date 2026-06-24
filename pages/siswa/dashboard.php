<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
include "../../includes/tahap22_bootstrap.php";
hanya_role('siswa');

$id_user = $_SESSION['user']['id_user'];
$nama_user = $_SESSION['user']['nama'] ?? 'Siswa';
$kelas_user = $_SESSION['user']['kelas'] ?? 'PRO MEMBER';

$total_materi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM materi"))['total'];
$total_selesai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM progres_belajar WHERE id_user=$id_user AND status='selesai'"))['total'];
$rata_nilai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT AVG(skor) AS rata FROM nilai WHERE id_user=$id_user"))['rata'];
$best_game = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT MAX(skor) AS skor FROM game_scores WHERE id_user=$id_user"))['skor'];
$total_tugas_kumpul = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengumpulan_tugas WHERE id_user=$id_user"))['total'];
$persen = $total_materi > 0 ? round(($total_selesai / $total_materi) * 100) : 0;

$today = date('Y-m-d');
$kelas_sql = mysqli_real_escape_string($koneksi, $kelas_user);
$absen = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM absensi WHERE id_user=$id_user AND tanggal='$today'"));

$materi_lanjut = mysqli_query($koneksi, "
    SELECT materi.*
    FROM materi
    LEFT JOIN progres_belajar 
        ON materi.id_materi = progres_belajar.id_materi 
        AND progres_belajar.id_user = $id_user 
        AND progres_belajar.status='selesai'
    WHERE progres_belajar.id_progres IS NULL
    ORDER BY materi.urutan ASC
    LIMIT 2
");

$latihan_terakhir = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT latihan_kode.*, materi.judul_materi
    FROM latihan_kode
    JOIN materi ON latihan_kode.id_materi = materi.id_materi
    WHERE latihan_kode.id_user=$id_user
    ORDER BY latihan_kode.tanggal_simpan DESC
    LIMIT 1
"));

$jadwal = mysqli_query($koneksi, "
    SELECT jadwal_kelas.*, materi.judul_materi
    FROM jadwal_kelas
    LEFT JOIN materi ON jadwal_kelas.id_materi = materi.id_materi
    WHERE jadwal_kelas.tanggal >= CURDATE()
      AND (jadwal_kelas.kelas IS NULL OR jadwal_kelas.kelas='' OR jadwal_kelas.kelas='$kelas_sql')
    ORDER BY jadwal_kelas.tanggal ASC, jadwal_kelas.jam_mulai ASC
    LIMIT 3
");

$kelompok = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT kelompok.* 
    FROM kelompok_anggota 
    JOIN kelompok ON kelompok_anggota.id_kelompok=kelompok.id_kelompok 
    WHERE kelompok_anggota.id_user=$id_user
    ORDER BY kelompok.created_at DESC
    LIMIT 1
"));
$anggota_count = 0;
if ($kelompok) {
    $kid = intval($kelompok['id_kelompok']);
    $anggota_count = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kelompok_anggota WHERE id_kelompok=$kid"))['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Siswa | SmartLearn</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <header class="syntactic-topbar">
            <div class="syntactic-title">
                <h1>Dashboard Siswa</h1>
                <p>Pantau progres belajar, jadwal, absensi, dan aktivitas pembelajaran.</p>
            </div>
            <div class="syntactic-search">
                <span class="material-symbols-outlined">search</span>
                <input type="text" placeholder="Cari materi..." onfocus="window.location='materi.php'">
            </div>
            <div class="syntactic-bell"><span class="material-symbols-outlined">notifications</span></div>
            <div class="syntactic-user">
                <div>
                    <strong><?= htmlspecialchars($nama_user); ?></strong>
                    <span><?= htmlspecialchars($kelas_user ?: 'PRO MEMBER'); ?></span>
                </div>
                <div class="syntactic-avatar"><?= strtoupper(substr($nama_user,0,1)); ?></div>
            </div>
        </header>

        <section class="syntax-stat-grid five">
            <div class="syntax-stat-card">
                <div class="syntax-stat-icon"><span class="material-symbols-outlined">library_books</span></div>
                <span class="label">Total Materi</span>
                <h2><?= $total_materi; ?></h2>
                <span class="syntax-trend neutral">SEMUA</span>
            </div>
            <div class="syntax-stat-card">
                <div class="syntax-stat-icon cyan"><span class="material-symbols-outlined">check_circle</span></div>
                <span class="label">Materi Selesai</span>
                <h2><?= $total_selesai; ?></h2>
                <span class="syntax-trend"><?= $persen; ?>%</span>
            </div>
            <div class="syntax-stat-card">
                <div class="syntax-stat-icon purple"><span class="material-symbols-outlined">star</span></div>
                <span class="label">Rata-rata Nilai</span>
                <h2><?= $rata_nilai ? round($rata_nilai,1) : 0; ?></h2>
                <span class="syntax-trend neutral"><?= ($rata_nilai ?: 0) >= 75 ? 'A-' : 'TRY'; ?></span>
            </div>
            <div class="syntax-stat-card">
                <div class="syntax-stat-icon"><span class="material-symbols-outlined">stadia_controller</span></div>
                <span class="label">Skor Game Terbaik</span>
                <h2><?= number_format($best_game ?: 0); ?></h2>
                <span class="syntax-trend neutral">Top</span>
            </div>
            <div class="syntax-stat-card">
                <div class="syntax-stat-icon red"><span class="material-symbols-outlined">assignment_turned_in</span></div>
                <span class="label">Tugas Terkumpul</span>
                <h2><?= $total_tugas_kumpul; ?></h2>
                <span class="syntax-trend danger">DONE</span>
            </div>
        </section>

        <section class="syntax-grid-main">
            <div class="syntax-side-stack">
                <div class="syntax-card">
                    <div class="syntax-card-header">
                        <h3>Lanjut Belajar</h3>
                        <a class="syntax-link" href="materi.php">Lihat Semua Materi →</a>
                    </div>

                    <div class="syntax-course-grid">
                        <?php if (!$materi_lanjut || mysqli_num_rows($materi_lanjut) == 0): ?>
                            <div class="empty-state">Semua materi sudah selesai. Mantap!</div>
                        <?php endif; ?>

                        <?php if ($materi_lanjut): ?>
                            <?php while($m = mysqli_fetch_assoc($materi_lanjut)): ?>
                                <?php $progressDummy = max(12, min(90, intval(($m['urutan'] * 17) % 95))); ?>
                                <div class="syntax-course">
                                    <span class="syntax-tag <?= $m['urutan'] % 2 == 0 ? 'cyan' : ''; ?>">
                                        <?= strpos(strtolower($m['judul_materi']), 'css') !== false ? 'CSS' : (strpos(strtolower($m['judul_materi']), 'java') !== false ? 'JS' : 'HTML'); ?>
                                    </span>
                                    <h4><?= htmlspecialchars($m['judul_materi']); ?></h4>
                                    <p><?= htmlspecialchars(substr(strip_tags($m['isi_materi'] ?? 'Pelajari konsep penting dan praktikkan langsung di SmartLearn.'),0,95)); ?>...</p>
                                    <div class="syntax-progress-row">
                                        <span>Progress: <?= $progressDummy; ?>%</span>
                                        <span><?= $m['urutan']; ?> modul</span>
                                    </div>
                                    <div class="syntax-progress"><span style="width:<?= $progressDummy; ?>%;"></span></div>
                                    <a class="syntax-button full" href="detail_materi.php?id=<?= $m['id_materi']; ?>">Lanjut Belajar</a>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="syntax-card">
                    <div class="syntax-card-header">
                        <h3><span class="material-symbols-outlined" style="vertical-align:middle;color:var(--syntax-primary);">terminal</span> Latihan Terakhir</h3>
                        <span class="small-muted">Terakhir diubah: <?= $latihan_terakhir ? $latihan_terakhir['tanggal_simpan'] : '-'; ?></span>
                    </div>
                    <pre class="syntax-code-panel"><code><span class="pink">async function</span> <span class="cyan">fetchUserData</span>(id) {
  <span class="pink">try</span> {
    const response = <span class="pink">await</span> fetch(<span class="yellow">`/api/users/${id}`</span>);
    const data = <span class="pink">await</span> response.json();
    console.<span class="green">log</span>(<span class="yellow">"User Loaded:"</span>, data);
    return data;
  } <span class="pink">catch</span> (err) {
    console.<span class="blue">error</span>(<span class="yellow">"Oops, fetch failed!"</span>, err);
  }
}</code></pre>
                </div>
            </div>

            <aside class="syntax-side-stack">
                <div class="syntax-card">
                    <div class="syntax-card-header">
                        <h3>Absensi Hari Ini</h3>
                    </div>
                    <div class="syntax-absence-box">
                        <span class="material-symbols-outlined">fingerprint</span>
                        <p>Status: <strong style="color:<?= $absen ? '#0b7a3a' : '#d71920'; ?>"><?= $absen ? 'Sudah Hadir' : 'Belum Hadir'; ?></strong></p>
                        <a class="syntax-button secondary full" href="absensi.php">
                            <span class="material-symbols-outlined" style="font-size:20px;">how_to_reg</span>
                            <?= $absen ? 'Lihat Absensi' : 'Absen Sekarang'; ?>
                        </a>
                        <small>Tersedia hingga pukul 09:00 WIB</small>
                    </div>
                </div>

                <div class="syntax-card">
                    <div class="syntax-card-header">
                        <h3>Jadwal Terdekat</h3>
                        <a class="syntax-link" href="jadwal.php">Lihat Semua</a>
                    </div>

                    <?php if (!$jadwal || mysqli_num_rows($jadwal) == 0): ?>
                        <div class="empty-state">Belum ada jadwal.</div>
                    <?php endif; ?>

                    <?php if ($jadwal): ?>
                        <?php while($j = mysqli_fetch_assoc($jadwal)): ?>
                            <div class="syntax-schedule-item">
                                <div class="syntax-datebox">
                                    <?= strtoupper(date('M', strtotime($j['tanggal']))); ?>
                                    <strong><?= date('d', strtotime($j['tanggal'])); ?></strong>
                                </div>
                                <div>
                                    <h4><?= htmlspecialchars(substr($j['judul'],0,22)); ?>...</h4>
                                    <p>⏱ <?= substr($j['jam_mulai'],0,5); ?> - <?= substr($j['jam_selesai'],0,5); ?></p>
                                </div>
                                <span class="material-symbols-outlined" style="color:var(--syntax-primary);">videocam</span>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>

                <div class="syntax-group-card">
                    <h3>Kelompok Saya</h3>
                    <p>"<?= htmlspecialchars($kelompok['nama_kelompok'] ?? 'Belum Masuk Kelompok'); ?>" - <?= $anggota_count; ?> Anggota Aktif</p>
                    <div class="syntax-avatar-row">
                        <div class="syntax-avatar-mini">A</div>
                        <div class="syntax-avatar-mini">B</div>
                        <div class="syntax-avatar-mini">C</div>
                        <div class="syntax-avatar-mini">+<?= max(0, $anggota_count-3); ?></div>
                    </div>
                    <a class="syntax-button full" style="background:#fff!important;color:var(--syntax-primary)!important;" href="kelompok.php">Buka Chat Kelompok</a>
                </div>
            </aside>
        </section>

        <footer style="border-top:1px solid #d3d7e8;margin-top:70px;padding:24px 0;color:#565b6d;display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap;">
            <span>© 2026 SmartLearn Educational Systems. Versi Dashboard 2.4.0</span>
            <span>Kebijakan Privasi &nbsp; Ketentuan Layanan &nbsp; Pusat Bantuan</span>
        </footer>

        <a href="live_coding.php" class="fab-add" title="Mulai latihan"><span class="material-symbols-outlined">add</span></a>
    </main>
</div>
</body>
</html>
