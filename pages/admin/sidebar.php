<?php
$currentPage = basename($_SERVER['PHP_SELF']);
include_once "../../includes/ui_settings.php";
$roleLabel = "ADMIN SYSTEM";
$avatarInitial = "A";
$avatarName = $_SESSION['user']['nama'] ?? "SmartLearn User";
function active_menu($pages) {
    global $currentPage;
    return in_array($currentPage, (array)$pages) ? 'active' : '';
}
?>
<button class="mobile-menu-toggle" type="button" onclick="toggleSmartLearnSidebar()" aria-label="Buka menu">☰</button>
<div class="mobile-sidebar-overlay" onclick="closeSmartLearnSidebar()"></div>

<aside class="sidebar syntactic-sidebar">
    <button class="mobile-menu-close" type="button" onclick="closeSmartLearnSidebar()" aria-label="Tutup menu">×</button>

    <div class="syntactic-brand">
        <div class="syntactic-logo">
            <span class="material-symbols-outlined">terminal</span>
        </div>
        <div>
            <strong><?= htmlspecialchars($ui_app_name); ?></strong>
            <span>LMS Platform</span>
        </div>
    </div>

    <nav class="syntactic-menu">
        
        <a class="syntactic-menu-item <?= active_menu(['dashboard.php']); ?>" href="dashboard.php"><span class="material-symbols-outlined">dashboard</span><span>Dashboard Admin</span></a>
        <a class="syntactic-menu-item <?= active_menu(['kelola_pengguna.php','edit_pengguna.php','helpdesk.php','helpdesk_logs.php']); ?>" href="kelola_pengguna.php"><span class="material-symbols-outlined">manage_accounts</span><span>Kelola Pengguna</span></a>
        <a class="syntactic-menu-item <?= active_menu(['pesan.php']); ?>" href="pesan.php"><span class="material-symbols-outlined">mail</span><span>Pesan Bantuan</span></a>
        <a class="syntactic-menu-item <?= active_menu(['ui_settings.php']); ?>" href="ui_settings.php"><span class="material-symbols-outlined">tune</span><span>Pengaturan UI</span></a>
        <a class="syntactic-menu-item <?= active_menu(['jadwal.php']); ?>" href="jadwal.php"><span class="material-symbols-outlined">calendar_month</span><span>Jadwal Kelas</span></a>
        <a class="syntactic-menu-item <?= active_menu(['absensi.php']); ?>" href="absensi.php"><span class="material-symbols-outlined">fact_check</span><span>Rekap Absensi</span></a>
        <a class="syntactic-menu-item <?= active_menu(['rekap_aktivitas.php']); ?>" href="rekap_aktivitas.php"><span class="material-symbols-outlined">history</span><span>Rekap Aktivitas</span></a>
        <a class="syntactic-menu-item <?= active_menu(['profil.php']); ?>" href="profil.php"><span class="material-symbols-outlined">account_circle</span><span>Profil</span></a>

    </nav>

    <div class="syntactic-sidebar-bottom">
        <a class="syntactic-menu-item <?= active_menu(['panduan.php']); ?>" href="panduan.php">
            <span class="material-symbols-outlined">help</span>
            <span>Help Center</span>
        </a>
        <a class="syntactic-menu-item" href="../../logout.php">
            <span class="material-symbols-outlined">logout</span>
            <span>Sign Out</span>
        </a>
    </div>
</aside>
<div id="toast-root"></div>
<script src="../../assets/js/gooey-toast.js"></script>
<script src="../../assets/js/theme-toggle.js"></script>
<script src="../../assets/js/mobile-menu.js"></script>
