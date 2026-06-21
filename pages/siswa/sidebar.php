<?php
$currentPage = basename($_SERVER['PHP_SELF']);
include_once "../../includes/ui_settings.php";
?>
<button class="mobile-menu-toggle" type="button" onclick="toggleSmartLearnSidebar()" aria-label="Buka menu">☰</button>
<div class="mobile-sidebar-overlay" onclick="closeSmartLearnSidebar()"></div>
<aside class="sidebar">
    <button class="mobile-menu-close" type="button" onclick="closeSmartLearnSidebar()" aria-label="Tutup menu">×</button>
    <div class="logo">
        <div class="logo-icon"><?= htmlspecialchars($ui_logo_icon); ?></div>
        <span><?= htmlspecialchars($ui_app_name); ?></span>
    </div>

    <div class="menu">
        <div class="menu-title">Menu Siswa</div>
        <a class="<?= $currentPage == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
        <a class="<?= in_array($currentPage, ['materi.php','detail_materi.php']) ? 'active' : ''; ?>" href="materi.php">Materi Web</a>
        <a class="<?= $currentPage == 'live_coding.php' ? 'active' : ''; ?>" href="live_coding.php">Live Coding</a>
        <a class="<?= $currentPage == 'game.php' ? 'active' : ''; ?>" href="game.php">Game Coding</a>
        <a class="<?= $currentPage == 'ranking.php' ? 'active' : ''; ?>" href="ranking.php">Ranking</a>
        <a class="<?= $currentPage == 'tugas.php' ? 'active' : ''; ?>" href="tugas.php">Tugas</a>
        <a class="<?= $currentPage == 'kelompok.php' ? 'active' : ''; ?>" href="kelompok.php">Kelompok Saya</a>
        <a class="<?= $currentPage == 'absensi.php' ? 'active' : ''; ?>" href="absensi.php">Absensi</a>
        <a class="<?= $currentPage == 'jadwal.php' ? 'active' : ''; ?>" href="jadwal.php">Jadwal Kelas</a>
        <a class="<?= in_array($currentPage, ['pesan.php']) ? 'active' : ''; ?>" href="pesan.php">Pesan & Bantuan</a>
        <a class="<?= in_array($currentPage, ['forum.php','forum_detail.php']) ? 'active' : ''; ?>" href="forum.php">Forum Diskusi</a>
        <a class="<?= in_array($currentPage, ['riwayat.php','riwayat_semua.php']) ? 'active' : ''; ?>" href="riwayat.php">Riwayat Belajar</a>
        <a class="<?= $currentPage == 'sertifikat.php' ? 'active' : ''; ?>" href="sertifikat.php">Sertifikat</a>

        <div class="menu-title">Bantuan</div>
        <a class="<?= $currentPage == 'panduan.php' ? 'active' : ''; ?>" href="panduan.php">Panduan</a>

        <div class="menu-title">Akun</div>
        <a class="<?= $currentPage == 'profil.php' ? 'active' : ''; ?>" href="profil.php">Profil</a>
        <a href="../../logout.php">Logout</a>
    </div>
    <button class="theme-toggle-btn" onclick="toggleLmsTheme()">🌙 / ☀️ Tema</button>
</aside>
<div id="toast-root"></div>
<script src="../../assets/js/gooey-toast.js"></script>
<script src="../../assets/js/mobile-menu.js"></script>
<script src="../../assets/js/theme-toggle.js"></script>
