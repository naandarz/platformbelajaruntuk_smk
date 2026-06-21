<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$keyword = mysqli_real_escape_string($koneksi, $_GET['keyword'] ?? '');
$id_materi = intval($_GET['id_materi'] ?? 0);

$where = "WHERE 1=1";

if ($keyword != "") {
    $where .= " AND (users.nama LIKE '%$keyword%' OR users.kelas LIKE '%$keyword%' OR latihan_kode.catatan LIKE '%$keyword%')";
}

if ($id_materi > 0) {
    $where .= " AND latihan_kode.id_materi=$id_materi";
}

$materi = mysqli_query($koneksi, "SELECT * FROM materi ORDER BY urutan ASC");

$latihan = mysqli_query($koneksi, "
    SELECT 
        latihan_kode.*, 
        users.nama, 
        users.kelas,
        materi.judul_materi
    FROM latihan_kode
    JOIN users ON latihan_kode.id_user = users.id_user
    JOIN materi ON latihan_kode.id_materi = materi.id_materi
    $where
    ORDER BY latihan_kode.tanggal_simpan DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan Coding Siswa</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Latihan Coding Siswa</h1>
                <p>Pantau hasil latihan HTML yang disimpan siswa melalui fitur Live Coding.</p>
            </div>
        </div>

        <div class="filter-card">
            <form method="GET" class="filter-form">
                <div class="form-group">
                    <label>Cari Siswa / Kelas / Catatan</label>
                    <input type="text" name="keyword" class="form-control" value="<?= htmlspecialchars($keyword); ?>" placeholder="Contoh: Siswa Demo">
                </div>

                <div class="form-group">
                    <label>Filter Materi</label>
                    <select name="id_materi" class="form-control">
                        <option value="0">Semua Materi</option>
                        <?php while($m = mysqli_fetch_assoc($materi)): ?>
                            <option value="<?= $m['id_materi']; ?>" <?= $id_materi == $m['id_materi'] ? 'selected' : ''; ?>>
                                <?= $m['judul_materi']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                <a href="latihan_siswa.php" class="btn btn-outline">Reset</a>
            </form>
        </div>

        <div class="module-list">
            <?php if (!$latihan || mysqli_num_rows($latihan) == 0): ?>
                <div class="empty-state">Belum ada latihan coding yang sesuai filter.</div>
            <?php endif; ?>

            <?php if ($latihan): ?>
                <?php while($row = mysqli_fetch_assoc($latihan)): ?>
                    <div class="card">
                        <div style="display:flex; justify-content:space-between; gap:14px; flex-wrap:wrap;">
                            <div>
                                <span class="badge badge-primary"><?= $row['judul_materi']; ?></span>
                                <h3 style="margin-top:12px;"><?= $row['nama']; ?></h3>
                                <p>Kelas: <?= $row['kelas'] ?: '-'; ?> | Disimpan: <?= $row['tanggal_simpan']; ?></p>
                            </div>
                            <a class="btn btn-outline btn-sm" href="detail_siswa.php?id=<?= $row['id_user']; ?>">Lihat Detail Siswa</a>
                        </div>

                        <br>
                        <div class="notice-box">
                            <strong>Catatan siswa:</strong><br>
                            <?= $row['catatan'] ?: 'Tidak ada catatan.'; ?>
                        </div>

                        <br>
                        <div class="code-preview-mini"><?= htmlspecialchars($row['kode_html']); ?></div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>
