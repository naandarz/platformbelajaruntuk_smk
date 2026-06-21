<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('siswa');

$id_user = $_SESSION['user']['id_user'];
$pesan = "";
$error = "";

$q = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user=$id_user");
$data = mysqli_fetch_assoc($q);

if (isset($_POST['update_profil'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $password_baru = $_POST['password_baru'];

    if ($password_baru != "") {
        $password_plain = mysqli_real_escape_string($koneksi, $password_baru);
        $password = md5($password_baru);
        mysqli_query($koneksi, "UPDATE users SET nama='$nama', kelas='$kelas', password='$password', password_plain='$password_plain' WHERE id_user=$id_user");
    } else {
        mysqli_query($koneksi, "UPDATE users SET nama='$nama', kelas='$kelas' WHERE id_user=$id_user");
    }

    $q = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user=$id_user");
    $_SESSION['user'] = mysqli_fetch_assoc($q);
    $data = $_SESSION['user'];
    $pesan = "Profil berhasil diperbarui.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Profil Pengguna</h1>
                <p>Kelola informasi akun yang sedang digunakan.</p>
            </div>
        </div>

        <?php if ($pesan): ?>
            <div class="alert alert-success"><?= $pesan; ?></div>
        <?php endif; ?>

        <section class="detail-grid">
            <div class="card">
                <h3>Informasi Akun</h3>
                <br>
                <div class="info-list">
                    <div class="info-item">
                        <span>Nama</span>
                        <strong><?= $data['nama']; ?></strong>
                    </div>
                    <div class="info-item">
                        <span>Email</span>
                        <strong><?= $data['email']; ?></strong>
                    </div>
                    <div class="info-item">
                        <span>Role</span>
                        <strong><?= ucfirst($data['role']); ?></strong>
                    </div>
                    <div class="info-item">
                        <span>Kelas</span>
                        <strong><?= $data['kelas'] ?: '-'; ?></strong>
                    </div>
                </div>
            </div>

            <form method="POST" class="lesson-box">
                <h3>Edit Profil</h3>
                <br>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" value="<?= htmlspecialchars($data['email']); ?>" disabled>
                    <small class="form-help">Email tidak diubah pada versi ini agar akun tetap aman dan tidak bentrok dengan data login.</small>
                </div>

                <div class="form-group">
                    <label>Kelas</label>
                    <input type="text" name="kelas" class="form-control" value="<?= htmlspecialchars($data['kelas']); ?>" placeholder="Contoh: X RPL 1">
                </div>

                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password_baru" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti password">
                </div>

                <button type="submit" name="update_profil" class="btn btn-primary">Simpan Profil</button>
            </form>
        </section>
    </main>
</div>
</body>
</html>
