<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$id = intval($_GET['id'] ?? 0);
$q = mysqli_query($koneksi, "SELECT * FROM game_questions WHERE id_question=$id");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    die("Pertanyaan game tidak ditemukan.");
}

if (isset($_POST['update'])) {
    $mode = mysqli_real_escape_string($koneksi, $_POST['mode_game']);
    $pertanyaan = mysqli_real_escape_string($koneksi, $_POST['pertanyaan']);
    $kode = mysqli_real_escape_string($koneksi, $_POST['kode']);
    $a = mysqli_real_escape_string($koneksi, $_POST['opsi_a']);
    $b = mysqli_real_escape_string($koneksi, $_POST['opsi_b']);
    $c = mysqli_real_escape_string($koneksi, $_POST['opsi_c']);
    $d = mysqli_real_escape_string($koneksi, $_POST['opsi_d']);
    $jawaban = mysqli_real_escape_string($koneksi, $_POST['jawaban_benar']);
    $pembahasan = mysqli_real_escape_string($koneksi, $_POST['pembahasan']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);

    mysqli_query($koneksi, "UPDATE game_questions SET
        mode_game='$mode',
        pertanyaan='$pertanyaan',
        kode='$kode',
        opsi_a='$a',
        opsi_b='$b',
        opsi_c='$c',
        opsi_d='$d',
        jawaban_benar='$jawaban',
        pembahasan='$pembahasan',
        status='$status'
        WHERE id_question=$id
    ");

    header("Location: kelola_game.php?status=diupdate");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pertanyaan Game</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Edit Pertanyaan Game</h1>
                <p>Perbarui soal, pilihan jawaban, kunci, pembahasan, dan status game.</p>
            </div>
            <a href="kelola_game.php" class="btn btn-outline">Kembali</a>
        </div>

        <form method="POST" class="lesson-box">
            <div class="form-group">
                <label>Mode Game</label>
                <select name="mode_game" class="form-control" required>
                    <option value="HTML" <?= $data['mode_game'] == 'HTML' ? 'selected' : ''; ?>>HTML</option>
                    <option value="CSS" <?= $data['mode_game'] == 'CSS' ? 'selected' : ''; ?>>CSS</option>
                    <option value="JavaScript" <?= $data['mode_game'] == 'JavaScript' ? 'selected' : ''; ?>>JavaScript</option>
                </select>
            </div>

            <div class="form-group">
                <label>Pertanyaan</label>
                <textarea name="pertanyaan" class="form-control" rows="3" required><?= htmlspecialchars($data['pertanyaan']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Kode Pendukung</label>
                <textarea name="kode" class="form-control" rows="5"><?= htmlspecialchars($data['kode']); ?></textarea>
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

            <div class="form-group">
                <label>Pembahasan</label>
                <textarea name="pembahasan" class="form-control" rows="3"><?= htmlspecialchars($data['pembahasan']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="aktif" <?= $data['status'] == 'aktif' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="nonaktif" <?= $data['status'] == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                </select>
            </div>

            <div class="page-actions">
                <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                <a href="kelola_game.php" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </main>
</div>
</body>
</html>
