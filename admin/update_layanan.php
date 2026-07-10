<?php
include "../koneksi.php";
include "cek_login.php";

$id = $_POST['id'] ?? '';
$nama_layanan = $_POST['nama_layanan'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';
$harga = $_POST['harga'] ?? '';

if(!$id){
    header("Location: layanan.php");
    exit();
}

// Ambil data lama untuk foto
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM layanan WHERE id_layanan='$id'"));
$foto = $data['foto'];

// Handle upload foto baru
if(isset($_FILES['foto']) && $_FILES['foto']['name'] != ''){
    $file_tmp = $_FILES['foto']['tmp_name'];
    $file_ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto = time().'_'.rand(1000,9999).'.'.$file_ext;
    move_uploaded_file($file_tmp, "../img/".$foto);

    // Hapus file lama
    if(!empty($data['foto']) && file_exists("../img/".$data['foto'])){
        @unlink("../img/".$data['foto']);
    }
}

// Update database
mysqli_query($koneksi, "UPDATE layanan SET nama_layanan='$nama_layanan', deskripsi='$deskripsi', harga='$harga', foto='$foto' WHERE id_layanan='$id'");

header("Location: layanan.php");
exit();