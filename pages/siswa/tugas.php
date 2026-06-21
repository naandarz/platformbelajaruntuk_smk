<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('siswa');

$id_user = $_SESSION['user']['id_user'];
$pesan = "";
$error = "";

function notifyRoleGuru($koneksi, $judul, $pesan, $tipe='info', $link=null) {
    $users = mysqli_query($koneksi, "SELECT id_user FROM users WHERE role='guru'");
    while($u = mysqli_fetch_assoc($users)) {
        $id = intval($u['id_user']);
        $j = mysqli_real_escape_string($koneksi, $judul);
        $p = mysqli_real_escape_string($koneksi, $pesan);
        $t = mysqli_real_escape_string($koneksi, $tipe);
        $l = $link ? "'" . mysqli_real_escape_string($koneksi, $link) . "'" : "NULL";
        mysqli_query($koneksi, "INSERT INTO notifikasi (id_user, judul, pesan, tipe, link) VALUES ($id, '$j', '$p', '$t', $l)");
    }
}

if (isset($_POST['upload_tugas'])) {
    $id_tugas = intval($_POST['id_tugas']);
    $catatan = mysqli_real_escape_string($koneksi, $_POST['catatan'] ?? '');
    $file_path_db = "";

    if (isset($_FILES['file_tugas']) && $_FILES['file_tugas']['error'] == UPLOAD_ERR_OK) {
        $allowed = ['zip','rar','docx','pdf','txt','html','css','js'];
        $original = $_FILES['file_tugas']['name'];
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $error = "Format file tidak didukung. Gunakan zip, rar, docx, pdf, txt, html, css, atau js.";
        } else {
            $upload_dir = "../../uploads/tugas/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $safe_name = "tugas_" . $id_user . "_" . $id_tugas . "_" . time() . "." . $ext;
            $target = $upload_dir . $safe_name;

            if (move_uploaded_file($_FILES['file_tugas']['tmp_name'], $target)) {
                $file_path_db = "uploads/tugas/" . $safe_name;
            } else {
                $error = "File gagal diupload.";
            }
        }
    } else {
        $error = "Pilih file tugas terlebih dahulu.";
    }

    if (!$error) {
        $tugas_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tugas WHERE id_tugas=$id_tugas"));
        $id_kelompok_sql = "NULL";
        if ($tugas_data && ($tugas_data['tipe_tugas'] ?? 'individu') == 'kelompok') {
            $id_kelompok_sql = intval($tugas_data['id_kelompok'] ?? 0) > 0 ? intval($tugas_data['id_kelompok']) : "NULL";
        }

        mysqli_query($koneksi, "INSERT INTO pengumpulan_tugas (id_tugas, id_user, id_kelompok, file_tugas, catatan)
        VALUES ($id_tugas, $id_user, $id_kelompok_sql, '$file_path_db', '$catatan')");

        notifyRoleGuru($koneksi, "Tugas Dikumpulkan", $_SESSION['user']['nama'] . " mengumpulkan tugas baru.", "info", "../guru/kelola_tugas.php");

        header("Location: tugas.php?saved=1");
        exit;
    }
}

