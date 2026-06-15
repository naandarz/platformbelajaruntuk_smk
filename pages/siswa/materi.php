<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('siswa');

$id_user = $_SESSION['user']['id_user'];
$keyword = mysqli_real_escape_string($koneksi, $_GET['keyword'] ?? '');

$where = "";
if ($keyword != "") {
    $where = "WHERE judul_materi LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%' OR isi_materi LIKE '%$keyword%'";
}

$materi = mysqli_query($koneksi, "SELECT * FROM materi $where ORDER BY urutan ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Materi Web</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Materi Web</h1>
                <p>Pelajari HTML, CSS, dan JavaScript dasar sampai mampu membuat halaman web sederhana yang menarik dan interaktif.</p>
            </div>
        </div>

        <div class="search-bar">
            <form method="GET">
                <input type="text" name="keyword" class="form-control" value="<?= htmlspecialchars($keyword); ?>" placeholder="Cari materi HTML, CSS, atau JavaScript...">
                <button type="submit" class="btn btn-primary">Cari</button>
                <a href="materi.php" class="btn btn-outline">Reset</a>
            </form>
        </div>

        <div class="module-list">
            <?php if (mysqli_num_rows($materi) == 0): ?>
                <div class="empty-state">Materi tidak ditemukan.</div>
            <?php endif; ?>

            <?php while ($row = mysqli_fetch_assoc($materi)): ?>
                <?php
                    $id_materi = $row['id_materi'];
                    $selesai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM progres_belajar WHERE id_user=$id_user AND id_materi=$id_materi AND status='selesai'"));
                    $nilai_terbaik = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT MAX(skor) AS skor FROM nilai WHERE id_user=$id_user AND id_materi=$id_materi"))['skor'];
                ?>
                <div class="module-item">
                    <div>
                        <span class="badge <?= $selesai ? 'badge-success' : 'badge-primary'; ?>">
                            <?= $selesai ? 'Selesai' : 'Materi ' . $row['urutan']; ?>
                        </span>
                        <h4 style="margin-top:10px;"><?= $row['judul_materi']; ?></h4>
                        <p><?= $row['deskripsi']; ?></p>
                        <?php if ($nilai_terbaik !== null): ?>
                            <br><span class="badge badge-warning">Nilai terbaik: <?= $nilai_terbaik; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="action-row">
                        <a href="detail_materi.php?id=<?= $row['id_materi']; ?>" class="btn btn-primary">Buka Materi</a>
                        <a href="kuis.php?id=<?= $row['id_materi']; ?>" class="btn btn-outline">Kuis</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
</div>
</body>
</html>
