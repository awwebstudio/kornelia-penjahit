<?php
// File: admin/balas_email.php

include "../koneksi.php";
include "cek_login.php";

// Require manual PHPMailer
require '../vendor/PHPMailer-master/src/PHPMailer.php';
require '../vendor/PHPMailer-master/src/SMTP.php';
require '../vendor/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$id = $_GET['id'] ?? 0;
$id = (int)$id;

// Ambil data pesan
$result = mysqli_query($koneksi, "SELECT * FROM pesan WHERE id_pesan='$id'");
$pesan = mysqli_fetch_assoc($result);

if(!$pesan){
    die("Pesan tidak ditemukan!");
}

// Kirim email jika form disubmit
if(isset($_POST['kirim'])){
    $subject = $_POST['subject'] ?? '';
    $body = $_POST['pesan'] ?? '';

    if(!empty($subject) && !empty($body) && !empty($pesan['email'])){
        $mail = new PHPMailer(true);

        try {
            // Konfigurasi SMTP Gmail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'antoniusgratziaamawitak@gmail.com'; // ganti email kamu
            $mail->Password   = 'mrwpfnwtlakbqluh';   // ganti App Password Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('antoniusgratziaamawitak@gmail.com', 'Kornelia Penjahit');
            $mail->addAddress($pesan['email'], $pesan['nama']);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = nl2br(htmlspecialchars($body));

            $mail->send();

            // Update status pesan jadi dibaca
            mysqli_query($koneksi, "UPDATE pesan SET status='dibaca' WHERE id_pesan='$id'");

            echo "<script>alert('Email berhasil dikirim!');window.location='pesan.php';</script>";

        } catch (Exception $e) {
            echo "<p>Gagal kirim email: {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p>Subject, pesan, dan email penerima wajib diisi!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Balas Email ke <?= htmlspecialchars($pesan['nama']); ?></title>
<style>
body { font-family:'Segoe UI',sans-serif; background:#f4f4f4; padding:20px; }
.container { background:white; padding:30px; max-width:600px; margin:auto; border-radius:8px; box-shadow:0 3px 10px rgba(0,0,0,0.1); }
a.back { display:block; margin-bottom:20px; color:#007bff; text-decoration:none; font-weight:bold; }
a.back:hover { text-decoration:underline; }
input, textarea { width:100%; padding:10px; margin:10px 0; border-radius:6px; border:1px solid #ccc; }
button { padding:10px 15px; border:none; border-radius:6px; background:#007bff; color:white; font-weight:bold; cursor:pointer; }
button:hover { background:#0056b3; }
</style>
</head>
<body>

<div class="container">
    <a href="pesan_baca.php?id=<?= $pesan['id_pesan']; ?>" class="back">← Kembali ke Pesan</a>
    <h2>Balas Email ke <?= htmlspecialchars($pesan['nama']); ?></h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($pesan['email'] ?? '-'); ?></p>

    <form method="POST">
        <input type="text" name="subject" placeholder="Subject" required>
        <textarea name="pesan" rows="6" placeholder="Tulis balasan..." required></textarea>
        <button type="submit" name="kirim">Kirim Email</button>
    </form>
</div>

</body>
</html>