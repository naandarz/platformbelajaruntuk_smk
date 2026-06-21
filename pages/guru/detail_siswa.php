<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$id_user = intval($_GET['id'] ?? 0);

$siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user=$id_user AND role='siswa'"));
if (!$siswa) {
    die("Data siswa tidak ditemukan.");
}

$total_materi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM materi"))['total'];
$total_selesai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM progres_belajar WHERE id_user=$id_user AND status='selesai'"))['total'];
$rata_nilai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT AVG(skor) AS rata FROM nilai WHERE id_user=$id_user"))['rata'];
$total_latihan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM latihan_kode WHERE id_user=$id_user"))['total'];
$persen = $total_materi > 0 ? round(($total_selesai / $total_materi) * 100) : 0;

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
    WHERE progres_belajar.id_user=$id_user AND progres_belajar.status='selesai'
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
    <title>Detail Siswa</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Detail Progress Siswa</h1>
                <p>Data lengkap aktivitas belajar <?= $siswa['nama']; ?>.</p>
            </div>
            <div class="page-actions">
                <a href="laporan.php" class="btn btn-outline">Kembali</a>
                <button class="btn btn-outline" onclick="window.print()">Cetak</button>
            </div>
        </div>

        <section class="stats-grid">
            <div class="stat-card"><span>Materi Selesai</span><h2><?= $total_selesai; ?>/<?= $total_materi; ?></h2></div>
            <div class="stat-card"><span>Progress</span><h2><?= $persen; ?>%</h2></div>
            <div class="stat-card"><span>Rata-rata Nilai</span><h2><?= $rata_nilai ? round($rata_nilai) : 0; ?></h2></div>
            <div class="stat-card"><span>Latihan Coding</span><h2><?= $total_latihan; ?></h2></div>
        </section>

        <section class="detail-grid">
            <div class="card">
                <h3>Informasi Siswa</h3>
                <br>
                <div class="info-list">
                    <div class="info-item"><span>Nama</span><strong><?= $siswa['nama']; ?></strong></div>
                    <div class="info-item"><span>Email</span><strong><?= $siswa['email']; ?></strong></div>
                    <div class="info-item"><span>Kelas</span><strong><?= $siswa['kelas']; ?></strong></div>
                    <div class="info-item"><span>Progress</span><strong><?= $persen; ?>%</strong></div>
                </div>

                <br>
                <h3>Materi Selesai</h3>
                <br>
                <?php if (mysqli_num_rows($progres) == 0): ?>
                    <div class="empty-state">Belum ada materi selesai.</div>
                <?php endif; ?>

                <?php while($row = mysqli_fetch_assoc($progres)): ?>
                    <p>✅ <?= $row['judul_materi']; ?></p>
                    <br>
                <?php endwhile; ?>
            </div>

            <div>
                <div class="table-wrapper" style="margin-bottom:18px;">
                    <table>
                        <thead>
                            <tr>
                                <th>Materi</th>
                                <th>Nilai</th>
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

                <div class="card">
                    <h3>Latihan Coding Siswa</h3>
                    <p>Guru dapat melihat hasil kode web yang disimpan siswa dari fitur Live Coding.</p>
                    <br>

                    <div class="module-list">
                        <?php if (mysqli_num_rows($latihan) == 0): ?>
                            <div class="empty-state">Belum ada latihan coding.</div>
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
            </div>
        </section>
    </main>
</div>
</body>
</html>
