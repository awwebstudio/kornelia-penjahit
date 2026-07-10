<?php
include "../koneksi.php";
session_start();

$username = mysqli_real_escape_string($koneksi, $_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username'");
if(mysqli_num_rows($query) > 0){
    $data = mysqli_fetch_assoc($query);
    if(password_verify($password, $data['password'])){
        session_regenerate_id(true); // aman dari session fixation
        $_SESSION['admin'] = $data['username'];
        $_SESSION['last_activity'] = time(); // untuk timeout
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Username atau password salah!";
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Username atau password salah!";
    header("Location: login.php");
    exit();
}