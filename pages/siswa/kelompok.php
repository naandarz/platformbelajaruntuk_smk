<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('siswa');

$id_user = $_SESSION['user']['id_user'];

$kelompok = mysqli_query($koneksi, "
    SELECT kelompok.*, kelompok_anggota.peran
    FROM kelompok_anggota
    JOIN kelompok ON kelompok_anggota.id_kelompok = kelompok.id_kelompok
    WHERE kelompok_anggota.id_user=$id_user
    ORDER BY kelompok.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelompok Saya</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Kelompok Saya</h1>
                <p>Lihat kelompok belajar, anggota, dan tugas kelompok yang diberikan guru.</p>
            </div>
            <a href="tugas.php" class="btn btn-outline">Lihat Tugas</a>
        </div>

        <?php if (!$kelompok || mysqli_num_rows($kelompok) == 0): ?>
            <div class="student-clean-card">
                <div class="empty-state">Kamu belum masuk ke kelompok mana pun.</div>
            </div>
        <?php endif; ?>

        <section class="student-page-grid">
            <div>
                <?php if ($kelompok): ?>
                    <?php while($k = mysqli_fetch_assoc($kelompok)): ?>
                        <div class="group-card">
                            <span class="badge badge-primary">Peran: <?= htmlspecialchars($k['peran']); ?></span>
                            <h3 style="margin-top:12px;"><?= htmlspecialchars($k['nama_kelompok']); ?></h3>
                            <p><?= nl2br(htmlspecialchars($k['deskripsi'] ?: 'Belum ada deskripsi.')); ?></p>

                            <h4 style="margin-top:16px;">Anggota Kelompok</h4>
                            <div class="group-member-list">
                                <?php
                                $id_kelompok = intval($k['id_kelompok']);
                                $anggota = mysqli_query($koneksi, "
                                    SELECT kelompok_anggota.*, users.nama, users.kelas
                                    FROM kelompok_anggota
                                    JOIN users ON kelompok_anggota.id_user = users.id_user
                                    WHERE kelompok_anggota.id_kelompok=$id_kelompok
                                    ORDER BY kelompok_anggota.peran DESC, users.nama ASC
                                ");
                                while($a = mysqli_fetch_assoc($anggota)):
                                ?>
                                    <div class="group-member">
                                        <div>
                                            <strong><?= htmlspecialchars($a['nama']); ?></strong><br>
                                            <span class="small-muted"><?= htmlspecialchars($a['kelas'] ?: '-'); ?></span>
                                        </div>
                                        <span class="badge <?= $a['peran'] == 'ketua' ? 'badge-warning' : 'badge-neutral'; ?>"><?= htmlspecialchars($a['peran']); ?></span>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

            <aside class="student-clean-card">
                <h3>Info Tugas Kelompok</h3>
                <p class="small-muted">Jika guru membuat tugas kelompok, salah satu anggota dapat mengumpulkan tugas mewakili kelompok.</p>
                <br>
                <a href="tugas.php" class="btn btn-primary">Buka Tugas</a>
            </aside>
        </section>
    </main>
</div>
</body>
</html>
