<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('admin');

$keyword = mysqli_real_escape_string($koneksi, $_GET['keyword'] ?? '');
$aksi = mysqli_real_escape_string($koneksi, $_GET['aksi'] ?? '');

$where = "WHERE 1=1";
if ($keyword != "") {
    $where .= " AND (target_user.nama LIKE '%$keyword%' OR target_user.email LIKE '%$keyword%' OR admin_helpdesk_logs.keterangan LIKE '%$keyword%')";
}
if ($aksi != "") {
    $where .= " AND admin_helpdesk_logs.aksi='$aksi'";
}

$logs = mysqli_query($koneksi, "
    SELECT 
        admin_helpdesk_logs.*,
        admin_user.nama AS nama_admin,
        target_user.nama AS nama_target,
        target_user.email AS email_target,
        target_user.role AS role_target
    FROM admin_helpdesk_logs
    JOIN users admin_user ON admin_helpdesk_logs.id_admin = admin_user.id_user
    JOIN users target_user ON admin_helpdesk_logs.id_target_user = target_user.id_user
    $where
    ORDER BY admin_helpdesk_logs.created_at DESC
");

$aksi_list = mysqli_query($koneksi, "SELECT DISTINCT aksi FROM admin_helpdesk_logs ORDER BY aksi ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Semua Riwayat Help Desk</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Semua Riwayat Help Desk</h1>
                <p>Riwayat lengkap aktivitas admin saat membantu akun pengguna.</p>
            </div>
            <div class="page-actions">
                <a href="kelola_pengguna.php" class="btn btn-outline">Kembali</a>
                <button onclick="window.print()" class="btn btn-primary">Export PDF</button>
            </div>
        </div>

        <div class="filter-card no-print">
            <form method="GET" class="filter-form">
                <div class="form-group">
                    <label>Cari Riwayat</label>
                    <input type="text" name="keyword" class="form-control" value="<?= htmlspecialchars($keyword); ?>" placeholder="Nama, email, atau keterangan...">
                </div>

                <div class="form-group">
                    <label>Aksi</label>
                    <select name="aksi" class="form-control">
                        <option value="">Semua Aksi</option>
                        <?php while($a = mysqli_fetch_assoc($aksi_list)): ?>
                            <option value="<?= htmlspecialchars($a['aksi']); ?>" <?= $aksi == $a['aksi'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($a['aksi']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button class="btn btn-primary" type="submit">Filter</button>
                <a href="helpdesk_logs.php" class="btn btn-outline">Reset</a>
            </form>
        </div>

        <div class="card">
            <?php if (!$logs || mysqli_num_rows($logs) == 0): ?>
                <div class="empty-state">Belum ada riwayat sesuai filter.</div>
            <?php endif; ?>

            <?php if ($logs): ?>
                <div class="compact-history" style="max-height:none;">
                    <?php while($log = mysqli_fetch_assoc($logs)): ?>
                        <div class="history-mini-item">
                            <span class="badge badge-primary"><?= htmlspecialchars($log['aksi']); ?></span>
                            <h4 style="margin-top:10px;">Target: <?= htmlspecialchars($log['nama_target']); ?></h4>
                            <p>
                                Email: <?= htmlspecialchars($log['email_target']); ?><br>
                                Role: <?= htmlspecialchars($log['role_target']); ?><br>
                                Admin: <?= htmlspecialchars($log['nama_admin']); ?><br>
                                Waktu: <?= $log['created_at']; ?>
                            </p>
                            <?php if ($log['keterangan']): ?>
                                <small><?= htmlspecialchars($log['keterangan']); ?></small>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>
