<?php
// Tahap 23: PWA head otomatis untuk root /platformbelajaruntuk_smk atau /lms_rpl.
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if (strpos($scriptDir, '/pages/') !== false) {
    $appBaseUrl = substr($scriptDir, 0, strpos($scriptDir, '/pages/'));
} else {
    $appBaseUrl = rtrim($scriptDir, '/');
}
if ($appBaseUrl === '') $appBaseUrl = '';
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#5b6ee1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="SmartLearn">
<meta name="mobile-web-app-capable" content="yes">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

<link rel="manifest" href="<?= $appBaseUrl; ?>/manifest.webmanifest">
<link rel="apple-touch-icon" href="<?= $appBaseUrl; ?>/assets/icons/icon-192.png">
<script>
if ("serviceWorker" in navigator) {
    window.addEventListener("load", function () {
        navigator.serviceWorker.register("<?= $appBaseUrl; ?>/service-worker.js").catch(function () {});
    });
}
</script>
