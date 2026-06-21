<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('siswa');

$id_materi = intval($_GET['id'] ?? 0);
$id_user = $_SESSION['user']['id_user'];

$materi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM materi WHERE id_materi=$id_materi"));
$soal = mysqli_query($koneksi, "SELECT * FROM kuis WHERE id_materi=$id_materi");
$total_soal = mysqli_num_rows($soal);
$hasil = null;
$jawaban_siswa = [];
$pesan_progress = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $benar = 0;
    $jawaban_siswa = $_POST['jawaban'] ?? [];
    $soal_cek = mysqli_query($koneksi, "SELECT * FROM kuis WHERE id_materi=$id_materi");

    while ($row = mysqli_fetch_assoc($soal_cek)) {
        $jawaban_user = $jawaban_siswa[$row['id_kuis']] ?? '';
        if ($jawaban_user === $row['jawaban_benar']) {
            $benar++;
        }
    }

    $skor = $total_soal > 0 ? round(($benar / $total_soal) * 100) : 0;
    mysqli_query($koneksi, "INSERT INTO nilai (id_user, id_materi, skor) VALUES ($id_user, $id_materi, $skor)");
    $hasil = $skor;

    if ($skor >= 75 && $id_materi > 0) {
        mysqli_query($koneksi, "INSERT INTO progres_belajar (id_user, id_materi, status, tanggal_selesai)
        VALUES ($id_user, $id_materi, 'selesai', NOW())
        ON DUPLICATE KEY UPDATE status='selesai', tanggal_selesai=NOW()");
        $pesan_progress = "Nilai kamu sudah mencapai batas ketuntasan. Materi otomatis ditandai selesai.";
    }

    $soal = mysqli_query($koneksi, "SELECT * FROM kuis WHERE id_materi=$id_materi");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuis HTML</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Kuis Interaktif</h1>
                <p><?= $materi ? 'Materi: ' . $materi['judul_materi'] : 'Jawab pertanyaan berikut untuk mengukur pemahaman materi.'; ?></p>
            </div>
            <a href="materi.php" class="btn btn-outline">Kembali ke Materi</a>
        </div>

        <?php if ($hasil !== null): ?>
            <div class="card <?= $hasil >= 75 ? 'completion-card' : ''; ?>" style="margin-bottom:18px;">
                <h2>Skor Kamu: <?= $hasil; ?></h2>
                <p><?= $hasil >= 75 ? 'Bagus! Kamu sudah memahami materi ini.' : 'Tetap semangat. Pelajari kembali materi dan coba lagi.'; ?></p>
            </div>

            <?php if ($pesan_progress): ?>
                <div class="alert alert-success"><?= $pesan_progress; ?></div>
            <?php endif; ?>
        <?php endif; ?>

        <form method="POST" class="lesson-box">
            <?php if ($total_soal == 0): ?>
                <div class="empty-state">Belum ada soal untuk materi ini.</div>
            <?php else: ?>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($soal)): ?>
                    <div style="margin-bottom:24px;">
                        <h3><?= $no++; ?>. <?= $row['pertanyaan']; ?></h3>
                        <label class="quiz-option"><input type="radio" name="jawaban[<?= $row['id_kuis']; ?>]" value="A" <?= ($jawaban_siswa[$row['id_kuis']] ?? '') == 'A' ? 'checked' : ''; ?> required> A. <?= htmlspecialchars($row['opsi_a']); ?></label>
                        <label class="quiz-option"><input type="radio" name="jawaban[<?= $row['id_kuis']; ?>]" value="B" <?= ($jawaban_siswa[$row['id_kuis']] ?? '') == 'B' ? 'checked' : ''; ?>> B. <?= htmlspecialchars($row['opsi_b']); ?></label>
                        <label class="quiz-option"><input type="radio" name="jawaban[<?= $row['id_kuis']; ?>]" value="C" <?= ($jawaban_siswa[$row['id_kuis']] ?? '') == 'C' ? 'checked' : ''; ?>> C. <?= htmlspecialchars($row['opsi_c']); ?></label>
                        <label class="quiz-option"><input type="radio" name="jawaban[<?= $row['id_kuis']; ?>]" value="D" <?= ($jawaban_siswa[$row['id_kuis']] ?? '') == 'D' ? 'checked' : ''; ?>> D. <?= htmlspecialchars($row['opsi_d']); ?></label>

                        <?php if ($hasil !== null): ?>
                            <?php $jawaban_user = $jawaban_siswa[$row['id_kuis']] ?? '-'; ?>
                            <div class="alert <?= $jawaban_user == $row['jawaban_benar'] ? 'alert-success' : 'alert-warning'; ?>">
                                Jawaban kamu: <?= $jawaban_user; ?> |
                                Jawaban benar: <?= $row['jawaban_benar']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>

                <?php if ($hasil === null): ?>
                    <button class="btn btn-primary" type="submit">Kirim Jawaban</button>
                <?php else: ?>
                    <div class="page-actions">
                        <a href="kuis.php?id=<?= $id_materi; ?>" class="btn btn-primary">Coba Lagi</a>
                        <a href="materi.php" class="btn btn-outline">Kembali ke Materi</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </form>
    </main>
</div>
</body>
</html>
