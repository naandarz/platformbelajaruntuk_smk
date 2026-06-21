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
        <div class="menu-title">Menu Admin</div>
        <a class="<?= $currentPage == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard Admin</a>
        <a class="<?= in_array($currentPage, ['kelola_pengguna.php','edit_pengguna.php','helpdesk.php','helpdesk_logs.php']) ? 'active' : ''; ?>" href="kelola_pengguna.php">Kelola Pengguna</a>
        <a class="<?= $currentPage == 'pesan.php' ? 'active' : ''; ?>" href="pesan.php">Pesan Bantuan</a>
        <a class="<?= $currentPage == 'ui_settings.php' ? 'active' : ''; ?>" href="ui_settings.php">Pengaturan UI</a>
        <a class="<?= $currentPage == 'jadwal.php' ? 'active' : ''; ?>" href="jadwal.php">Jadwal Kelas</a>
        <a class="<?= $currentPage == 'absensi.php' ? 'active' : ''; ?>" href="absensi.php">Rekap Absensi</a>
        <a class="<?= $currentPage == 'rekap_aktivitas.php' ? 'active' : ''; ?>" href="rekap_aktivitas.php">Rekap Aktivitas</a>

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
