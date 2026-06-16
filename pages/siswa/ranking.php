<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('siswa');

$total_materi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM materi"))['total'];

$query = mysqli_query($koneksi, "
    SELECT 
        users.id_user,
        users.nama,
        users.kelas,
        COUNT(DISTINCT progres_belajar.id_materi) AS materi_selesai,
        COALESCE(ROUND(AVG(nilai.skor)), 0) AS rata_nilai,
        COALESCE(MAX(game_scores.skor), 0) AS skor_game,
        COUNT(DISTINCT pengumpulan_tugas.id_pengumpulan) AS total_pengumpulan
    FROM users
    LEFT JOIN progres_belajar 
        ON users.id_user = progres_belajar.id_user 
        AND progres_belajar.status='selesai'
    LEFT JOIN nilai ON users.id_user = nilai.id_user
    LEFT JOIN game_scores ON users.id_user = game_scores.id_user
    LEFT JOIN pengumpulan_tugas ON users.id_user = pengumpulan_tugas.id_user
    WHERE users.role='siswa'
    GROUP BY users.id_user, users.nama, users.kelas
");

$ranking = [];

while($row = mysqli_fetch_assoc($query)) {
    $progress = $total_materi > 0 ? round(($row['materi_selesai'] / $total_materi) * 100) : 0;
    $nilai = intval($row['rata_nilai']);
    $game = intval($row['skor_game']);
    $game_normal = min(100, round($game / 2));
    $tugas_bonus = min(10, intval($row['total_pengumpulan']) * 2);

    $score_total = round(($progress * 0.4) + ($nilai * 0.35) + ($game_normal * 0.2) + $tugas_bonus);

    $row['progress'] = $progress;
    $row['score_total'] = $score_total;
    $ranking[] = $row;
}

usort($ranking, function($a, $b) {
    return $b['score_total'] <=> $a['score_total'];
});
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ranking Siswa</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Ranking Siswa</h1>
                <p>Peringkat dihitung dari progress materi, rata-rata nilai kuis, skor game, dan tugas terkumpul.</p>
            </div>
            <div class="page-actions no-print">
                <button class="btn btn-primary" onclick="window.print()">Export PDF</button>
            </div>
        </div>

        <div class="chart-card">
            <h3>Grafik Top 5 Ranking</h3>
            <p>Grafik ini menampilkan skor total siswa tertinggi.</p>

            <div class="simple-bar-chart">
                <?php foreach(array_slice($ranking, 0, 5) as $row): ?>
                    <div class="chart-row">
                        <div class="chart-label"><?= htmlspecialchars($row['nama']); ?></div>
                        <div class="chart-track">
                            <div class="chart-fill" style="width:<?= min(100, $row['score_total']); ?>%;"></div>
                        </div>
                        <strong><?= $row['score_total']; ?></strong>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div>
            <?php if (count($ranking) == 0): ?>
                <div class="empty-state">Belum ada data siswa.</div>
            <?php endif; ?>

            <?php $no = 1; foreach($ranking as $row): ?>
                <div class="rank-card">
                    <div class="rank-number"><?= $no; ?></div>
                    <div>
                        <h3><?= htmlspecialchars($row['nama']); ?></h3>
                        <p>Kelas: <?= htmlspecialchars($row['kelas'] ?: '-'); ?></p>
                        <div class="task-status">
                            <span class="badge badge-primary">Progress <?= $row['progress']; ?>%</span>
                            <span class="badge badge-success">Nilai <?= $row['rata_nilai']; ?></span>
                            <span class="badge badge-warning">Game <?= $row['skor_game']; ?></span>
                            <span class="badge badge-primary">Tugas <?= $row['total_pengumpulan']; ?></span>
                        </div>
                    </div>
                    <div class="rank-score"><?= $row['score_total']; ?></div>
                </div>
            <?php $no++; endforeach; ?>
        </div>
    </main>
</div>
</body>
</html>
