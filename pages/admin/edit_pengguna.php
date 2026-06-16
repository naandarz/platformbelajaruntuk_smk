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
        $password_plain = mysqli_real_escape_string($koneksi, $password_baru);
        $password = md5($password_baru);
        mysqli_query($koneksi, "UPDATE users SET nama='$nama', role='$role', kelas='$kelas', password='$password', password_plain='$password_plain' WHERE id_user=$id");
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
                <label>Password Saat Ini / Terakhir Diset Admin</label>
                <div class="password-viewer">
                    <input type="password" id="current_password_view" class="form-control" value="<?= htmlspecialchars($data['password_plain'] ?: 'Belum tersimpan'); ?>" readonly>
                    <button type="button" class="eye-btn" onclick="togglePasswordField('current_password_view', this)">👁</button>
                </div>
                <small class="form-help">Jika belum tersimpan, masukkan password baru lalu simpan.</small>
            </div>

            <div class="form-group">
                <label>Password Baru</label>
                <div class="password-viewer">
                    <input type="password" name="password_baru" id="edit_password_baru" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti password">
                    <button type="button" class="eye-btn" onclick="togglePasswordField('edit_password_baru', this)">👁</button>
                </div>
                <div class="quick-passwords">
                    <button type="button" onclick="document.getElementById('edit_password_baru').value='123456'">123456</button>
                    <button type="button" onclick="document.getElementById('edit_password_baru').value='rpl12345'">rpl12345</button>
                    <button type="button" onclick="generateTempPassword('edit_password_baru')">Generate</button>
                </div>
            </div>

            <div class="page-actions">
                <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                <a href="kelola_pengguna.php" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </main>
</div>
<script src="../../assets/js/password-toggle.js"></script>
</body>
</html>
