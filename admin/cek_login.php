<?php
session_start();

// Kalau session admin tidak ada, redirect ke login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Optional: session timeout (misal 30 menit)
$timeout = 1800; // 1800 detik = 30 menit
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header("Location: login.php?message=Session+expired");
    exit();
}

// Update waktu terakhir aktivitas
$_SESSION['last_activity'] = time();
?>