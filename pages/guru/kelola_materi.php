<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$pesan = "";

if (isset($_POST['tambah'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul_materi']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi_materi']);
    $kode = mysqli_real_escape_string($koneksi, $_POST['contoh_kode']);
    $urutan = intval($_POST['urutan']);

    mysqli_query($koneksi, "INSERT INTO materi (judul_materi, deskripsi, isi_materi, contoh_kode, urutan)
    VALUES ('$judul', '$deskripsi', '$isi', '$kode', $urutan)");
    header("Location: kelola_materi.php?status=ditambah");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM materi WHERE id_materi=$id");
    header("Location: kelola_materi.php?status=dihapus");
    exit;
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'ditambah') $pesan = "Materi berhasil ditambahkan.";
    if ($_GET['status'] == 'diupdate') $pesan = "Materi berhasil diperbarui.";
    if ($_GET['status'] == 'dihapus') $pesan = "Materi berhasil dihapus.";
}

$materi = mysqli_query($koneksi, "SELECT * FROM materi ORDER BY urutan ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Materi</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Kelola Materi</h1>
                <p>Tambah, edit, dan hapus materi pembelajaran web dasar.</p>
            </div>
        </div>

        <?php if ($pesan): ?>
            <div class="alert alert-success"><?= $pesan; ?></div>
        <?php endif; ?>

        <section class="content-grid">
            <form method="POST" class="card">
                <h3>Tambah Materi</h3>
                <br>
                <div class="form-group">
                    <label>Judul Materi</label>
                    <input type="text" name="judul_materi" class="form-control" placeholder="Contoh: Struktur Dasar HTML" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Tulis ringkasan materi..." required></textarea>
                </div>
                <div class="form-group">
                    <label>Isi Materi</label>
                    <textarea name="isi_materi" class="form-control" rows="7" placeholder="Tulis penjelasan materi secara lengkap..." required></textarea>
                </div>
                <div class="form-group">
                    <label>Contoh Kode</label>
                    <textarea name="contoh_kode" class="form-control" rows="7" placeholder="<h1>Contoh Kode Web</h1>"></textarea>
                    <small class="form-help">Contoh kode ini dapat dicoba siswa melalui fitur Live Coding.</small>
                </div>
                <div class="form-group">
                    <label>Urutan Materi</label>
                    <input type="number" name="urutan" class="form-control" value="1" required>
                </div>
                <button type="submit" name="tambah" class="btn btn-primary">Simpan Materi</button>
            </form>

            <div class="card">
                <h3>Daftar Materi</h3>
                <p>Materi ditampilkan sesuai urutan pembelajaran siswa.</p>
                <br>
                <div class="module-list">
                    <?php if (mysqli_num_rows($materi) == 0): ?>
                        <div class="empty-state">Belum ada materi.</div>
                    <?php endif; ?>

                    <?php while($row = mysqli_fetch_assoc($materi)): ?>
                        <div class="module-item">
                            <div>
                                <span class="badge badge-primary">Urutan <?= $row['urutan']; ?></span>
                                <h4 style="margin-top:10px;"><?= $row['judul_materi']; ?></h4>
                                <p><?= $row['deskripsi']; ?></p>
                            </div>
                            <div class="action-row">
                                <a href="edit_materi.php?id=<?= $row['id_materi']; ?>" class="btn btn-outline btn-sm">Edit</a>
                                <a href="?hapus=<?= $row['id_materi']; ?>" onclick="return confirm('Hapus materi ini? Semua kuis terkait juga akan terhapus.')" class="btn btn-danger btn-sm">Hapus</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
