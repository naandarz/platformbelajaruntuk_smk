<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<aside class="sidebar">
    <div class="logo">
        <div class="logo-icon">&lt;/&gt;</div>
        <span>HTML Learn RPL</span>
    </div>

    <div class="menu">
        <div class="menu-title">Menu Siswa</div>
        <a class="<?= $currentPage == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
        <a class="<?= in_array($currentPage, ['materi.php','detail_materi.php']) ? 'active' : ''; ?>" href="materi.php">Materi Web</a>
        <a class="<?= $currentPage == 'live_coding.php' ? 'active' : ''; ?>" href="live_coding.php">Live Coding</a>
        <a class="<?= $currentPage == 'game.php' ? 'active' : ''; ?>" href="game.php">Game Coding</a>
        <a class="<?= $currentPage == 'riwayat.php' ? 'active' : ''; ?>" href="riwayat.php">Riwayat Belajar</a>
        <a class="<?= $currentPage == 'sertifikat.php' ? 'active' : ''; ?>" href="sertifikat.php">Sertifikat</a>

        <div class="menu-title">Bantuan</div>
        <a class="<?= $currentPage == 'panduan.php' ? 'active' : ''; ?>" href="panduan.php">Panduan</a>

        <div class="menu-title">Akun</div>
        <a class="<?= $currentPage == 'profil.php' ? 'active' : ''; ?>" href="profil.php">Profil</a>
        <a href="../../logout.php">Logout</a>
    </div>
</aside>
<div id="toast-root"></div>
<script src="../../assets/js/gooey-toast.js"></script>
