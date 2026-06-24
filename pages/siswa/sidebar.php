<?php
$currentPage = basename($_SERVER['PHP_SELF']);
include_once "../../includes/ui_settings.php";
$roleLabel = "PRO MEMBER";
$avatarInitial = "S";
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
        
        <a class="syntactic-menu-item <?= active_menu(['dashboard.php']); ?>" href="dashboard.php"><span class="material-symbols-outlined">dashboard</span><span>Dashboard</span></a>
        <a class="syntactic-menu-item <?= active_menu(['materi.php','detail_materi.php']); ?>" href="materi.php"><span class="material-symbols-outlined">menu_book</span><span>Materi Web</span></a>
        <a class="syntactic-menu-item <?= active_menu(['live_coding.php']); ?>" href="live_coding.php"><span class="material-symbols-outlined">code</span><span>Live Coding</span></a>
        <a class="syntactic-menu-item <?= active_menu(['game.php']); ?>" href="game.php"><span class="material-symbols-outlined">stadia_controller</span><span>Game Coding</span></a>
        <a class="syntactic-menu-item <?= active_menu(['ranking.php']); ?>" href="ranking.php"><span class="material-symbols-outlined">leaderboard</span><span>Ranking</span></a>
        <a class="syntactic-menu-item <?= active_menu(['tugas.php']); ?>" href="tugas.php"><span class="material-symbols-outlined">assignment</span><span>Tugas</span></a>
        <a class="syntactic-menu-item <?= active_menu(['kelompok.php']); ?>" href="kelompok.php"><span class="material-symbols-outlined">groups</span><span>Kelompok Saya</span></a>
        <a class="syntactic-menu-item <?= active_menu(['absensi.php']); ?>" href="absensi.php"><span class="material-symbols-outlined">fact_check</span><span>Absensi</span></a>
        <a class="syntactic-menu-item <?= active_menu(['jadwal.php']); ?>" href="jadwal.php"><span class="material-symbols-outlined">calendar_month</span><span>Jadwal Kelas</span></a>
        <a class="syntactic-menu-item <?= active_menu(['pesan.php']); ?>" href="pesan.php"><span class="material-symbols-outlined">forum</span><span>Pesan & Bantuan</span></a>
        <a class="syntactic-menu-item <?= active_menu(['forum.php','forum_detail.php']); ?>" href="forum.php"><span class="material-symbols-outlined">chat</span><span>Forum Diskusi</span></a>
        <a class="syntactic-menu-item <?= active_menu(['riwayat.php','riwayat_semua.php']); ?>" href="riwayat.php"><span class="material-symbols-outlined">history</span><span>Riwayat Belajar</span></a>
        <a class="syntactic-menu-item <?= active_menu(['sertifikat.php']); ?>" href="sertifikat.php"><span class="material-symbols-outlined">workspace_premium</span><span>Sertifikat</span></a>
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
