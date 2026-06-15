<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$id = intval($_GET['id'] ?? 0);
$q = mysqli_query($koneksi, "SELECT * FROM materi WHERE id_materi=$id");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    die("Materi tidak ditemukan.");
}

if (isset($_POST['update'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul_materi']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi_materi']);
    $kode = mysqli_real_escape_string($koneksi, $_POST['contoh_kode']);
    $urutan = intval($_POST['urutan']);

    mysqli_query($koneksi, "UPDATE materi SET
        judul_materi='$judul',
        deskripsi='$deskripsi',
        isi_materi='$isi',
        contoh_kode='$kode',
        urutan=$urutan
        WHERE id_materi=$id
    ");

    header("Location: kelola_materi.php?status=diupdate");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Materi</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Edit Materi</h1>
                <p>Perbarui isi materi pembelajaran web dasar.</p>
            </div>
            <a href="kelola_materi.php" class="btn btn-outline">Kembali</a>
        </div>

        <form method="POST" class="lesson-box">
            <div class="form-group">
                <label>Judul Materi</label>
                <input type="text" name="judul_materi" class="form-control" value="<?= htmlspecialchars($data['judul_materi']); ?>" required>
            </div>

            <div class="form-group">
                <label>Deskripsi Singkat</label>
                <textarea name="deskripsi" class="form-control" rows="3" required><?= htmlspecialchars($data['deskripsi']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Isi Materi</label>
                <textarea name="isi_materi" class="form-control" rows="8" required><?= htmlspecialchars($data['isi_materi']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Contoh Kode</label>
                <textarea name="contoh_kode" class="form-control" rows="8"><?= htmlspecialchars($data['contoh_kode']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Urutan Materi</label>
                <input type="number" name="urutan" class="form-control" value="<?= $data['urutan']; ?>" required>
            </div>

            <div class="page-actions">
                <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                <a href="kelola_materi.php" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </main>
</div>
</body>
</html>
