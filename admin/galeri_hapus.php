<?php
include "../koneksi.php";
include "cek_login.php"; // pastikan admin sudah login

$id = $_GET['id'] ?? '';

if($id){
    $query = mysqli_query($koneksi, "SELECT * FROM galeri WHERE id_galeri='$id'");
    $data = mysqli_fetch_assoc($query);

    if($data && !empty($data['nama_file'])){
        $filePath = "../img/" . $data['nama_file'];
        if(file_exists($filePath)){
            unlink($filePath); // hapus file gambar
        }
    }

    mysqli_query($koneksi, "DELETE FROM galeri WHERE id_galeri='$id'");
}

header("Location: galeri.php");
exit();