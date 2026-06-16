<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$pesan = "";
$error = "";

if (isset($_POST['buat_kelompok'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_kelompok']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    mysqli_query($koneksi, "INSERT INTO kelompok (nama_kelompok, deskripsi) VALUES ('$nama', '$deskripsi')");
    header("Location: kelola_kelompok.php?status=buat");
    exit;
}

if (isset($_POST['tambah_anggota'])) {
    $id_kelompok = intval($_POST['id_kelompok']);
    $id_user = intval($_POST['id_user']);
    $peran = mysqli_real_escape_string($koneksi, $_POST['peran'] ?? 'anggota');

    mysqli_query($koneksi, "INSERT IGNORE INTO kelompok_anggota (id_kelompok, id_user, peran) VALUES ($id_kelompok, $id_user, '$peran')");
    header("Location: kelola_kelompok.php?status=anggota");
    exit;
}

if (isset($_GET['hapus_anggota'])) {
    $id = intval($_GET['hapus_anggota']);
    mysqli_query($koneksi, "DELETE FROM kelompok_anggota WHERE id_anggota=$id");
    header("Location: kelola_kelompok.php?status=hapusanggota");
    exit;
}

if (isset($_GET['hapus_kelompok'])) {
    $id = intval($_GET['hapus_kelompok']);
    mysqli_query($koneksi, "DELETE FROM kelompok WHERE id_kelompok=$id");
    header("Location: kelola_kelompok.php?status=hapuskelompok");
    exit;
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'buat') $pesan = "Kelompok berhasil dibuat.";
    if ($_GET['status'] == 'anggota') $pesan = "Anggota berhasil ditambahkan ke kelompok.";
    if ($_GET['status'] == 'hapusanggota') $pesan = "Anggota berhasil dihapus dari kelompok.";
    if ($_GET['status'] == 'hapuskelompok') $pesan = "Kelompok berhasil dihapus.";
}

$siswa = mysqli_query($koneksi, "SELECT * FROM users WHERE role='siswa' ORDER BY kelas ASC, nama ASC");
$kelompok = mysqli_query($koneksi, "SELECT * FROM kelompok ORDER BY created_at DESC");
$total_kelompok = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kelompok"))['total'];
$total_anggota = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kelompok_anggota"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kelompok</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Kelola Kelompok</h1>
                <p>Guru dapat membuat kelompok belajar dan mengatur anggota untuk tugas kelompok.</p>
            </div>
            <a href="kelola_tugas.php" class="btn btn-outline">Kelola Tugas</a>
        </div>

        <?php if ($pesan): ?><div class="alert alert-success"><?= $pesan; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error; ?></div><?php endif; ?>

        <section class="stats-grid">
            <div class="stat-card"><span>Total Kelompok</span><h2><?= $total_kelompok; ?></h2></div>
            <div class="stat-card"><span>Total Anggota</span><h2><?= $total_anggota; ?></h2></div>
            <div class="stat-card"><span>Jenis Tugas</span><h2>Individu</h2></div>
            <div class="stat-card"><span>Jenis Tugas</span><h2>Kelompok</h2></div>
        </section>

        <section class="content-grid">
            <div>
                <form method="POST" class="student-clean-card">
                    <span class="badge badge-primary">Kelompok Baru</span>
                    <h3 style="margin-top:12px;">Buat Kelompok</h3>
                    <p class="small-muted">Buat kelompok untuk pembagian tugas kelompok siswa.</p>
                    <br>

                    <div class="form-group">
                        <label>Nama Kelompok</label>
                        <input type="text" name="nama_kelompok" class="form-control" placeholder="Contoh: Kelompok Web Kreatif" required>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Deskripsi singkat kelompok..."></textarea>
                    </div>

                    <button type="submit" name="buat_kelompok" class="btn btn-primary">Simpan Kelompok</button>
                </form>

                <form method="POST" class="student-clean-card" style="margin-top:18px;">
                    <span class="badge badge-success">Anggota</span>
                    <h3 style="margin-top:12px;">Tambah Anggota Kelompok</h3>
                    <br>

                    <div class="form-group">
                        <label>Kelompok</label>
                        <select name="id_kelompok" class="form-control" required>
                            <option value="">Pilih kelompok</option>
                            <?php
                            $kelompok_select = mysqli_query($koneksi, "SELECT * FROM kelompok ORDER BY nama_kelompok ASC");
                            while($k = mysqli_fetch_assoc($kelompok_select)):
                            ?>
                                <option value="<?= $k['id_kelompok']; ?>"><?= htmlspecialchars($k['nama_kelompok']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Siswa</label>
                        <select name="id_user" class="form-control" required>
                            <option value="">Pilih siswa</option>
                            <?php while($s = mysqli_fetch_assoc($siswa)): ?>
                                <option value="<?= $s['id_user']; ?>"><?= htmlspecialchars($s['nama']); ?> <?= $s['kelas'] ? '- ' . htmlspecialchars($s['kelas']) : ''; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Peran</label>
                        <select name="peran" class="form-control">
                            <option value="anggota">Anggota</option>
                            <option value="ketua">Ketua</option>
                        </select>
                    </div>

                    <button type="submit" name="tambah_anggota" class="btn btn-primary">Tambah Anggota</button>
                </form>
            </div>

            <div>
                <?php if (!$kelompok || mysqli_num_rows($kelompok) == 0): ?>
                    <div class="empty-state">Belum ada kelompok.</div>
                <?php endif; ?>

                <?php if ($kelompok): ?>
                    <?php while($k = mysqli_fetch_assoc($kelompok)): ?>
                        <div class="group-card">
                            <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                                <div>
                                    <span class="badge badge-primary">Kelompok</span>
                                    <h3 style="margin-top:12px;"><?= htmlspecialchars($k['nama_kelompok']); ?></h3>
                                    <p><?= nl2br(htmlspecialchars($k['deskripsi'] ?: 'Belum ada deskripsi.')); ?></p>
                                </div>
                                <a href="?hapus_kelompok=<?= $k['id_kelompok']; ?>" onclick="return confirm('Hapus kelompok ini?')" class="btn btn-danger btn-sm">Hapus</a>
                            </div>

                            <div class="group-member-list">
                                <?php
                                $id_kelompok = intval($k['id_kelompok']);
                                $anggota = mysqli_query($koneksi, "
                                    SELECT kelompok_anggota.*, users.nama, users.kelas
                                    FROM kelompok_anggota
                                    JOIN users ON kelompok_anggota.id_user = users.id_user
                                    WHERE kelompok_anggota.id_kelompok=$id_kelompok
                                    ORDER BY kelompok_anggota.peran DESC, users.nama ASC
                                ");
                                ?>

                                <?php if (!$anggota || mysqli_num_rows($anggota) == 0): ?>
                                    <div class="empty-state">Belum ada anggota.</div>
                                <?php endif; ?>

                                <?php if ($anggota): ?>
                                    <?php while($a = mysqli_fetch_assoc($anggota)): ?>
                                        <div class="group-member">
                                            <div>
                                                <strong><?= htmlspecialchars($a['nama']); ?></strong><br>
                                                <span class="small-muted"><?= htmlspecialchars($a['kelas'] ?: '-'); ?> | <?= htmlspecialchars($a['peran']); ?></span>
                                            </div>
                                            <a href="?hapus_anggota=<?= $a['id_anggota']; ?>" onclick="return confirm('Hapus anggota ini?')" class="btn btn-outline btn-sm">Hapus</a>
                                        </div>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>
</body>
</html>
