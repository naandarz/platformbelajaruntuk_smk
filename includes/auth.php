<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function wajib_login() {
    if (!isset($_SESSION['user'])) {
        header("Location: ../../login.php");
        exit;
    }
}

function hanya_role($role) {
    wajib_login();
    if ($_SESSION['user']['role'] !== $role) {
        header("Location: ../../login.php");
        exit;
    }
}
?>
