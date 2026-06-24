<?php
$currentPage = basename($_SERVER['PHP_SELF']);
include_once "../../includes/ui_settings.php";
$roleLabel = "INSTRUKTUR UTAMA";
$avatarInitial = "G";
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
        <a class="syntactic-menu-item <?= active_menu(['kelola_materi.php','edit_materi.php']); ?>" href="kelola_materi.php"><span class="material-symbols-outlined">menu_book</span><span>Kelola Materi</span></a>
        <a class="syntactic-menu-item <?= active_menu(['kelola_kuis.php','edit_kuis.php']); ?>" href="kelola_kuis.php"><span class="material-symbols-outlined">quiz</span><span>Kelola Kuis</span></a>
        <a class="syntactic-menu-item <?= active_menu(['import_soal_word.php']); ?>" href="import_soal_word.php"><span class="material-symbols-outlined">upload_file</span><span>Import Soal Word</span></a>
        <a class="syntactic-menu-item <?= active_menu(['latihan_siswa.php']); ?>" href="latihan_siswa.php"><span class="material-symbols-outlined">school</span><span>Latihan Siswa</span></a>
        <a class="syntactic-menu-item <?= active_menu(['kelola_game.php','edit_game_question.php']); ?>" href="kelola_game.php"><span class="material-symbols-outlined">stadia_controller</span><span>Kelola Game</span></a>
        <a class="syntactic-menu-item <?= active_menu(['laporan_game.php']); ?>" href="laporan_game.php"><span class="material-symbols-outlined">insert_chart</span><span>Laporan Game</span></a>
        <a class="syntactic-menu-item <?= active_menu(['ranking.php']); ?>" href="ranking.php"><span class="material-symbols-outlined">leaderboard</span><span>Ranking Siswa</span></a>
        <a class="syntactic-menu-item <?= active_menu(['kelola_tugas.php','penilaian_tugas.php']); ?>" href="kelola_tugas.php"><span class="material-symbols-outlined">assignment</span><span>Kelola Tugas</span></a>
        <a class="syntactic-menu-item <?= active_menu(['kelola_kelompok.php']); ?>" href="kelola_kelompok.php"><span class="material-symbols-outlined">groups</span><span>Kelola Kelompok</span></a>
        <a class="syntactic-menu-item <?= active_menu(['absensi.php']); ?>" href="absensi.php"><span class="material-symbols-outlined">fact_check</span><span>Absensi Siswa</span></a>
        <a class="syntactic-menu-item <?= active_menu(['jadwal.php']); ?>" href="jadwal.php"><span class="material-symbols-outlined">calendar_month</span><span>Jadwal Kelas</span></a>
        <a class="syntactic-menu-item <?= active_menu(['rekap_aktivitas.php']); ?>" href="rekap_aktivitas.php"><span class="material-symbols-outlined">history</span><span>Rekap Aktivitas</span></a>
        <a class="syntactic-menu-item <?= active_menu(['forum.php','forum_detail.php']); ?>" href="forum.php"><span class="material-symbols-outlined">forum</span><span>Forum Diskusi</span></a>
        <a class="syntactic-menu-item <?= active_menu(['laporan.php','detail_siswa.php']); ?>" href="laporan.php"><span class="material-symbols-outlined">analytics</span><span>Laporan Nilai</span></a>
        <a class="syntactic-menu-item <?= active_menu(['instrumen.php']); ?>" href="instrumen.php"><span class="material-symbols-outlined">construction</span><span>Instrumen</span></a>
        <a class="syntactic-menu-item <?= active_menu(['pesan.php']); ?>" href="pesan.php"><span class="material-symbols-outlined">chat</span><span>Pesan & Bantuan</span></a>
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
