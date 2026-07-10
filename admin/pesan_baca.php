<?php
// File: admin/pesan_baca.php

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

// Update status jadi dibaca
mysqli_query($koneksi, "UPDATE pesan SET status='dibaca' WHERE id_pesan='$id'");

// Kirim email jika form disubmit
$email_msg = '';
if(isset($_POST['kirim_email'])){
    $subject = $_POST['subject'] ?? '';
    $body = $_POST['body'] ?? '';

    if(!empty($subject) && !empty($body) && !empty($pesan['email'])){
        $mail = new PHPMailer(true);

        try {
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
            $email_msg = "<p style='color:green;'>Email berhasil dikirim!</p>";

        } catch (Exception $e) {
            $email_msg = "<p style='color:red;'>Gagal kirim email: {$mail->ErrorInfo}</p>";
        }
    } else {
        $email_msg = "<p style='color:red;'>Subject, pesan, dan email penerima wajib diisi!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Baca Pesan - <?= htmlspecialchars($pesan['nama']); ?></title>
<style>
body { font-family:'Segoe UI',sans-serif; background:#f4f4f4; padding:20px; }
.container { background:white; padding:30px; max-width:700px; margin:auto; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
a.back { display:block; margin-bottom:20px; color:#007bff; text-decoration:none; font-weight:bold; }
a.back:hover { text-decoration:underline; }
p { margin:10px 0; line-height:1.6; }
.button { display:inline-block; padding:8px 14px; border-radius:6px; text-decoration:none; font-weight:bold; margin-top:10px; margin-right:10px; transition:0.3s; }
.button-wa { background:#25D366; color:white; }
.button-wa:hover { background:#1ebe57; }
.button-hapus { background:#dc3545; color:white; }
.button-hapus:hover { background:#b52a37; }
.button-email { background:#007bff; color:white; }
.button-email:hover { background:#0056b3; }
.status { display:inline-block; padding:5px 10px; border-radius:20px; font-size:12px; font-weight:bold; background:#28a745; color:white; margin-bottom:15px; }
input, textarea { width:100%; padding:10px; margin:10px 0; border-radius:6px; border:1px solid #ccc; }
button { padding:10px 15px; border:none; border-radius:6px; background:#007bff; color:white; font-weight:bold; cursor:pointer; }
button:hover { background:#0056b3; }
</style>
</head>
<body>

<div class="container">
    <a href="pesan.php" class="back">← Kembali ke Daftar Pesan</a>

    <h2>Pesan dari <?= htmlspecialchars($pesan['nama']); ?></h2>
    <span class="status">Sudah Dibaca</span>

    <p><strong>No WA:</strong> <?= htmlspecialchars($pesan['no_hp']); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($pesan['email'] ?? '-'); ?></p>

    <hr>

    <p><strong>Isi Pesan:</strong></p>
    <p><?= nl2br(htmlspecialchars($pesan['isi_pesan'])); ?></p>

    <!-- Tombol Balas WA -->
    <a class="button button-wa" target="_blank"
       href="https://wa.me/<?= preg_replace('/[^0-9]/','',$pesan['no_hp']); ?>?text=Halo%20<?= urlencode($pesan['nama']); ?>,%20terima%20kasih%20atas%20pesannya.">
       Balas WA
    </a>

    <!-- Form Balas Email Inline -->
    <?php if(!empty($pesan['email'])): ?>
        <h3>Balas Email</h3>
        <?= $email_msg; ?>
        <form method="POST">
            <input type="text" name="subject" placeholder="Subject" required>
            <textarea name="body" rows="6" placeholder="Tulis balasan..." required></textarea>
            <button type="submit" name="kirim_email">Kirim Email</button>
        </form>
    <?php endif; ?>

    <!-- Tombol Hapus -->
    <a class="button button-hapus"
       href="pesan_hapus.php?id=<?= $pesan['id_pesan']; ?>"
       onclick="return confirm('Yakin ingin menghapus pesan ini?')">
       Hapus
    </a>
</div>

</body>
</html>