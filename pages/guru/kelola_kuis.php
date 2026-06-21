<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$pesan = "";

if (isset($_POST['tambah'])) {
    $id_materi = intval($_POST['id_materi']);
    $pertanyaan = mysqli_real_escape_string($koneksi, $_POST['pertanyaan']);
    $a = mysqli_real_escape_string($koneksi, $_POST['opsi_a']);
    $b = mysqli_real_escape_string($koneksi, $_POST['opsi_b']);
    $c = mysqli_real_escape_string($koneksi, $_POST['opsi_c']);
    $d = mysqli_real_escape_string($koneksi, $_POST['opsi_d']);
    $jawaban = mysqli_real_escape_string($koneksi, $_POST['jawaban_benar']);

    mysqli_query($koneksi, "INSERT INTO kuis (id_materi, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar)
    VALUES ($id_materi, '$pertanyaan', '$a', '$b', '$c', '$d', '$jawaban')");
    header("Location: kelola_kuis.php?status=ditambah");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM kuis WHERE id_kuis=$id");
    header("Location: kelola_kuis.php?status=dihapus");
    exit;
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'ditambah') $pesan = "Soal kuis berhasil ditambahkan.";
    if ($_GET['status'] == 'diupdate') $pesan = "Soal kuis berhasil diperbarui.";
    if ($_GET['status'] == 'dihapus') $pesan = "Soal kuis berhasil dihapus.";
    if ($_GET['status'] == 'import') {
        $jumlah = intval($_GET['jumlah'] ?? 0);
        $skip = intval($_GET['skip'] ?? 0);
        $pesan = "$jumlah soal berhasil diimport dari Word. $skip soal dilewati karena format belum valid.";
    }
}

$materi = mysqli_query($koneksi, "SELECT * FROM materi ORDER BY urutan ASC");
$kuis = mysqli_query($koneksi, "
    SELECT kuis.*, materi.judul_materi 
    FROM kuis 
    JOIN materi ON kuis.id_materi = materi.id_materi
    ORDER BY kuis.id_kuis DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kuis</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Kelola Kuis</h1>
                <p>Tambah, edit, dan hapus soal evaluasi untuk setiap materi web.</p>
            </div>
            <div class="page-actions">
                <a href="import_soal_word.php" class="btn btn-primary">Import Soal Word</a>
            </div>
        </div>

        <?php if ($pesan): ?>
            <div class="alert alert-success"><?= $pesan; ?></div>
        <?php endif; ?>

        <section class="content-grid">
            <form method="POST" class="card">
                <h3>Tambah Soal</h3>
                <br>
                <div class="form-group">
                    <label>Materi</label>
                    <select name="id_materi" class="form-control" required>
                        <?php while($m = mysqli_fetch_assoc($materi)): ?>
                            <option value="<?= $m['id_materi']; ?>"><?= $m['judul_materi']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pertanyaan</label>
                    <textarea name="pertanyaan" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group"><label>Opsi A</label><input type="text" name="opsi_a" class="form-control" required></div>
                <div class="form-group"><label>Opsi B</label><input type="text" name="opsi_b" class="form-control" required></div>
                <div class="form-group"><label>Opsi C</label><input type="text" name="opsi_c" class="form-control" required></div>
                <div class="form-group"><label>Opsi D</label><input type="text" name="opsi_d" class="form-control" required></div>
                <div class="form-group">
                    <label>Jawaban Benar</label>
                    <select name="jawaban_benar" class="form-control" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>
                <button type="submit" name="tambah" class="btn btn-primary">Simpan Soal</button>
            </form>

            <div class="card">
                <h3>Daftar Soal</h3>
                <p>Soal akan muncul pada halaman kuis siswa sesuai materi yang dipilih.</p>
                <br>
                <div class="module-list">
                    <?php if (mysqli_num_rows($kuis) == 0): ?>
                        <div class="empty-state">Belum ada soal kuis.</div>
                    <?php endif; ?>

                    <?php while($row = mysqli_fetch_assoc($kuis)): ?>
                        <div class="module-item">
                            <div>
                                <span class="badge badge-primary"><?= $row['judul_materi']; ?></span>
                                <h4 style="margin-top:10px;"><?= $row['pertanyaan']; ?></h4>
                                <p>Jawaban benar: <?= $row['jawaban_benar']; ?></p>
                            </div>
                            <div class="action-row">
                                <a href="edit_kuis.php?id=<?= $row['id_kuis']; ?>" class="btn btn-outline btn-sm">Edit</a>
                                <a href="?hapus=<?= $row['id_kuis']; ?>" onclick="return confirm('Hapus soal ini?')" class="btn btn-danger btn-sm">Hapus</a>
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
