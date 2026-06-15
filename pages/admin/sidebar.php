<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<aside class="sidebar">
    <div class="logo">
        <div class="logo-icon">&lt;/&gt;</div>
        <span>HTML Learn RPL</span>
    </div>

    <div class="menu">
        <div class="menu-title">Menu Admin</div>
        <a class="<?= $currentPage == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard Admin</a>
        <a class="<?= in_array($currentPage, ['kelola_pengguna.php','edit_pengguna.php']) ? 'active' : ''; ?>" href="kelola_pengguna.php">Kelola Pengguna</a>

        <div class="menu-title">Bantuan</div>
        <a class="<?= $currentPage == 'panduan.php' ? 'active' : ''; ?>" href="panduan.php">Panduan</a>

        <div class="menu-title">Akun</div>
        <a class="<?= $currentPage == 'profil.php' ? 'active' : ''; ?>" href="profil.php">Profil</a>
        <a href="../../logout.php">Logout</a>
    </div>
</aside>
<div id="toast-root"></div>
<script src="../../assets/js/gooey-toast.js"></script>
