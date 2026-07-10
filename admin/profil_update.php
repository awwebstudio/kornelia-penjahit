<?php
include "../koneksi.php";
include "cek_login.php";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $nama_usaha = mysqli_real_escape_string($koneksi, $_POST['nama_usaha']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_wa = mysqli_real_escape_string($koneksi, $_POST['no_wa']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $maps_link = mysqli_real_escape_string($koneksi, $_POST['maps_link']);

    if($id) {
        $query = "UPDATE profil_usaha 
                  SET nama_usaha='$nama_usaha',
                      deskripsi='$deskripsi',
                      alamat='$alamat',
                      no_wa='$no_wa',
                      email='$email',
                      maps_link='$maps_link'
                  WHERE id_profil='$id'";
        mysqli_query($koneksi, $query);
    }

    // Redirect kembali ke halaman profil atau dashboard
    header("Location: profil.php");
    exit();
} else {
    // kalau bukan POST, kembali ke profil
    header("Location: profil.php");
    exit();
}
?>