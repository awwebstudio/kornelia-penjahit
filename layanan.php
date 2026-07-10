<?php
include 'koneksi.php';

$layanan = mysqli_query($koneksi, "SELECT * FROM layanan ORDER BY id_layanan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Layanan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Semua Layanan Kami</h1>
</header>

<section>
    <div class="layanan-container">
        <?php while ($row = mysqli_fetch_assoc($layanan)) { ?>
            <div class="layanan-item">
                <h3><?= htmlspecialchars($row['nama_layanan']); ?></h3>

                <?php
                $foto_path = 'img/' . $row['foto'];
                if (!empty($row['foto']) && file_exists($foto_path)) {
                    echo '<img src="' . $foto_path . '" alt="">';
                }
                ?>

                <p><?= htmlspecialchars($row['deskripsi']); ?></p>
                <p class="harga">Rp <?= number_format((float)$row['harga'], 0, ',', '.'); ?></p>
            </div>
        <?php } ?>
    </div>
</section>

<nav class="nav-link">
    <a href="index.php">Kembali ke Beranda</a>
</nav>

</body>
</html>