<?php
session_start();
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    if ($role === 'siswa') header("Location: pages/siswa/dashboard.php");
    if ($role === 'guru') header("Location: pages/guru/dashboard.php");
    if ($role === 'admin') header("Location: pages/admin/dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartLearn</title>
    <link rel="stylesheet" href="assets/css/style.css">
<?php include "includes/ui_settings.php"; ?>
<?php include "includes/pwa_head.php"; ?>
</head>
<body>
<div class="landing">
    <nav class="navbar">
        <div class="logo">
            <div class="logo-icon">&lt;/&gt;</div>
            <span>SmartLearn</span>
        </div>
        <a href="login.php" class="btn btn-primary">Masuk LMS</a>
    </nav>

    <section class="hero">
        <div>
            <span class="badge badge-primary">LMS Interaktif HTML • CSS • JavaScript</span>
            <h1>Belajar web dasar jadi lebih seru, visual, dan langsung praktik.</h1>
            <p>
                Platform pembelajaran web untuk siswa SMK RPL dengan materi bertahap, live coding,
                kuis otomatis, progress belajar, laporan guru, dan tampilan playful modern.
            </p>
            <a href="login.php" class="btn btn-primary">Mulai Belajar</a>
            <a href="#fitur" class="btn btn-outline">Lihat Fitur</a>
        </div>

        <div class="hero-card">
            <div class="code-window">
                <div class="code-line"><span class="code-tag">&lt;!DOCTYPE html&gt;</span></div>
                <div class="code-line"><span class="code-tag">&lt;html&gt;</span></div>
                <div class="code-line">&nbsp;&nbsp;<span class="code-tag">&lt;head&gt;</span></div>
                <div class="code-line">&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;style&gt;</span> body { background: lime; } <span class="code-tag">&lt;/style&gt;</span></div>
                <div class="code-line">&nbsp;&nbsp;<span class="code-tag">&lt;/head&gt;</span></div>
                <div class="code-line">&nbsp;&nbsp;<span class="code-tag">&lt;body&gt;</span></div>
                <div class="code-line">&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;h1&gt;</span><span class="code-text">Halo Siswa RPL!</span><span class="code-tag">&lt;/h1&gt;</span></div>
                <div class="code-line">&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-tag">&lt;button onclick="alert('Semangat!')"&gt;</span><span class="code-text">Klik</span><span class="code-tag">&lt;/button&gt;</span></div>
                <div class="code-line">&nbsp;&nbsp;<span class="code-tag">&lt;/body&gt;</span></div>
                <div class="code-line"><span class="code-tag">&lt;/html&gt;</span></div>
            </div>
        </div>
    </section>

    <section class="feature-grid" id="fitur">
        <div class="feature-card">
            <span class="badge badge-primary">HTML</span>
            <h3>Struktur Web</h3>
            <p>Belajar struktur dasar halaman web, heading, paragraf, link, gambar, tabel, dan form.</p>
        </div>
        <div class="feature-card">
            <span class="badge badge-success">CSS</span>
            <h3>Tampilan Menarik</h3>
            <p>Pelajari selector, warna, box model, layout, dan style agar halaman web lebih rapi.</p>
        </div>
        <div class="feature-card">
            <span class="badge badge-warning">JS</span>
            <h3>Interaksi Dinamis</h3>
            <p>Buat halaman web lebih hidup dengan event, DOM, tombol interaktif, dan manipulasi teks.</p>
        </div>
    </section>
</div>
<div id="toast-root"></div>
<script src="assets/js/gooey-toast.js"></script>
</body>
</html>
