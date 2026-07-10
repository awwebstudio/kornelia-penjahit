<?php
include "../koneksi.php";
include "cek_login.php";

$id = $_GET['id'] ?? '';
if($id){
    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM layanan WHERE id_layanan='$id'"));
    if($data && !empty($data['foto']) && file_exists("../img/".$data['foto'])){
        @unlink("../img/".$data['foto']);
    }
    mysqli_query($koneksi, "DELETE FROM layanan WHERE id_layanan='$id'");
}

header("Location: layanan.php");
exit();