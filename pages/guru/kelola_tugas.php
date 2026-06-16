<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$pesan = "";
$error = "";

function notifyRole($koneksi, $role, $judul, $pesan, $tipe='info', $link=null) {
    $users = mysqli_query($koneksi, "SELECT id_user FROM users WHERE role='$role'");
    while($u = mysqli_fetch_assoc($users)) {
        $id = intval($u['id_user']);
        $j = mysqli_real_escape_string($koneksi, $judul);
        $p = mysqli_real_escape_string($koneksi, $pesan);
        $t = mysqli_real_escape_string($koneksi, $tipe);
        $l = $link ? "'" . mysqli_real_escape_string($koneksi, $link) . "'" : "NULL";
        mysqli_query($koneksi, "INSERT INTO notifikasi (id_user, judul, pesan, tipe, link) VALUES ($id, '$j', '$p', '$t', $l)");
    }
}

function addNotif($koneksi, $id_user, $judul, $pesan, $tipe='info', $link=null) {
    $judul = mysqli_real_escape_string($koneksi, $judul);
    $pesan = mysqli_real_escape_string($koneksi, $pesan);
    $tipe = mysqli_real_escape_string($koneksi, $tipe);
    $linkSql = $link ? "'" . mysqli_real_escape_string($koneksi, $link) . "'" : "NULL";
    mysqli_query($koneksi, "INSERT INTO notifikasi (id_user, judul, pesan, tipe, link) VALUES ($id_user, '$judul', '$pesan', '$tipe', $linkSql)");
}

if (isset($_POST['tambah'])) {
    $id_materi = intval($_POST['id_materi'] ?? 0);
    $id_materi_sql = $id_materi > 0 ? $id_materi : "NULL";
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul_tugas']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $batas = mysqli_real_escape_string($koneksi, $_POST['batas_waktu']);
    $batas_sql = $batas ? "'$batas'" : "NULL";
    $tipe_tugas = mysqli_real_escape_string($koneksi, $_POST['tipe_tugas'] ?? 'individu');
    $id_kelompok = intval($_POST['id_kelompok'] ?? 0);
    $id_kelompok_sql = ($tipe_tugas == 'kelompok' && $id_kelompok > 0) ? $id_kelompok : "NULL";

    mysqli_query($koneksi, "INSERT INTO tugas (id_materi, judul_tugas, deskripsi, batas_waktu, tipe_tugas, id_kelompok)
    VALUES ($id_materi_sql, '$judul', '$deskripsi', $batas_sql, '$tipe_tugas', $id_kelompok_sql)");

    notifyRole($koneksi, 'siswa', 'Tugas Baru', "Guru menambahkan tugas baru: $judul.", 'success', 'tugas.php');

    header("Location: kelola_tugas.php?status=ditambah");
    exit;
}

if (isset($_POST['update_nilai'])) {
    $id_pengumpulan = intval($_POST['id_pengumpulan']);
    $nilai = intval($_POST['nilai']);
    $feedback = mysqli_real_escape_string($koneksi, $_POST['feedback']);

    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT pengumpulan_tugas.*, tugas.judul_tugas
        FROM pengumpulan_tugas
        JOIN tugas ON pengumpulan_tugas.id_tugas = tugas.id_tugas
        WHERE pengumpulan_tugas.id_pengumpulan=$id_pengumpulan
    "));

    mysqli_query($koneksi, "UPDATE pengumpulan_tugas SET nilai=$nilai, feedback='$feedback', tanggal_nilai=NOW() WHERE id_pengumpulan=$id_pengumpulan");

    if ($data) {
        addNotif($koneksi, intval($data['id_user']), 'Tugas Sudah Dinilai', 'Tugas "' . $data['judul_tugas'] . '" sudah diberi nilai oleh guru.', 'success', 'tugas.php');
    }

    header("Location: kelola_tugas.php?status=dinilai");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM tugas WHERE id_tugas=$id");
    header("Location: kelola_tugas.php?status=dihapus");
    exit;
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'ditambah') $pesan = "Tugas berhasil ditambahkan dan notifikasi dikirim ke siswa.";
    if ($_GET['status'] == 'dihapus') $pesan = "Tugas berhasil dihapus.";
    if ($_GET['status'] == 'dinilai') $pesan = "Nilai tugas berhasil disimpan dan siswa mendapat notifikasi.";
}

