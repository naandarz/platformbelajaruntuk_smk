<?php
session_start();
include "config/koneksi.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = md5($_POST['password']);

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1");
    $user = mysqli_fetch_assoc($query);

    if ($user) {
        $_SESSION['user'] = $user;

        if ($user['role'] === 'siswa') {
            header("Location: pages/siswa/dashboard.php?login=1");
        } elseif ($user['role'] === 'guru') {
            header("Location: pages/guru/dashboard.php?login=1");
        } elseif ($user['role'] === 'admin') {
            header("Location: pages/admin/dashboard.php?login=1");
        }
        exit;
    } else {
        $error = "Email atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HTML Learn RPL</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-card">
        <div class="logo">
            <div class="logo-icon">&lt;/&gt;</div>
            <span>HTML Learn RPL</span>
        </div>
        <h1>Masuk Akun</h1>
        <p>Silakan masuk menggunakan akun siswa, guru, atau admin.</p>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="email" name="email" placeholder="Masukkan email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input class="form-control" type="password" name="password" placeholder="Masukkan password" required>
            </div>

            <button class="btn btn-primary" style="width:100%;" type="submit">Login</button>
        </form>

        <p style="margin-top:18px; font-size:14px;">
            Akun demo:<br>
            Siswa: siswa@demo.com / 123456<br>
            Guru: guru@demo.com / 123456<br>
            Admin: admin@demo.com / 123456
        </p>
    </div>
</div>
<div id="toast-root"></div>
<script src="assets/js/gooey-toast.js"></script>
<?php if ($error): ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        showGooeyToast({type:"error", title:"Login Gagal", message:"Email atau password salah."});
    });
</script>
<?php endif; ?>
</body>
</html>
