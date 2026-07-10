<?php
include "../koneksi.php";
include "cek_login.php";

$id = $_GET['id'] ?? '';
if($id){
    mysqli_query($koneksi, "UPDATE pesan SET status='dibaca' WHERE id_pesan='$id'");
}
header("Location: pesan.php");
exit();
?>