<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('admin');

$pesan = "";
$id_login = $_SESSION['user']['id_user'];

if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = md5($_POST['password']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);

    mysqli_query($koneksi, "INSERT INTO users (nama, email, password, role, kelas)
    VALUES ('$nama', '$email', '$password', '$role', '$kelas')");
    header("Location: kelola_pengguna.php?status=ditambah");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);

    if ($id == $id_login) {
        header("Location: kelola_pengguna.php?status=gagalhapus");
        exit;
    }

    mysqli_query($koneksi, "DELETE FROM users WHERE id_user=$id");
    header("Location: kelola_pengguna.php?status=dihapus");
    exit;
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'ditambah') $pesan = "Pengguna berhasil ditambahkan.";
    if ($_GET['status'] == 'diupdate') $pesan = "Data pengguna berhasil diperbarui.";
    if ($_GET['status'] == 'dihapus') $pesan = "Pengguna berhasil dihapus.";
    if ($_GET['status'] == 'gagalhapus') $pesan = "Akun yang sedang digunakan tidak boleh dihapus.";
}

$users = mysqli_query($koneksi, "SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengguna</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Kelola Pengguna</h1>
                <p>Tambah, edit, dan hapus akun siswa, guru, atau admin.</p>
            </div>
        </div>

        <?php if ($pesan): ?>
            <div class="alert <?= $_GET['status'] == 'gagalhapus' ? 'alert-warning' : 'alert-success'; ?>"><?= $pesan; ?></div>
        <?php endif; ?>

        <section class="content-grid">
            <form method="POST" class="card">
                <h3>Tambah Pengguna</h3>
                <br>
                <div class="form-group"><label>Nama</label><input type="text" name="nama" class="form-control" required></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control" required>
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group"><label>Kelas</label><input type="text" name="kelas" class="form-control" placeholder="Contoh: X RPL 1"></div>
                <button type="submit" name="tambah" class="btn btn-primary">Simpan Pengguna</button>
            </form>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($users)): ?>
                            <tr>
                                <td><?= $row['nama']; ?></td>
                                <td><?= $row['email']; ?></td>
                                <td><span class="badge user-role-<?= $row['role']; ?>"><?= $row['role']; ?></span></td>
                                <td><?= $row['kelas']; ?></td>
                                <td>
                                    <div class="action-row">
                                        <a href="edit_pengguna.php?id=<?= $row['id_user']; ?>" class="btn btn-outline btn-sm">Edit</a>
                                        <a href="?hapus=<?= $row['id_user']; ?>" onclick="return confirm('Hapus pengguna ini?')" class="btn btn-danger btn-sm">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
</body>
</html>
