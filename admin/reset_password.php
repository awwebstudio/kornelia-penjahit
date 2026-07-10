<?php
include "../koneksi.php";
session_start();
$msg = '';

$token = $_GET['token'] ?? '';
if(!$token){
    die("Token tidak valid!");
}

// Ambil admin berdasarkan token
$query = mysqli_query($koneksi, "SELECT id_admin FROM admin WHERE reset_token='$token'");
if(mysqli_num_rows($query) == 0){
    die("Token tidak valid atau sudah dipakai!");
}
$data = mysqli_fetch_assoc($query);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $password_baru = $_POST['password_baru'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

    if($password_baru !== $konfirmasi_password){
        $msg = "Password dan konfirmasi tidak cocok!";
    } else {
        $hash = password_hash($password_baru, PASSWORD_DEFAULT);
        mysqli_query($koneksi, "UPDATE admin SET password='$hash', reset_token=NULL WHERE id_admin='".$data['id_admin']."'");
        $msg = "Password berhasil diubah! <a href='login.php'>Login sekarang</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password Admin</title>
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
form {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.2);
    width: 350px;
}
form label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
}
form input {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 6px;
    border: 1px solid #ccc;
}
button {
    margin-top: 20px;
    width: 100%;
    padding: 10px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
}
button:hover { background: #0056b3; }
.msg {
    margin-top: 15px;
    font-weight: bold;
    color: green;
    text-align: center;
}
a.back {
    display: block;
    margin-top: 20px;
    text-align: center;
    text-decoration: none;
    color: #007bff;
}
a.back:hover { text-decoration: underline; }
</style>
</head>
<body>

<form method="POST" autocomplete="off">
    <h2 style="text-align:center;">Reset Password Admin</h2>

    <label>Password Baru:</label>
    <input type="password" name="password_baru" required>

    <label>Konfirmasi Password Baru:</label>
    <input type="password" name="konfirmasi_password" required>

    <button type="submit">Ubah Password</button>

    <?php if(!empty($msg)) echo "<p class='msg'>$msg</p>"; ?>

    <a href="login.php" class="back">← Kembali ke Login</a>
</form>

</body>
</html>