$tugas = mysqli_query($koneksi, "
    SELECT DISTINCT tugas.*, materi.judul_materi, kelompok.nama_kelompok
    FROM tugas
    LEFT JOIN materi ON tugas.id_materi = materi.id_materi
    LEFT JOIN kelompok ON tugas.id_kelompok = kelompok.id_kelompok
    LEFT JOIN kelompok_anggota ON tugas.id_kelompok = kelompok_anggota.id_kelompok
    WHERE (tugas.tipe_tugas IS NULL OR tugas.tipe_tugas='individu')
       OR (tugas.tipe_tugas='kelompok' AND (tugas.id_kelompok IS NULL OR kelompok_anggota.id_user=$id_user))
    ORDER BY tugas.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas Siswa</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Tugas Siswa</h1>
                <p>Lihat tugas dari guru, upload hasil pekerjaan, dan pantau nilai tugas.</p>
            </div>
        </div>

        <?php if (isset($_GET['saved'])): ?>
            <div class="alert alert-success">Tugas berhasil dikumpulkan.</div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <div class="module-list">
            <?php if (!$tugas || mysqli_num_rows($tugas) == 0): ?>
                <div class="empty-state">Belum ada tugas dari guru.</div>
            <?php endif; ?>

            <?php if ($tugas): ?>
                <?php while($row = mysqli_fetch_assoc($tugas)): ?>
                    <?php
                    $id_tugas = $row['id_tugas'];
                    $last = mysqli_fetch_assoc(mysqli_query($koneksi, "
                        SELECT * FROM pengumpulan_tugas
                        WHERE id_tugas=$id_tugas AND id_user=$id_user
                        ORDER BY tanggal_upload DESC LIMIT 1
                    "));
                    ?>
                    <div class="student-clean-card">
                        <span class="badge badge-primary"><?= $row['judul_materi'] ?: 'Umum'; ?></span>
                        <span class="task-type-pill <?= ($row['tipe_tugas'] ?? 'individu') == 'kelompok' ? 'group' : ''; ?>">
                            <?= ($row['tipe_tugas'] ?? 'individu') == 'kelompok' ? '👥 Kelompok' : '👤 Individu'; ?>
                        </span>
                        <?php if (($row['tipe_tugas'] ?? 'individu') == 'kelompok' && !empty($row['nama_kelompok'])): ?>
                            <span class="badge badge-success"><?= htmlspecialchars($row['nama_kelompok']); ?></span>
                        <?php endif; ?>
                        <h3 style="margin-top:12px;"><?= htmlspecialchars($row['judul_tugas']); ?></h3>
                        <p><?= nl2br(htmlspecialchars($row['deskripsi'])); ?></p>

                        <div class="task-status">
                            <span class="badge badge-warning">Deadline: <?= $row['batas_waktu'] ?: '-'; ?></span>
                            <?php if ($last): ?>
                                <span class="badge badge-success">Sudah Mengumpulkan</span>
                                <?php if ($last['nilai'] !== null): ?>
                                    <span class="badge badge-primary">Nilai: <?= $last['nilai']; ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="badge badge-warning">Belum Mengumpulkan</span>
                            <?php endif; ?>
                        </div>

                        <?php if ($last): ?>
                            <div class="notice-box">
                                <strong>Pengumpulan terakhir:</strong> <?= $last['tanggal_upload']; ?><br>
                                <strong>Catatan:</strong> <?= htmlspecialchars($last['catatan'] ?: '-'); ?><br>
                                <?php if ($last['file_tugas']): ?>
                                    <a href="../../<?= htmlspecialchars($last['file_tugas']); ?>" target="_blank">Lihat file terkumpul</a><br>
                                <?php endif; ?>
                                <?php if ($last['feedback']): ?>
                                    <strong>Feedback Guru:</strong> <?= htmlspecialchars($last['feedback']); ?>
                                <?php endif; ?>
                            </div>
                            <br>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data" class="no-print">
                            <input type="hidden" name="id_tugas" value="<?= $row['id_tugas']; ?>">
                            <div class="form-group">
                                <label>Upload File Tugas</label>
                                <input type="file" name="file_tugas" class="form-control" accept=".zip,.rar,.docx,.pdf,.txt,.html,.css,.js" required>
                                <small class="form-help">Format: zip, rar, docx, pdf, txt, html, css, js.</small>
                            </div>
                            <div class="form-group">
                                <label>Catatan</label>
                                <textarea name="catatan" class="form-control" rows="3" placeholder="Tulis catatan singkat untuk guru..."></textarea>
                            </div>
                            <button type="submit" name="upload_tugas" class="btn btn-primary"><?= $last ? 'Upload Ulang Tugas' : 'Kumpulkan Tugas'; ?></button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>
