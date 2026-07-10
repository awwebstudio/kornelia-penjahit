<?php
include 'koneksi.php';

// Ambil semua data galeri
$galeri = mysqli_query(
    $koneksi,
    "SELECT * FROM galeri ORDER BY id_galeri DESC"
);

if (!$galeri) {
    die("Query Error: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Galeri Hasil Jahitan - Kornelia Jahit</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: "Segoe UI", Arial, sans-serif;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(236, 72, 153, .22),
                    transparent 30%
                ),
                linear-gradient(
                    135deg,
                    #0f172a,
                    #312e81,
                    #581c87
                );
            color: white;
        }

        .navbar {
            padding: 16px 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(15, 23, 42, .94);
            border-bottom: 1px solid rgba(255,255,255,.1);
            backdrop-filter: blur(15px);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
            font-weight: 800;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            font-size: 22px;
        }

        .back-link {
            padding: 10px 16px;
            border-radius: 10px;
            color: white;
            text-decoration: none;
            font-size: 13px;
            font-weight: 800;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
        }

        .back-link:hover {
            background: rgba(255,255,255,.18);
        }

        .hero {
            padding: 70px 7% 45px;
            text-align: center;
        }

        .hero span {
            display: inline-block;
            margin-bottom: 12px;
            padding: 7px 13px;
            border-radius: 999px;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            color: #f5d0fe;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .hero h1 {
            margin-bottom: 12px;
            font-size: clamp(34px, 5vw, 52px);
        }

        .hero p {
            max-width: 650px;
            margin: auto;
            color: #ddd6fe;
            line-height: 1.7;
        }

        .gallery-section {
            padding: 20px 7% 80px;
        }

        .galeri-container {
            max-width: 1180px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .galeri-item {
            overflow: hidden;
            position: relative;
            min-height: 340px;
            border-radius: 22px;
            background: #1e293b;
            box-shadow: 0 18px 45px rgba(0,0,0,.25);
            transition: .3s ease;
        }

        .galeri-item:hover {
            transform: translateY(-7px);
        }

        .galeri-item img {
            width: 100%;
            height: 340px;
            display: block;
            object-fit: cover;
            transition: .4s ease;
        }

        .galeri-item:hover img {
            transform: scale(1.06);
            opacity: .68;
        }

        .galeri-info {
            position: absolute;
            inset: auto 0 0 0;
            padding: 25px 20px;
            background: linear-gradient(
                transparent,
                rgba(15,23,42,.96)
            );
        }

        .galeri-info h3 {
            margin-bottom: 7px;
            font-size: 19px;
        }

        .galeri-info p {
            margin-bottom: 8px;
            color: #e2e8f0;
            font-size: 13px;
            line-height: 1.55;
        }

        .galeri-info small {
            color: #c4b5fd;
            font-size: 11px;
        }

        .no-image {
            width: 100%;
            height: 340px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            background: #312e81;
        }

        .empty-state {
            grid-column: 1 / -1;
            padding: 60px 25px;
            border-radius: 22px;
            text-align: center;
            background: rgba(255,255,255,.09);
            border: 1px solid rgba(255,255,255,.13);
        }

        .empty-state div {
            margin-bottom: 12px;
            font-size: 54px;
        }

        .empty-state h3 {
            margin-bottom: 7px;
        }

        .footer {
            padding: 28px 7%;
            text-align: center;
            color: #cbd5e1;
            background: #0f172a;
        }

        @media (max-width: 900px) {
            .galeri-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 620px) {
            .navbar {
                padding: 14px 18px;
            }

            .gallery-section {
                padding: 15px 18px 60px;
            }

            .hero {
                padding: 55px 18px 35px;
            }

            .galeri-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">

    <a href="index.php" class="brand">
        <div class="brand-logo">✂️</div>
        <span>Kornelia Jahit</span>
    </a>

    <a href="index.php" class="back-link">
        ← Kembali ke Beranda
    </a>

</nav>

<section class="hero">

    <span>HASIL KARYA</span>

    <h1>Galeri Hasil Jahitan</h1>

    <p>
        Lihat dokumentasi hasil jahitan dan karya yang telah dikerjakan
        oleh Kornelia Jahit.
    </p>

</section>

<section class="gallery-section">

    <div class="galeri-container">

        <?php if (mysqli_num_rows($galeri) > 0): ?>

            <?php while ($row = mysqli_fetch_assoc($galeri)): ?>

                <?php
                $fotoPath = 'img/' . $row['nama_file'];
                ?>

                <article class="galeri-item">

                    <?php if (
                        !empty($row['nama_file']) &&
                        file_exists($fotoPath)
                    ): ?>

                        <img
                            src="<?= htmlspecialchars($fotoPath); ?>"
                            alt="<?= htmlspecialchars($row['judul_foto']); ?>"
                        >

                    <?php else: ?>

                        <div class="no-image">🖼️</div>

                    <?php endif; ?>

                    <div class="galeri-info">

                        <h3>
                            <?= htmlspecialchars($row['judul_foto']); ?>
                        </h3>

                        <p>
                            <?= htmlspecialchars($row['deskripsi']); ?>
                        </p>

                        <?php if (!empty($row['created_at'])): ?>
                            <small>
                                Diunggah:
                                <?= htmlspecialchars($row['created_at']); ?>
                            </small>
                        <?php endif; ?>

                    </div>

                </article>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="empty-state">
                <div>🖼️</div>
                <h3>Belum ada foto galeri</h3>
                <p>Dokumentasi hasil jahitan akan tampil di sini.</p>
            </div>

        <?php endif; ?>

    </div>

</section>

<footer class="footer">
    © <?= date('Y'); ?> Kornelia Jahit — Galeri Hasil Jahitan
</footer>

</body>
</html>