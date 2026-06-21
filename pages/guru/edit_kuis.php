<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$id = intval($_GET['id'] ?? 0);
$q = mysqli_query($koneksi, "SELECT * FROM kuis WHERE id_kuis=$id");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    die("Soal kuis tidak ditemukan.");
}

$materi = mysqli_query($koneksi, "SELECT * FROM materi ORDER BY urutan ASC");

if (isset($_POST['update'])) {
    $id_materi = intval($_POST['id_materi']);
    $pertanyaan = mysqli_real_escape_string($koneksi, $_POST['pertanyaan']);
    $a = mysqli_real_escape_string($koneksi, $_POST['opsi_a']);
    $b = mysqli_real_escape_string($koneksi, $_POST['opsi_b']);
    $c = mysqli_real_escape_string($koneksi, $_POST['opsi_c']);
    $d = mysqli_real_escape_string($koneksi, $_POST['opsi_d']);
    $jawaban = mysqli_real_escape_string($koneksi, $_POST['jawaban_benar']);

    mysqli_query($koneksi, "UPDATE kuis SET
        id_materi=$id_materi,
        pertanyaan='$pertanyaan',
        opsi_a='$a',
        opsi_b='$b',
        opsi_c='$c',
        opsi_d='$d',
        jawaban_benar='$jawaban'
        WHERE id_kuis=$id
    ");

    header("Location: kelola_kuis.php?status=diupdate");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kuis</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Edit Soal Kuis</h1>
                <p>Perbarui pertanyaan, pilihan jawaban, dan kunci jawaban.</p>
            </div>
            <a href="kelola_kuis.php" class="btn btn-outline">Kembali</a>
        </div>

        <form method="POST" class="lesson-box">
            <div class="form-group">
                <label>Materi</label>
                <select name="id_materi" class="form-control" required>
                    <?php while($m = mysqli_fetch_assoc($materi)): ?>
                        <option value="<?= $m['id_materi']; ?>" <?= $m['id_materi'] == $data['id_materi'] ? 'selected' : ''; ?>>
                            <?= $m['judul_materi']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Pertanyaan</label>
                <textarea name="pertanyaan" class="form-control" rows="3" required><?= htmlspecialchars($data['pertanyaan']); ?></textarea>
            </div>

            <div class="form-group"><label>Opsi A</label><input type="text" name="opsi_a" class="form-control" value="<?= htmlspecialchars($data['opsi_a']); ?>" required></div>
            <div class="form-group"><label>Opsi B</label><input type="text" name="opsi_b" class="form-control" value="<?= htmlspecialchars($data['opsi_b']); ?>" required></div>
            <div class="form-group"><label>Opsi C</label><input type="text" name="opsi_c" class="form-control" value="<?= htmlspecialchars($data['opsi_c']); ?>" required></div>
            <div class="form-group"><label>Opsi D</label><input type="text" name="opsi_d" class="form-control" value="<?= htmlspecialchars($data['opsi_d']); ?>" required></div>

            <div class="form-group">
                <label>Jawaban Benar</label>
                <select name="jawaban_benar" class="form-control" required>
                    <option value="A" <?= $data['jawaban_benar'] == 'A' ? 'selected' : ''; ?>>A</option>
                    <option value="B" <?= $data['jawaban_benar'] == 'B' ? 'selected' : ''; ?>>B</option>
                    <option value="C" <?= $data['jawaban_benar'] == 'C' ? 'selected' : ''; ?>>C</option>
                    <option value="D" <?= $data['jawaban_benar'] == 'D' ? 'selected' : ''; ?>>D</option>
                </select>
            </div>

            <div class="page-actions">
                <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                <a href="kelola_kuis.php" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </main>
</div>
</body>
</html>
