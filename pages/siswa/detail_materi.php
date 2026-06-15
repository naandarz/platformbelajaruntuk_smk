<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('siswa');

$id = intval($_GET['id'] ?? 0);
$q = mysqli_query($koneksi, "SELECT * FROM materi WHERE id_materi=$id");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    die("Materi tidak ditemukan.");
}

$id_user = $_SESSION['user']['id_user'];

$prev = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM materi WHERE urutan < {$data['urutan']} ORDER BY urutan DESC LIMIT 1"));
$next = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM materi WHERE urutan > {$data['urutan']} ORDER BY urutan ASC LIMIT 1"));

$status = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM progres_belajar WHERE id_user=$id_user AND id_materi=$id AND status='selesai'"));

if (isset($_POST['selesai'])) {
    mysqli_query($koneksi, "INSERT INTO progres_belajar (id_user, id_materi, status, tanggal_selesai)
    VALUES ($id_user, $id, 'selesai', NOW())
    ON DUPLICATE KEY UPDATE status='selesai', tanggal_selesai=NOW()");
    header("Location: detail_materi.php?id=$id&status=selesai");
    exit;
}

$pesan = isset($_GET['status']) && $_GET['status'] == 'selesai' ? "Materi berhasil ditandai selesai." : "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $data['judul_materi']; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1><?= $data['judul_materi']; ?></h1>
                <p><?= $data['deskripsi']; ?></p>
            </div>
            <a href="materi.php" class="btn btn-outline">Daftar Materi</a>
        </div>

        <?php if ($pesan): ?>
            <div class="alert alert-success"><?= $pesan; ?></div>
        <?php endif; ?>

        <div class="lesson-box">
            <span class="badge <?= $status ? 'badge-success' : 'badge-primary'; ?>">
                <?= $status ? 'Sudah Selesai' : 'Materi ' . $data['urutan']; ?>
            </span>
            <h2 style="margin:14px 0;"><?= $data['judul_materi']; ?></h2>
            <p><?= nl2br($data['isi_materi']); ?></p>

            <h3 style="margin-top:24px;">Contoh Kode</h3>
            <pre><?= htmlspecialchars($data['contoh_kode']); ?></pre>

            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <a class="btn btn-primary" href="live_coding.php?id=<?= $data['id_materi']; ?>">Coba di Live Coding</a>
                <a class="btn btn-outline" href="kuis.php?id=<?= $data['id_materi']; ?>">Kerjakan Kuis</a>
                <form method="POST">
                    <button class="btn btn-outline" type="submit" name="selesai">Tandai Selesai</button>
                </form>
            </div>
        </div>

        <div class="card" style="margin-top:18px;">
            <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <div>
                    <?php if ($prev): ?>
                        <a class="btn btn-outline" href="detail_materi.php?id=<?= $prev['id_materi']; ?>">← Materi Sebelumnya</a>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if ($next): ?>
                        <a class="btn btn-primary" href="detail_materi.php?id=<?= $next['id_materi']; ?>">Materi Berikutnya →</a>
                    <?php else: ?>
                        <a class="btn btn-primary" href="live_coding.php?id=<?= $data['id_materi']; ?>">Latihan Akhir</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
