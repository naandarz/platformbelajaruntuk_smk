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
        <div class="menu-title">Menu Guru</div>
        <a class="<?= $currentPage == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
        <a class="<?= in_array($currentPage, ['kelola_materi.php','edit_materi.php']) ? 'active' : ''; ?>" href="kelola_materi.php">Kelola Materi</a>
        <a class="<?= in_array($currentPage, ['kelola_kuis.php','edit_kuis.php']) ? 'active' : ''; ?>" href="kelola_kuis.php">Kelola Kuis</a>
        <a class="<?= $currentPage == 'import_soal_word.php' ? 'active' : ''; ?>" href="import_soal_word.php">Import Soal Word</a>
        <a class="<?= $currentPage == 'latihan_siswa.php' ? 'active' : ''; ?>" href="latihan_siswa.php">Latihan Siswa</a>
        <a class="<?= in_array($currentPage, ['kelola_game.php','edit_game_question.php']) ? 'active' : ''; ?>" href="kelola_game.php">Kelola Game</a>
        <a class="<?= $currentPage == 'laporan_game.php' ? 'active' : ''; ?>" href="laporan_game.php">Laporan Game</a>
        <a class="<?= $currentPage == 'ranking.php' ? 'active' : ''; ?>" href="ranking.php">Ranking Siswa</a>
        <a class="<?= in_array($currentPage, ['kelola_tugas.php','penilaian_tugas.php']) ? 'active' : ''; ?>" href="kelola_tugas.php">Kelola Tugas</a>
        <a class="<?= $currentPage == 'kelola_kelompok.php' ? 'active' : ''; ?>" href="kelola_kelompok.php">Kelola Kelompok</a>
        <a class="<?= in_array($currentPage, ['pesan.php']) ? 'active' : ''; ?>" href="pesan.php">Pesan & Bantuan</a>
        <a class="<?= in_array($currentPage, ['forum.php','forum_detail.php']) ? 'active' : ''; ?>" href="forum.php">Forum Diskusi</a>
        <a class="<?= in_array($currentPage, ['laporan.php','detail_siswa.php']) ? 'active' : ''; ?>" href="laporan.php">Laporan Nilai</a>
        <a class="<?= $currentPage == 'instrumen.php' ? 'active' : ''; ?>" href="instrumen.php">Instrumen</a>

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
