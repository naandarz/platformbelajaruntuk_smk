<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='siswa'"))['total'];
$total_materi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM materi"))['total'];
$total_kuis = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kuis"))['total'];
$rata_nilai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT AVG(skor) AS rata FROM nilai"))['rata'];

$nilai = mysqli_query($koneksi, "
    SELECT nilai.*, users.nama, users.kelas, materi.judul_materi 
    FROM nilai 
    JOIN users ON nilai.id_user = users.id_user
    JOIN materi ON nilai.id_materi = materi.id_materi
    ORDER BY nilai.tanggal DESC
    LIMIT 8
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Guru</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Dashboard Guru</h1>
                <p>Pantau aktivitas belajar siswa dan kelola pembelajaran web dasar.</p>
            </div>
            <div class="user-pill">
                <div class="avatar">G</div>
                Guru
            </div>
        </div>

        <section class="stats-grid">
            <div class="stat-card"><span>Total Siswa</span><h2><?= $total_siswa; ?></h2></div>
            <div class="stat-card"><span>Total Materi</span><h2><?= $total_materi; ?></h2></div>
            <div class="stat-card"><span>Total Soal</span><h2><?= $total_kuis; ?></h2></div>
            <div class="stat-card"><span>Rata-rata Nilai</span><h2><?= $rata_nilai ? round($rata_nilai) : 0; ?></h2></div>
        </section>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Materi</th>
                        <th>Nilai</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($nilai)): ?>
                        <tr>
                            <td><?= $row['nama']; ?></td>
                            <td><?= $row['kelas']; ?></td>
                            <td><?= $row['judul_materi']; ?></td>
                            <td><span class="badge badge-primary"><?= $row['skor']; ?></span></td>
                            <td><?= $row['tanggal']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>
