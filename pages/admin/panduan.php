<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('admin');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panduan Admin</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Panduan Admin</h1>
                <p>Gunakan panduan ini untuk mengelola pengguna sistem.</p>
            </div>
        </div>

        <section class="guide-grid">
            <div class="guide-step">
                <div class="guide-number">1</div>
                <h3>Tambah Pengguna</h3>
                <p>Tambahkan akun siswa, guru, atau admin melalui menu Kelola Pengguna.</p>
            </div>

            <div class="guide-step">
                <div class="guide-number">2</div>
                <h3>Edit Pengguna</h3>
                <p>Ubah nama, role, kelas, atau password pengguna jika diperlukan.</p>
            </div>

            <div class="guide-step">
                <div class="guide-number">3</div>
                <h3>Hapus Pengguna</h3>
                <p>Hapus akun yang tidak digunakan. Akun yang sedang login tidak dapat dihapus untuk keamanan.</p>
            </div>

            <div class="guide-step">
                <div class="guide-number">4</div>
                <h3>Pantau Ringkasan</h3>
                <p>Dashboard admin menampilkan jumlah pengguna, siswa, guru, dan materi yang tersedia.</p>
            </div>

            <div class="guide-step">
                <div class="guide-number">5</div>
                <h3>Kelola Profil</h3>
                <p>Admin dapat memperbarui nama, kelas, dan password melalui halaman Profil.</p>
            </div>

            <div class="guide-step">
                <div class="guide-number">6</div>
                <h3>Jaga Data Login</h3>
                <p>Pastikan email pengguna tidak ganda agar proses login berjalan normal.</p>
            </div>
        </section>
    </main>
</div>
</body>
</html>
