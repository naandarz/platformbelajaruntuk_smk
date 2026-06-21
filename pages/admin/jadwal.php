<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
include "../../includes/tahap22_bootstrap.php";
hanya_role('admin');

$id_user = $_SESSION['user']['id_user'];
$pesan = "";
$error = "";

function notifyStudentsForSchedule($koneksi, $kelas, $judul) {
    $kelas = mysqli_real_escape_string($koneksi, $kelas);
    $judulClean = mysqli_real_escape_string($koneksi, $judul);
    $where = "role='siswa'";
    if ($kelas != "") {
        $where .= " AND kelas='$kelas'";
    }

    $users = mysqli_query($koneksi, "SELECT id_user FROM users WHERE $where");
    while($u = mysqli_fetch_assoc($users)) {
        $id = intval($u['id_user']);
        mysqli_query($koneksi, "INSERT INTO notifikasi (id_user, judul, pesan, tipe, link)
        VALUES ($id, 'Jadwal Baru', 'Jadwal baru ditambahkan: $judulClean', 'info', 'jadwal.php')");
    }
}

if (isset($_POST['simpan_jadwal'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $id_materi = intval($_POST['id_materi'] ?? 0);
    $id_materi_sql = $id_materi > 0 ? $id_materi : "NULL";
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $jam_mulai = mysqli_real_escape_string($koneksi, $_POST['jam_mulai']);
    $jam_selesai = mysqli_real_escape_string($koneksi, $_POST['jam_selesai']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    mysqli_query($koneksi, "
        INSERT INTO jadwal_kelas (judul, kategori, kelas, id_materi, tanggal, jam_mulai, jam_selesai, keterangan, dibuat_oleh)
        VALUES ('$judul', '$kategori', '$kelas', $id_materi_sql, '$tanggal', '$jam_mulai', '$jam_selesai', '$keterangan', $id_user)
    ");

    notifyStudentsForSchedule($koneksi, $kelas, $judul);

    header("Location: jadwal.php?status=saved");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM jadwal_kelas WHERE id_jadwal=$id");
    header("Location: jadwal.php?status=deleted");
    exit;
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'saved') $pesan = "Jadwal berhasil disimpan dan notifikasi dikirim ke siswa.";
    if ($_GET['status'] == 'deleted') $pesan = "Jadwal berhasil dihapus.";
}

$keyword = mysqli_real_escape_string($koneksi, $_GET['keyword'] ?? '');
$filter_kelas = mysqli_real_escape_string($koneksi, $_GET['kelas'] ?? '');
$filter_tanggal = mysqli_real_escape_string($koneksi, $_GET['tanggal'] ?? '');

$where = "WHERE 1=1";
if ($keyword != "") $where .= " AND (jadwal_kelas.judul LIKE '%$keyword%' OR jadwal_kelas.keterangan LIKE '%$keyword%')";
if ($filter_kelas != "") $where .= " AND jadwal_kelas.kelas='$filter_kelas'";
if ($filter_tanggal != "") $where .= " AND jadwal_kelas.tanggal='$filter_tanggal'";

$jadwal = mysqli_query($koneksi, "
    SELECT jadwal_kelas.*, materi.judul_materi, users.nama AS pembuat
    FROM jadwal_kelas
    LEFT JOIN materi ON jadwal_kelas.id_materi = materi.id_materi
    LEFT JOIN users ON jadwal_kelas.dibuat_oleh = users.id_user
    $where
    ORDER BY jadwal_kelas.tanggal DESC, jadwal_kelas.jam_mulai DESC
");

$materi = mysqli_query($koneksi, "SELECT * FROM materi ORDER BY urutan ASC");
$kelas_list = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM users WHERE role='siswa' AND kelas IS NOT NULL AND kelas != '' ORDER BY kelas ASC");

$total_jadwal = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM jadwal_kelas"))['total'];
$jadwal_mendatang = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM jadwal_kelas WHERE tanggal >= CURDATE()"))['total'];
$jadwal_hari_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM jadwal_kelas WHERE tanggal = CURDATE()"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Jadwal Kelas</title>
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
                <p>Atur jadwal pembelajaran, praktik, kuis, tugas, atau kegiatan kelas.</p>
            </div>
            <button class="btn btn-primary" onclick="window.print()">Export PDF</button>
        </div>

        <?php if ($pesan): ?><div class="alert alert-success"><?= $pesan; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error; ?></div><?php endif; ?>

        <section class="stats-grid">
            <div class="stat-card"><span>Total Jadwal</span><h2><?= $total_jadwal; ?></h2></div>
            <div class="stat-card"><span>Mendatang</span><h2><?= $jadwal_mendatang; ?></h2></div>
            <div class="stat-card"><span>Hari Ini</span><h2><?= $jadwal_hari_ini; ?></h2></div>
            <div class="stat-card"><span>Mode</span><h2>Aktif</h2></div>
        </section>

        <section class="content-grid">
            <div>
                <form method="POST" class="student-clean-card no-print">
                    <span class="badge badge-primary">Jadwal Baru</span>
                    <h3 style="margin-top:12px;">Tambah Jadwal</h3>
                    <br>

                    <div class="form-group">
                        <label>Judul Jadwal</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Praktik HTML Dasar" required>
                    </div>

                    <div class="form-row-clean">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" class="form-control">
                                <option value="Pembelajaran">Pembelajaran</option>
                                <option value="Praktik">Praktik</option>
                                <option value="Kuis">Kuis</option>
                                <option value="Tugas">Tugas</option>
                                <option value="Diskusi">Diskusi</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Kelas</label>
                            <select name="kelas" class="form-control">
                                <option value="">Semua Kelas</option>
                                <?php
                                mysqli_data_seek($kelas_list, 0);
                                while($k = mysqli_fetch_assoc($kelas_list)):
                                ?>
                                    <option value="<?= htmlspecialchars($k['kelas']); ?>"><?= htmlspecialchars($k['kelas']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Materi Terkait</label>
                            <select name="id_materi" class="form-control">
                                <option value="0">Tidak terkait materi</option>
                                <?php while($m = mysqli_fetch_assoc($materi)): ?>
                                    <option value="<?= $m['id_materi']; ?>"><?= $m['urutan']; ?>. <?= htmlspecialchars($m['judul_materi']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row-clean">
                        <div class="form-group"><label>Tanggal</label><input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required></div>
                        <div class="form-group"><label>Jam Mulai</label><input type="time" name="jam_mulai" class="form-control" value="08:00"></div>
                        <div class="form-group"><label>Jam Selesai</label><input type="time" name="jam_selesai" class="form-control" value="09:30"></div>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="4" placeholder="Tambahkan keterangan atau instruksi singkat..."></textarea>
                    </div>

                    <button type="submit" name="simpan_jadwal" class="btn btn-primary">Simpan Jadwal</button>
                </form>

                <div class="filter-card no-print" style="margin-top:18px;">
                    <form method="GET" class="filter-form">
                        <div class="form-group">
                            <label>Cari Jadwal</label>
                            <input type="text" name="keyword" class="form-control" value="<?= htmlspecialchars($keyword); ?>" placeholder="Cari judul/keterangan...">
                        </div>
                        <div class="form-group">
                            <label>Kelas</label>
                            <select name="kelas" class="form-control">
                                <option value="">Semua</option>
                                <?php
                                $kelas_filter = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM users WHERE role='siswa' AND kelas IS NOT NULL AND kelas != '' ORDER BY kelas ASC");
                                while($k = mysqli_fetch_assoc($kelas_filter)):
                                ?>
                                    <option value="<?= htmlspecialchars($k['kelas']); ?>" <?= $filter_kelas == $k['kelas'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($k['kelas']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group"><label>Tanggal</label><input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($filter_tanggal); ?>"></div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="jadwal.php" class="btn btn-outline">Reset</a>
                    </form>
                </div>
            </div>

            <div class="student-clean-card">
                <h3>Daftar Jadwal</h3>
                <p class="small-muted">Jadwal terbaru tampil paling atas.</p>
                <br>

                <?php if (!$jadwal || mysqli_num_rows($jadwal) == 0): ?>
                    <div class="empty-state">Belum ada jadwal.</div>
                <?php endif; ?>

                <?php if ($jadwal): ?>
                    <div class="schedule-list">
                        <?php while($j = mysqli_fetch_assoc($jadwal)): ?>
                            <div class="schedule-card">
                                <span class="badge badge-primary"><?= htmlspecialchars($j['kategori']); ?></span>
                                <h3><?= htmlspecialchars($j['judul']); ?></h3>
                                <div class="schedule-meta">
                                    <span class="badge badge-neutral">📅 <?= date('d M Y', strtotime($j['tanggal'])); ?></span>
                                    <span class="badge badge-success">⏰ <?= substr($j['jam_mulai'],0,5); ?> - <?= substr($j['jam_selesai'],0,5); ?></span>
                                    <span class="badge badge-warning">🏫 <?= htmlspecialchars($j['kelas'] ?: 'Semua Kelas'); ?></span>
                                </div>
                                <p class="small-muted">
                                    Materi: <?= htmlspecialchars($j['judul_materi'] ?: '-'); ?><br>
                                    Pembuat: <?= htmlspecialchars($j['pembuat'] ?: '-'); ?><br>
                                    <?= nl2br(htmlspecialchars($j['keterangan'] ?: '-')); ?>
                                </p>
                                <div class="action-row no-print">
                                    <a href="?hapus=<?= $j['id_jadwal']; ?>" onclick="return confirm('Hapus jadwal ini?')" class="btn btn-danger btn-sm">Hapus</a>
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
