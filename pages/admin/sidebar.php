<?php
$currentPage = basename($_SERVER['PHP_SELF']);
include_once "../../includes/ui_settings.php";
?>
<aside class="sidebar">
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
<script src="../../assets/js/theme-toggle.js"></script>