$filter_status = $_GET['status_nilai'] ?? 'semua';
$filter_materi = intval($_GET['id_materi'] ?? 0);
$filter_tugas = intval($_GET['id_tugas'] ?? 0);

$materi = mysqli_query($koneksi, "SELECT * FROM materi ORDER BY urutan ASC");
$kelompok_select_tugas = mysqli_query($koneksi, "SELECT * FROM kelompok ORDER BY nama_kelompok ASC");
$tugas_list = mysqli_query($koneksi, "
    SELECT tugas.*, materi.judul_materi,
        COUNT(pengumpulan_tugas.id_pengumpulan) AS total_pengumpulan,
        SUM(CASE WHEN pengumpulan_tugas.nilai IS NULL THEN 1 ELSE 0 END) AS belum_dinilai,
        SUM(CASE WHEN pengumpulan_tugas.nilai IS NOT NULL THEN 1 ELSE 0 END) AS sudah_dinilai
    FROM tugas
    LEFT JOIN materi ON tugas.id_materi = materi.id_materi
    LEFT JOIN pengumpulan_tugas ON tugas.id_tugas = pengumpulan_tugas.id_tugas
    GROUP BY tugas.id_tugas
    ORDER BY tugas.created_at DESC
");

$where = "WHERE 1=1";
if ($filter_status == 'belum') $where .= " AND pengumpulan_tugas.nilai IS NULL";
if ($filter_status == 'sudah') $where .= " AND pengumpulan_tugas.nilai IS NOT NULL";
if ($filter_tugas > 0) $where .= " AND tugas.id_tugas=$filter_tugas";
if ($filter_materi > 0) $where .= " AND tugas.id_materi=$filter_materi";

$grouped = mysqli_query($koneksi, "
    SELECT tugas.id_tugas, tugas.judul_tugas, tugas.batas_waktu, materi.judul_materi,
        COUNT(pengumpulan_tugas.id_pengumpulan) AS total_pengumpulan,
        SUM(CASE WHEN pengumpulan_tugas.nilai IS NULL THEN 1 ELSE 0 END) AS belum_dinilai,
        SUM(CASE WHEN pengumpulan_tugas.nilai IS NOT NULL THEN 1 ELSE 0 END) AS sudah_dinilai
    FROM tugas
    LEFT JOIN materi ON tugas.id_materi = materi.id_materi
    JOIN pengumpulan_tugas ON tugas.id_tugas = pengumpulan_tugas.id_tugas
    $where
    GROUP BY tugas.id_tugas
    ORDER BY tugas.created_at DESC
");

$total_belum = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengumpulan_tugas WHERE nilai IS NULL"))['total'];
$total_sudah = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengumpulan_tugas WHERE nilai IS NOT NULL"))['total'];
$total_tugas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tugas"))['total'];
$total_kumpul = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengumpulan_tugas"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Tugas</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Kelola Tugas</h1>
                <p>Pengumpulan tugas dikelompokkan berdasarkan tugas dan status penilaian agar guru tidak perlu scroll terlalu panjang.</p>
            </div>
            <div class="page-actions no-print">
                <button class="btn btn-primary" onclick="window.print()">Export PDF</button>
            </div>
        </div>

        <?php if ($pesan): ?><div class="alert alert-success"><?= $pesan; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error; ?></div><?php endif; ?>

        <section class="stats-grid">
            <div class="stat-card"><span>Total Tugas</span><h2><?= $total_tugas; ?></h2></div>
            <div class="stat-card"><span>Pengumpulan</span><h2><?= $total_kumpul; ?></h2></div>
            <div class="stat-card"><span>Belum Dinilai</span><h2><?= $total_belum; ?></h2></div>
            <div class="stat-card"><span>Sudah Dinilai</span><h2><?= $total_sudah; ?></h2></div>
        </section>

        <section class="content-grid">
            <div>
                <form method="POST" class="card no-print">
                    <span class="badge badge-primary">Buat Tugas</span>
                    <h3 style="margin-top:12px;">Tambah Tugas Baru</h3>
                    <br>

                    <div class="form-group">
                        <label>Materi Terkait</label>
                        <select name="id_materi" class="form-control">
                            <option value="">Umum / Tidak terkait materi</option>
                            <?php mysqli_data_seek($materi, 0); while($m = mysqli_fetch_assoc($materi)): ?>
                                <option value="<?= $m['id_materi']; ?>"><?= $m['urutan']; ?>. <?= $m['judul_materi']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-row-clean">
                        <div class="form-group">
                            <label>Tipe Tugas</label>
                            <select name="tipe_tugas" class="form-control" id="tipeTugasSelect" onchange="toggleKelompokTarget()">
                                <option value="individu">Individu</option>
                                <option value="kelompok">Kelompok</option>
                            </select>
                        </div>
                        <div class="form-group" style="grid-column:span 2;">
                            <label>Target Kelompok</label>
                            <select name="id_kelompok" class="form-control" id="targetKelompokSelect">
                                <option value="0">Untuk semua / Tidak khusus kelompok</option>
                                <?php while($kg = mysqli_fetch_assoc($kelompok_select_tugas)): ?>
                                    <option value="<?= $kg['id_kelompok']; ?>"><?= htmlspecialchars($kg['nama_kelompok']); ?></option>
                                <?php endwhile; ?>
                            </select>
                            <small class="form-help">Diisi jika tipe tugas adalah tugas kelompok.</small>
                        </div>
                    </div>

                    <div class="form-group"><label>Judul Tugas</label><input type="text" name="judul_tugas" class="form-control" required></div>
                    <div class="form-group"><label>Deskripsi Tugas</label><textarea name="deskripsi" class="form-control" rows="5" required></textarea></div>
                    <div class="form-group"><label>Batas Waktu</label><input type="datetime-local" name="batas_waktu" class="form-control"></div>

                    <button type="submit" name="tambah" class="btn btn-primary">Simpan Tugas</button>
                </form>

                <div class="card" style="margin-top:18px;">
                    <h3>Daftar Tugas</h3>
                    <p>Ringkasan setiap tugas dan jumlah pengumpulan.</p>
                    <br>

                    <?php if (!$tugas_list || mysqli_num_rows($tugas_list) == 0): ?>
                        <div class="empty-state">Belum ada tugas.</div>
                    <?php endif; ?>

                    <?php if ($tugas_list): ?>
                        <?php while($row = mysqli_fetch_assoc($tugas_list)): ?>
                            <div class="task-card">
                                <span class="badge badge-primary"><?= $row['judul_materi'] ?: 'Umum'; ?></span>
                                <span class="task-type-pill <?= ($row['tipe_tugas'] ?? 'individu') == 'kelompok' ? 'group' : ''; ?>">
                                    <?= ($row['tipe_tugas'] ?? 'individu') == 'kelompok' ? '👥 Kelompok' : '👤 Individu'; ?>
                                </span>
                                <h3 style="margin-top:12px;"><?= htmlspecialchars($row['judul_tugas']); ?></h3>
                                <div class="task-status">
                                    <span class="badge badge-neutral">Terkumpul: <?= intval($row['total_pengumpulan']); ?></span>
                                    <span class="badge badge-warning">Belum: <?= intval($row['belum_dinilai']); ?></span>
                                    <span class="badge badge-success">Sudah: <?= intval($row['sudah_dinilai']); ?></span>
                                </div>
                                <div class="action-row no-print">
                                    <a href="kelola_tugas.php?id_tugas=<?= $row['id_tugas']; ?>&status_nilai=semua" class="btn btn-outline btn-sm">Lihat Detail</a>
                                    <a href="?hapus=<?= $row['id_tugas']; ?>" onclick="return confirm('Hapus tugas ini?')" class="btn btn-danger btn-sm">Hapus</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <div class="filter-card no-print">
                    <h3>Filter Pengumpulan</h3>
                    <br>
                    <div class="status-tabs">
                        <a class="status-tab <?= $filter_status == 'belum' ? 'active' : ''; ?>" href="kelola_tugas.php?status_nilai=belum">Belum Dinilai</a>
                        <a class="status-tab <?= $filter_status == 'sudah' ? 'active' : ''; ?>" href="kelola_tugas.php?status_nilai=sudah">Sudah Dinilai</a>
                        <a class="status-tab <?= $filter_status == 'semua' ? 'active' : ''; ?>" href="kelola_tugas.php?status_nilai=semua">Semua</a>
                    </div>

                    <form method="GET" class="filter-form">
                        <input type="hidden" name="status_nilai" value="<?= htmlspecialchars($filter_status); ?>">

                        <div class="form-group">
                            <label>Materi</label>
                            <select name="id_materi" class="form-control">
                                <option value="0">Semua Materi</option>
                                <?php mysqli_data_seek($materi, 0); while($m = mysqli_fetch_assoc($materi)): ?>
                                    <option value="<?= $m['id_materi']; ?>" <?= $filter_materi == $m['id_materi'] ? 'selected' : ''; ?>>
                                        <?= $m['judul_materi']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Terapkan</button>
                        <a href="kelola_tugas.php" class="btn btn-outline">Reset</a>
                    </form>
                </div>

                <div class="card">
                    <h3>Monitoring Pengumpulan</h3>
                    <p>Buka panel tugas untuk melihat pengumpulan siswa, memberi nilai, dan menulis feedback.</p>
                    <br>

                    <?php if (!$grouped || mysqli_num_rows($grouped) == 0): ?>
                        <div class="empty-state">Belum ada pengumpulan sesuai filter.</div>
                    <?php endif; ?>

                    <?php if ($grouped): ?>
                        <?php while($g = mysqli_fetch_assoc($grouped)): ?>
                            <details class="task-monitor-card" <?= $filter_tugas == $g['id_tugas'] ? 'open' : ''; ?>>
                                <summary class="task-monitor-summary">
                                    <div>
                                        <?= htmlspecialchars($g['judul_tugas']); ?><br>
                                        <small><?= htmlspecialchars($g['judul_materi'] ?: 'Umum'); ?></small>
                                    </div>
                                    <div class="task-status">
                                        <span class="badge badge-neutral">Total <?= $g['total_pengumpulan']; ?></span>
                                        <span class="badge badge-warning">Belum <?= intval($g['belum_dinilai']); ?></span>
                                        <span class="badge badge-success">Sudah <?= intval($g['sudah_dinilai']); ?></span>
                                    </div>
                                </summary>

                                <div class="task-monitor-body">
                                    <?php
                                    $id_tugas_group = intval($g['id_tugas']);
                                    $where_detail = "WHERE pengumpulan_tugas.id_tugas=$id_tugas_group";
                                    if ($filter_status == 'belum') $where_detail .= " AND pengumpulan_tugas.nilai IS NULL";
                                    if ($filter_status == 'sudah') $where_detail .= " AND pengumpulan_tugas.nilai IS NOT NULL";

                                    $detail = mysqli_query($koneksi, "
                                        SELECT pengumpulan_tugas.*, users.nama, users.kelas
                                        FROM pengumpulan_tugas
                                        JOIN users ON pengumpulan_tugas.id_user = users.id_user
                                        $where_detail
                                        ORDER BY pengumpulan_tugas.tanggal_upload DESC
                                    ");
                                    ?>

                                    <?php while($row = mysqli_fetch_assoc($detail)): ?>
                                        <div class="task-card">
                                            <span class="badge <?= $row['nilai'] === null ? 'badge-warning' : 'badge-success'; ?>">
                                                <?= $row['nilai'] === null ? 'Belum Dinilai' : 'Sudah Dinilai'; ?>
                                            </span>
                                            <h3 style="margin-top:12px;"><?= htmlspecialchars($row['nama']); ?></h3>
                                            <p>Kelas: <?= htmlspecialchars($row['kelas'] ?: '-'); ?></p>
                                            <p>Catatan: <?= nl2br(htmlspecialchars($row['catatan'] ?: '-')); ?></p>

                                            <?php if ($row['file_tugas']): ?>
                                                <a class="file-pill" href="../../<?= htmlspecialchars($row['file_tugas']); ?>" target="_blank">📎 Lihat File Tugas</a>
                                            <?php endif; ?>

                                            <form method="POST" style="margin-top:14px;" class="no-print">
                                                <input type="hidden" name="id_pengumpulan" value="<?= $row['id_pengumpulan']; ?>">
                                                <div class="compact-grid">
                                                    <div class="form-group">
                                                        <label>Nilai</label>
                                                        <input type="number" name="nilai" class="form-control" min="0" max="100" value="<?= htmlspecialchars($row['nilai'] ?? ''); ?>" required>
                                                    </div>
                                                    <div class="form-group" style="grid-column: span 2;">
                                                        <label>Feedback</label>
                                                        <textarea name="feedback" class="form-control" rows="3"><?= htmlspecialchars($row['feedback'] ?? ''); ?></textarea>
                                                    </div>
                                                </div>
                                                <button type="submit" name="update_nilai" class="btn btn-primary">Simpan Nilai</button>
                                            </form>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </details>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
