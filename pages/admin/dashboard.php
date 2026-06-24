<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
include "../../includes/tahap22_bootstrap.php";
hanya_role('admin');

$nama_user = $_SESSION['user']['nama'] ?? 'Admin';
$total_user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users"))['total'];
$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='siswa'"))['total'];
$total_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='guru'"))['total'];
$total_materi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM materi"))['total'];
$total_absensi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM absensi"))['total'];
$total_jadwal = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM jadwal_kelas"))['total'];
$total_helpdesk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM admin_helpdesk_logs"))['total'];

$logs = mysqli_query($koneksi, "
    SELECT admin_helpdesk_logs.*, target.nama AS nama_target
    FROM admin_helpdesk_logs
    LEFT JOIN users target ON admin_helpdesk_logs.id_target_user=target.id_user
    ORDER BY admin_helpdesk_logs.created_at DESC
    LIMIT 3
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin | SmartLearn</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <header class="syntactic-topbar">
            <div class="syntactic-title">
                <h1>Dashboard Admin</h1>
                <p>Gambaran umum kondisi sistem SmartLearn.</p>
            </div>
            <div class="syntactic-search">
                <span class="material-symbols-outlined">search</span>
                <input type="text" placeholder="Search parameters..." onfocus="window.location='kelola_pengguna.php'">
            </div>
            <div class="syntactic-bell"><span class="material-symbols-outlined">notifications</span></div>
            <div class="syntactic-user">
                <div>
                    <strong><?= htmlspecialchars($nama_user); ?></strong>
                    <span>ADMIN SYSTEM</span>
                </div>
                <div class="syntactic-avatar"><?= strtoupper(substr($nama_user,0,1)); ?></div>
            </div>
        </header>

        <div class="syntax-card-header">
            <h3 style="font-size:26px;"><span class="material-symbols-outlined" style="vertical-align:middle;color:var(--syntax-secondary);">insert_chart</span> Statistik Sistem</h3>
        </div>

        <section class="syntax-stat-grid">
            <div class="syntax-stat-card">
                <div class="syntax-stat-icon"><span class="material-symbols-outlined">groups</span></div>
                <span class="label">Total User</span>
                <h2><?= number_format($total_user); ?></h2>
                <span class="syntax-trend">+12%</span>
            </div>
            <div class="syntax-stat-card">
                <div class="syntax-stat-icon cyan"><span class="material-symbols-outlined">school</span></div>
                <span class="label">Total Siswa</span>
                <h2><?= number_format($total_siswa); ?></h2>
                <span class="syntax-trend">+8%</span>
            </div>
            <div class="syntax-stat-card">
                <div class="syntax-stat-icon purple"><span class="material-symbols-outlined">workspace_premium</span></div>
                <span class="label">Total Guru</span>
                <h2><?= number_format($total_guru); ?></h2>
                <span class="syntax-trend neutral">Stable</span>
            </div>
            <div class="syntax-stat-card">
                <div class="syntax-stat-icon cyan"><span class="material-symbols-outlined">archive</span></div>
                <span class="label">Total Materi</span>
                <h2><?= number_format($total_materi); ?></h2>
                <span class="syntax-trend danger">-2%</span>
            </div>
        </section>

        <section class="syntax-grid-main">
            <div>
                <div class="syntax-card-header">
                    <h3 style="font-size:26px;"><span class="material-symbols-outlined" style="vertical-align:middle;color:var(--syntax-secondary);">bolt</span> Shortcut Sistem</h3>
                </div>

                <div class="syntax-course-grid" style="margin-bottom:18px;">
                    <a class="syntax-action-item" href="absensi.php" style="min-height:100px;">
                        <span class="icon"><span class="material-symbols-outlined">check_circle</span></span>
                        <span><h4>Total Absensi</h4><p>Update <?= number_format($total_absensi); ?> records today</p></span>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                    <a class="syntax-action-item" href="jadwal.php" style="min-height:100px;">
                        <span class="icon" style="background:#e2dfff;color:var(--syntax-primary);"><span class="material-symbols-outlined">event_available</span></span>
                        <span><h4>Total Jadwal</h4><p><?= number_format($total_jadwal); ?> Active sessions now</p></span>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                    <a class="syntax-action-item" href="rekap_aktivitas.php" style="min-height:100px;">
                        <span class="icon" style="background:#eee4ff;color:var(--syntax-purple);"><span class="material-symbols-outlined">analytics</span></span>
                        <span><h4>Rekap Aktivitas</h4><p>Analyze user engagement</p></span>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                    <a class="syntax-action-item" href="ui_settings.php" style="min-height:100px;">
                        <span class="icon" style="background:#eef0f5;color:#2b3344;"><span class="material-symbols-outlined">palette</span></span>
                        <span><h4>Pengaturan UI</h4><p>Customize brand identity</p></span>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                </div>

                <div class="syntax-card" style="background:#1f2b3d!important;color:white!important;border-color:#162235!important;">
                    <h3 style="font-size:18px;color:white!important;margin-bottom:26px;">Traffic Monitor (Last 24h)</h3>
                    <div style="height:150px;display:flex;align-items:end;gap:18px;">
                        <?php foreach([45,62,52,78,68,59,49,72,84,56,38,62] as $h): ?>
                            <div style="height:<?= $h; ?>%;flex:1;background:#57dffe;border:1px solid #0b1c30;"></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <aside class="syntax-info-panel">
                <div class="syntax-card-header">
                    <h3 style="font-size:26px;"><span class="material-symbols-outlined" style="vertical-align:middle;color:var(--syntax-secondary);">info</span> Ringkasan Sistem</h3>
                </div>

                <div class="syntax-info-box">
                    <h4>ARSITEKTUR INTI</h4>
                    <p>SmartLearn beroperasi pada arsitektur PHP Native dan MySQL untuk mendukung pembelajaran interaktif, evaluasi, absensi, dan rekap aktivitas.</p>
                </div>

                <div class="syntax-info-box">
                    <h4>INTEGRITAS DATA</h4>
                    <p>Setiap interaksi pengguna, mulai dari materi, tugas, absensi, jadwal, hingga pesan bantuan tercatat sebagai bagian dari aktivitas belajar.</p>
                </div>

                <div class="syntax-info-box">
                    <h4>SKALABILITAS</h4>
                    <p>Dashboard admin memberikan kontrol penuh atas pengguna, tampilan, jadwal, dan rekap sistem secara menyeluruh.</p>
                </div>

                <a href="rekap_aktivitas.php" class="syntax-button full">Generate Full Audit Report</a>
            </aside>
        </section>

        <section class="syntax-activity-log">
            <div class="syntax-log-head">
                <strong>▣ Recent Activity Log</strong>
                <span>Uptime: 99.98%</span>
            </div>
            <?php if (!$logs || mysqli_num_rows($logs) == 0): ?>
                <div class="syntax-log-row"><span class="syntax-dot"></span><span>SYS_READY</span><span>SmartLearn dashboard siap digunakan</span><span><?= date('H:i:s'); ?></span></div>
            <?php endif; ?>
            <?php if ($logs): ?>
                <?php while($log = mysqli_fetch_assoc($logs)): ?>
                    <div class="syntax-log-row">
                        <span class="syntax-dot <?= $log['aksi'] == 'Reset Password' ? 'blue' : ''; ?>"></span>
                        <span><?= strtoupper(str_replace(' ', '_', $log['aksi'])); ?></span>
                        <span><?= htmlspecialchars($log['nama_target'] ?: 'System'); ?> - <?= htmlspecialchars($log['keterangan'] ?: 'Aktivitas admin'); ?></span>
                        <span><?= date('H:i:s', strtotime($log['created_at'])); ?></span>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </section>

        <a href="kelola_pengguna.php" class="fab-add" title="Tambah akun"><span class="material-symbols-outlined">add</span></a>
    </main>
</div>
</body>
</html>
