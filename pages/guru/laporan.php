<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$total_materi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM materi"))['total'];

$laporan = mysqli_query($koneksi, "
    SELECT 
        users.id_user,
        users.nama, 
        users.kelas,
        COUNT(DISTINCT progres_belajar.id_materi) AS materi_selesai,
        COALESCE(ROUND(AVG(nilai.skor)), 0) AS rata_nilai,
        MAX(nilai.tanggal) AS aktivitas_terakhir
    FROM users
    LEFT JOIN progres_belajar ON users.id_user = progres_belajar.id_user AND progres_belajar.status='selesai'
    LEFT JOIN nilai ON users.id_user = nilai.id_user
    WHERE users.role='siswa'
    GROUP BY users.id_user, users.nama, users.kelas
    ORDER BY users.nama ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Nilai</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Laporan Progress Siswa</h1>
                <p>Data progress belajar dan rata-rata nilai siswa.</p>
            </div>
            <div class="page-actions">
                <a class="btn btn-primary" href="export_laporan.php">Export CSV</a>
                <button class="btn btn-outline" onclick="window.print()">Cetak Laporan</button>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Materi Selesai</th>
                        <th>Progress</th>
                        <th>Rata-rata Nilai</th>
                        <th>Aktivitas Terakhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($laporan)): ?>
                        <?php $persen = $total_materi > 0 ? round(($row['materi_selesai'] / $total_materi) * 100) : 0; ?>
                        <tr>
                            <td><?= $row['nama']; ?></td>
                            <td><?= $row['kelas']; ?></td>
                            <td><?= $row['materi_selesai']; ?> / <?= $total_materi; ?></td>
                            <td>
                                <span class="badge <?= $persen >= 75 ? 'badge-success' : 'badge-warning'; ?>"><?= $persen; ?>%</span>
                            </td>
                            <td><span class="badge badge-primary"><?= $row['rata_nilai']; ?></span></td>
                            <td><?= $row['aktivitas_terakhir'] ?: '-'; ?></td>
                            <td><a class="btn btn-outline btn-sm" href="detail_siswa.php?id=<?= $row['id_user']; ?>">Detail</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>
