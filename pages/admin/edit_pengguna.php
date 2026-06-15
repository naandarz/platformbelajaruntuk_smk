<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('admin');

$id = intval($_GET['id'] ?? 0);
$q = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user=$id");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    die("Data pengguna tidak ditemukan.");
}

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $password_baru = $_POST['password_baru'];

    if ($password_baru != "") {
        $password = md5($password_baru);
        mysqli_query($koneksi, "UPDATE users SET nama='$nama', role='$role', kelas='$kelas', password='$password' WHERE id_user=$id");
    } else {
        mysqli_query($koneksi, "UPDATE users SET nama='$nama', role='$role', kelas='$kelas' WHERE id_user=$id");
    }

    if ($_SESSION['user']['id_user'] == $id) {
        $q = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user=$id");
        $_SESSION['user'] = mysqli_fetch_assoc($q);
    }

    header("Location: kelola_pengguna.php?status=diupdate");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pengguna</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Edit Pengguna</h1>
                <p>Perbarui data akun pengguna sistem.</p>
            </div>
            <a href="kelola_pengguna.php" class="btn btn-outline">Kembali</a>
        </div>

        <form method="POST" class="lesson-box">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']); ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($data['email']); ?>" disabled>
                <small class="form-help">Email tidak diubah agar tidak mengganggu data login.</small>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="siswa" <?= $data['role'] == 'siswa' ? 'selected' : ''; ?>>Siswa</option>
                    <option value="guru" <?= $data['role'] == 'guru' ? 'selected' : ''; ?>>Guru</option>
                    <option value="admin" <?= $data['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="kelas" class="form-control" value="<?= htmlspecialchars($data['kelas']); ?>" placeholder="Contoh: X RPL 1">
            </div>

            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password_baru" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti password">
            </div>

            <div class="page-actions">
                <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                <a href="kelola_pengguna.php" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </main>
</div>
</body>
</html>
