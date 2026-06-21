<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$keyword = mysqli_real_escape_string($koneksi, $_GET['keyword'] ?? '');
$mode = mysqli_real_escape_string($koneksi, $_GET['mode'] ?? '');

$where = "WHERE 1=1";

if ($keyword != "") {
    $where .= " AND (users.nama LIKE '%$keyword%' OR users.kelas LIKE '%$keyword%')";
}

if ($mode != "") {
    $where .= " AND game_scores.mode_game='$mode'";
}

$scores = mysqli_query($koneksi, "
    SELECT game_scores.*, users.nama, users.kelas
    FROM game_scores
    JOIN users ON game_scores.id_user = users.id_user
    $where
    ORDER BY game_scores.tanggal_main DESC
");

$top = mysqli_query($koneksi, "
    SELECT users.nama, users.kelas, MAX(game_scores.skor) AS skor_terbaik
    FROM game_scores
    JOIN users ON game_scores.id_user = users.id_user
    GROUP BY users.id_user, users.nama, users.kelas
    ORDER BY skor_terbaik DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Game</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Laporan Game Coding</h1>
                <p>Pantau skor permainan edukasi HTML, CSS, dan JavaScript siswa.</p>
            </div>
        </div>

        <section class="content-grid">
            <div>
                <div class="filter-card">
                    <form method="GET" class="filter-form">
                        <div class="form-group">
                            <label>Cari Siswa / Kelas</label>
                            <input type="text" name="keyword" class="form-control" value="<?= htmlspecialchars($keyword); ?>" placeholder="Contoh: X RPL">
                        </div>

                        <div class="form-group">
                            <label>Mode Game</label>
                            <select name="mode" class="form-control">
                                <option value="">Semua Mode</option>
                                <option value="HTML" <?= $mode == 'HTML' ? 'selected' : ''; ?>>HTML</option>
                                <option value="CSS" <?= $mode == 'CSS' ? 'selected' : ''; ?>>CSS</option>
                                <option value="JavaScript" <?= $mode == 'JavaScript' ? 'selected' : ''; ?>>JavaScript</option>
                                <option value="Campuran" <?= $mode == 'Campuran' ? 'selected' : ''; ?>>Campuran</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                        <a href="laporan_game.php" class="btn btn-outline">Reset</a>
                    </form>
                </div>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Mode</th>
                                <th>Skor</th>
                                <th>Benar</th>
                                <th>Total Soal</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$scores || mysqli_num_rows($scores) == 0): ?>
                                <tr><td colspan="7">Belum ada data skor game.</td></tr>
                            <?php endif; ?>

                            <?php if ($scores): ?>
                                <?php while($row = mysqli_fetch_assoc($scores)): ?>
                                    <tr>
                                        <td><?= $row['nama']; ?></td>
                                        <td><?= $row['kelas']; ?></td>
                                        <td><span class="badge badge-primary"><?= $row['mode_game']; ?></span></td>
                                        <td><span class="badge badge-success"><?= $row['skor']; ?></span></td>
                                        <td><?= $row['jawaban_benar']; ?></td>
                                        <td><?= $row['total_soal']; ?></td>
                                        <td><?= $row['tanggal_main']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <h3>Top Skor Siswa</h3>
                <p>Daftar siswa dengan skor game tertinggi.</p>
                <br>

                <?php if (!$top || mysqli_num_rows($top) == 0): ?>
                    <div class="empty-state">Belum ada skor game.</div>
                <?php endif; ?>

                <?php if ($top): ?>
                    <?php $no = 1; while($row = mysqli_fetch_assoc($top)): ?>
                        <div class="module-item" style="margin-bottom:12px;">
                            <div>
                                <span class="badge badge-warning">Rank <?= $no++; ?></span>
                                <h4 style="margin-top:10px;"><?= $row['nama']; ?></h4>
                                <p><?= $row['kelas'] ?: '-'; ?></p>
                            </div>
                            <strong style="font-size:24px;"><?= $row['skor_terbaik']; ?></strong>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>
</body>
</html>
