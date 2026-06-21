<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('siswa');

$id_user = $_SESSION['user']['id_user'];

$nilai = mysqli_query($koneksi, "
    SELECT nilai.*, materi.judul_materi 
    FROM nilai 
    JOIN materi ON nilai.id_materi = materi.id_materi
    WHERE nilai.id_user=$id_user 
    ORDER BY nilai.tanggal DESC
");

$progres = mysqli_query($koneksi, "
    SELECT progres_belajar.*, materi.judul_materi 
    FROM progres_belajar 
    JOIN materi ON progres_belajar.id_materi = materi.id_materi
    WHERE progres_belajar.id_user=$id_user 
    ORDER BY progres_belajar.tanggal_selesai DESC
");

$latihan = mysqli_query($koneksi, "
    SELECT latihan_kode.*, materi.judul_materi
    FROM latihan_kode
    JOIN materi ON latihan_kode.id_materi = materi.id_materi
    WHERE latihan_kode.id_user=$id_user
    ORDER BY latihan_kode.tanggal_simpan DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Belajar</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Riwayat Belajar</h1>
                <p>Ringkasan terbaru progress, nilai kuis, dan hasil latihan coding web yang sudah kamu kerjakan.</p>
            </div>
            <div class="page-actions">
                <a href="riwayat_semua.php" class="btn btn-primary">Lihat Semua Riwayat</a>
            </div>
        </div>

        <div class="student-page-grid">
            <div class="student-clean-card student-table-clean">
                <table>
                    <thead>
                        <tr>
                            <th>Materi</th>
                            <th>Skor</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($nilai) == 0): ?>
                            <tr><td colspan="3">Belum ada nilai kuis.</td></tr>
                        <?php endif; ?>

                        <?php while($row = mysqli_fetch_assoc($nilai)): ?>
                            <tr>
                                <td><?= $row['judul_materi']; ?></td>
                                <td><span class="badge badge-primary"><?= $row['skor']; ?></span></td>
                                <td><?= $row['tanggal']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="student-clean-card">
                <h3>Materi Selesai</h3>
                <br>
                <?php if (mysqli_num_rows($progres) == 0): ?>
                    <div class="empty-state">Belum ada materi yang ditandai selesai.</div>
                <?php endif; ?>

                <?php while($row = mysqli_fetch_assoc($progres)): ?>
                    <p>✅ <?= $row['judul_materi']; ?></p>
                    <br>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="student-clean-card" style="margin-top:18px;">
            <h3>Riwayat Latihan Coding Terbaru</h3>
            <p>Kode terbaru yang kamu simpan melalui fitur Live Coding akan tampil di sini. Untuk melihat semua data, klik tombol Lihat Semua Riwayat.</p>
            <br>

            <div class="module-list">
                <?php if (mysqli_num_rows($latihan) == 0): ?>
                    <div class="empty-state">Belum ada latihan coding yang disimpan.</div>
                <?php endif; ?>

                <?php while($row = mysqli_fetch_assoc($latihan)): ?>
                    <div class="module-item" style="align-items:flex-start;">
                        <div style="width:100%;">
                            <span class="badge badge-primary"><?= $row['judul_materi']; ?></span>
                            <h4 style="margin-top:10px;"><?= $row['catatan'] ?: 'Latihan Coding Web'; ?></h4>
                            <p>Disimpan pada: <?= $row['tanggal_simpan']; ?></p>
                            <br>
                            <div class="code-preview-mini"><?= htmlspecialchars($row['kode_html']); ?></div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>
</div>
</body>
</html>
