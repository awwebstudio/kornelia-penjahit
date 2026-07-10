<?php
include "../koneksi.php"; // koneksi dari root
session_start();
$msg = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'] ?? '';

    // Cek username di database
    $query = mysqli_query($koneksi, "SELECT id_admin FROM admin WHERE username='$username'");
    if(mysqli_num_rows($query) > 0){
        $data = mysqli_fetch_assoc($query);

        // Buat token unik
        $token = bin2hex(random_bytes(16));

        // Simpan token ke database
        $result = mysqli_query($koneksi, "UPDATE admin SET reset_token='$token' WHERE id_admin='".$data['id_admin']."'");
        if(!$result){
            die("Gagal simpan token: " . mysqli_error($koneksi));
        }

        // Link reset password (sesuaikan nama folder)
        $reset_link = "http://localhost/kornelia_penjahit/admin/reset_password.php?token=$token";

        // Tampilkan link untuk testing
        $msg = "Klik link ini untuk reset password: <a href='$reset_link'>$reset_link</a>";
    } else {
        $msg = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lupa Password Admin</title>
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

<form method="POST">
    <h2 style="text-align:center;">Lupa Password Admin</h2>

    <label>Username:</label>
    <input type="text" name="username" required>

    <button type="submit">Kirim Link Reset</button>

    <?php if(!empty($msg)) echo "<p class='msg'>$msg</p>"; ?>

    <a href="login.php" class="back">← Kembali ke Login</a>
</form>

</body>
</html>