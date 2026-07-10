<?php
include "../koneksi.php";
include "cek_login.php"; // pastikan admin sudah login

$admin_id = $_SESSION['admin_id'] ?? '';
$msg = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $password_lama = $_POST['password_lama'] ?? '';
    $password_baru = $_POST['password_baru'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

    if($password_baru !== $konfirmasi_password){
        $msg = "Password baru dan konfirmasi tidak cocok!";
    } else {
        $query = mysqli_query($koneksi, "SELECT password FROM admin WHERE id_admin='$admin_id'");
        $data = mysqli_fetch_assoc($query);

        if(password_verify($password_lama, $data['password'])){
            $hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);
            mysqli_query($koneksi, "UPDATE admin SET password='$hash_baru' WHERE id_admin='$admin_id'");
            $msg = "Password berhasil diubah!";
        } else {
            $msg = "Password lama salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Ganti Password Admin</title>
</head>
<body>

<h2>Ganti Password Admin</h2>

<form method="POST">
    <label>Password Lama:</label>
    <input type="password" name="password_lama" required>

    <label>Password Baru:</label>
    <input type="password" name="password_baru" required>

    <label>Konfirmasi Password Baru:</label>
    <input type="password" name="konfirmasi_password" required>

    <button type="submit">Ubah Password</button>
</form>

<?php if(!empty($msg)) echo "<p>$msg</p>"; ?>

<a href="dashboard.php">← Kembali ke Dashboard</a>

</body>
</html>