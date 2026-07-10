<?php
include 'koneksi.php';

// Ambil 3 layanan terbaru
$layanan = mysqli_query(
    $koneksi,
    "SELECT * FROM layanan ORDER BY id_layanan DESC LIMIT 3"
);

if (!$layanan) {
    die("Query Error (Layanan): " . mysqli_error($koneksi));
}

// Ambil profil usaha
$profil = mysqli_query(
    $koneksi,
    "SELECT * FROM profil_usaha LIMIT 1"
);

if (!$profil) {
    die("Query Error (Profil): " . mysqli_error($koneksi));
}

$data_profil = mysqli_fetch_assoc($profil) ?: [];

// Ambil 6 foto galeri terbaru
$galeri = mysqli_query(
    $koneksi,
    "SELECT * FROM galeri ORDER BY id_galeri DESC LIMIT 6"
);

if (!$galeri) {
    die("Query Error (Galeri): " . mysqli_error($koneksi));
}

$nama_usaha = $data_profil['nama_usaha'] ?? 'Kornelia Jahit';
$deskripsi = $data_profil['deskripsi']
    ?? 'Jasa jahit dan permak pakaian dengan hasil rapi dan harga terjangkau.';

$noWa = preg_replace(
    '/[^0-9]/',
    '',
    $data_profil['no_wa'] ?? ''
);

if (substr($noWa, 0, 1) === '0') {
    $noWa = '62' . substr($noWa, 1);
}

$pesanWa = urlencode(
    "Halo Kornelia Jahit, saya ingin bertanya mengenai layanan jahit."
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($nama_usaha); ?></title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    scroll-behavior: smooth;
}

body {
    font-family: "Segoe UI", Arial, sans-serif;
    background: #f8fafc;
    color: #1e293b;
    line-height: 1.6;
}

/* NAVBAR */

.navbar {
    position: sticky;
    top: 0;
    z-index: 50;
    padding: 16px 7%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(15, 23, 42, .94);
    border-bottom: 1px solid rgba(255,255,255,.09);
    backdrop-filter: blur(16px);
}

.brand {
    display: flex;
    align-items: center;
    gap: 12px;
    color: white;
    text-decoration: none;
}

.brand-logo {
    width: 45px;
    height: 45px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    font-size: 22px;
    box-shadow: 0 8px 20px rgba(0,0,0,.25);
}

.brand-text strong {
    display: block;
    font-size: 18px;
}

.brand-text span {
    display: block;
    color: #cbd5e1;
    font-size: 11px;
}

.nav-menu {
    display: flex;
    align-items: center;
    gap: 24px;
}

.nav-menu a {
    color: #e2e8f0;
    text-decoration: none;
    font-size: 14px;
    font-weight: 700;
    transition: .25s;
}

.nav-menu a:hover {
    color: #f0abfc;
}

.nav-contact {
    padding: 10px 16px;
    border-radius: 10px;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: white !important;
}

/* HERO */

.hero {
    min-height: 620px;
    padding: 80px 7%;
    display: flex;
    align-items: center;
    background:
        radial-gradient(circle at top right, rgba(236,72,153,.3), transparent 32%),
        radial-gradient(circle at bottom left, rgba(59,130,246,.28), transparent 35%),
        linear-gradient(135deg, #0f172a, #312e81, #581c87);
    color: white;
    overflow: hidden;
}

.hero-content {
    max-width: 720px;
}

.hero-badge {
    display: inline-block;
    padding: 8px 14px;
    margin-bottom: 20px;
    border-radius: 999px;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.15);
    color: #f5d0fe;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 1px;
}

.hero h1 {
    max-width: 700px;
    margin-bottom: 20px;
    font-size: clamp(42px, 6vw, 70px);
    line-height: 1.08;
}

.hero h1 span {
    background: linear-gradient(135deg, #c084fc, #f9a8d4);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
}

.hero p {
    max-width: 650px;
    margin-bottom: 30px;
    color: #e2e8f0;
    font-size: 18px;
    line-height: 1.8;
}

.hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
}

.btn {
    min-height: 50px;
    padding: 0 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border-radius: 13px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 800;
    transition: .25s;
}

.btn:hover {
    transform: translateY(-3px);
}

.btn-primary {
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: white;
    box-shadow: 0 14px 30px rgba(139,92,246,.3);
}

.btn-secondary {
    background: rgba(255,255,255,.1);
    color: white;
    border: 1px solid rgba(255,255,255,.2);
}

/* SECTION */

.section {
    padding: 80px 7%;
}

.section-light {
    background: #ffffff;
}

.section-soft {
    background: #f5f3ff;
}

.section-header {
    max-width: 680px;
    margin: 0 auto 42px;
    text-align: center;
}

.section-label {
    display: inline-block;
    margin-bottom: 10px;
    color: #7c3aed;
    font-size: 12px;
    font-weight: 900;
    letter-spacing: 1.2px;
}

.section-header h2 {
    margin-bottom: 12px;
    color: #1e1b4b;
    font-size: clamp(30px, 4vw, 42px);
}

.section-header p {
    color: #64748b;
}

/* LAYANAN */

.layanan-container {
    max-width: 1180px;
    margin: auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.layanan-item {
    overflow: hidden;
    border-radius: 22px;
    background: white;
    border: 1px solid #ede9fe;
    box-shadow: 0 15px 40px rgba(76,29,149,.09);
    transition: .3s;
}

.layanan-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 22px 45px rgba(76,29,149,.15);
}

.layanan-image {
    width: 100%;
    height: 240px;
    background: #ede9fe;
    overflow: hidden;
}

.layanan-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: .4s;
}

.layanan-item:hover img {
    transform: scale(1.05);
}

.layanan-content {
    padding: 23px;
}

.layanan-content h3 {
    margin-bottom: 10px;
    color: #312e81;
    font-size: 20px;
}

.layanan-content p {
    min-height: 52px;
    margin-bottom: 16px;
    color: #64748b;
    font-size: 14px;
}

.harga {
    color: #7c3aed !important;
    font-size: 19px !important;
    font-weight: 900;
}

/* GALERI */

.galeri-container {
    max-width: 1180px;
    margin: auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
}

.galeri-item {
    overflow: hidden;
    position: relative;
    min-height: 330px;
    border-radius: 20px;
    background: #1e293b;
    box-shadow: 0 16px 38px rgba(15,23,42,.16);
}

.galeri-item img {
    width: 100%;
    height: 330px;
    display: block;
    object-fit: cover;
    transition: .4s;
}

.galeri-item:hover img {
    transform: scale(1.07);
    opacity: .62;
}

.galeri-info {
    position: absolute;
    inset: auto 0 0 0;
    padding: 25px 20px;
    color: white;
    background: linear-gradient(transparent, rgba(15,23,42,.94));
}

.galeri-info h3 {
    margin-bottom: 5px;
    font-size: 18px;
}

.galeri-info p {
    color: #e2e8f0;
    font-size: 13px;
}

/* CTA */

.cta {
    margin: 0 7% 80px;
    padding: 50px;
    border-radius: 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 25px;
    color: white;
    background:
        radial-gradient(circle at right, rgba(255,255,255,.13), transparent 35%),
        linear-gradient(135deg, #4c1d95, #7c3aed, #db2777);
    box-shadow: 0 25px 60px rgba(76,29,149,.22);
}

.cta h2 {
    margin-bottom: 8px;
    font-size: 32px;
}

.cta p {
    color: #f3e8ff;
}

/* FOOTER */

.footer {
    padding: 32px 7%;
    background: #0f172a;
    color: #cbd5e1;
    text-align: center;
}

.footer strong {
    color: white;
}

/* RESPONSIVE */

@media (max-width: 900px) {
    .nav-menu a:not(.nav-contact) {
        display: none;
    }

    .layanan-container,
    .galeri-container {
        grid-template-columns: repeat(2, 1fr);
    }

    .cta {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 620px) {
    .navbar {
        padding: 14px 18px;
    }

    .brand-text span {
        display: none;
    }

    .hero {
        min-height: 560px;
        padding: 70px 20px;
    }

    .hero h1 {
        font-size: 42px;
    }

    .hero p {
        font-size: 16px;
    }

    .section {
        padding: 65px 18px;
    }

    .layanan-container,
    .galeri-container {
        grid-template-columns: 1fr;
    }

    .hero-actions,
    .hero-actions .btn {
        width: 100%;
    }

    .cta {
        margin: 0 18px 60px;
        padding: 32px 24px;
    }

    .cta .btn {
        width: 100%;
    }
}
</style>
</head>

<body>

<nav class="navbar">

    <a href="index.php" class="brand">
        <div class="brand-logo">✂️</div>

        <div class="brand-text">
            <strong>Kornelia Jahit</strong>
            <span>Jasa Jahit & Permak</span>
        </div>
    </a>

    <div class="nav-menu">
        <a href="#beranda">Beranda</a>
        <a href="#layanan">Layanan</a>
        <a href="#galeri">Galeri</a>
        <a href="lokasi.php">Lokasi</a>

        <a href="kontak.php" class="nav-contact">
            Hubungi Kami
        </a>
    </div>

</nav>

<header class="hero" id="beranda">

    <div class="hero-content">

        <span class="hero-badge">
            JASA JAHIT PROFESIONAL
        </span>

        <h1>
            Wujudkan Pakaian
            <span>Impian Anda</span>
        </h1>

        <p>
            <?= htmlspecialchars($deskripsi); ?>
            Kami melayani jahit kebaya, gaun pesta, pakaian anak,
            seragam, dan permak pakaian sesuai kebutuhan Anda.
        </p>

        <div class="hero-actions">

            <a href="#layanan" class="btn btn-primary">
                Lihat Layanan
            </a>

            <?php if ($noWa !== ''): ?>
                <a
                    href="https://wa.me/<?= htmlspecialchars($noWa); ?>?text=<?= $pesanWa; ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn btn-secondary"
                >
                    💬 Hubungi WhatsApp
                </a>
            <?php else: ?>
                <a href="kontak.php" class="btn btn-secondary">
                    Hubungi Kami
                </a>
            <?php endif; ?>

        </div>

    </div>

</header>

<section class="section section-light" id="layanan">

    <div class="section-header">

        <span class="section-label">LAYANAN PILIHAN</span>

        <h2>Layanan Jahit Kami</h2>

        <p>
            Layanan jahit berkualitas dengan pengerjaan rapi,
            detail, dan harga yang terjangkau.
        </p>

    </div>

    <div class="layanan-container">

        <?php while ($row = mysqli_fetch_assoc($layanan)): ?>

            <?php
            $fotoPath = 'img/' . $row['foto'];
            ?>

            <article class="layanan-item">

                <div class="layanan-image">

                    <?php if (
                        !empty($row['foto']) &&
                        file_exists($fotoPath)
                    ): ?>

                        <img
                            src="<?= htmlspecialchars($fotoPath); ?>"
                            alt="<?= htmlspecialchars($row['nama_layanan']); ?>"
                        >

                    <?php else: ?>

                        <div style="
                            width:100%;
                            height:100%;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            font-size:55px;
                        ">
                            ✂️
                        </div>

                    <?php endif; ?>

                </div>

                <div class="layanan-content">

                    <h3>
                        <?= htmlspecialchars($row['nama_layanan']); ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($row['deskripsi']); ?>
                    </p>

                    <p class="harga">
                        Rp<?= number_format(
                            (float) $row['harga'],
                            0,
                            ',',
                            '.'
                        ); ?>
                    </p>

                </div>

            </article>

        <?php endwhile; ?>

    </div>

    <div style="text-align:center;margin-top:35px;">
        <a href="layanan.php" class="btn btn-primary">
            Lihat Semua Layanan →
        </a>
    </div>

</section>

<section class="section section-soft" id="galeri">

    <div class="section-header">

        <span class="section-label">HASIL KARYA</span>

        <h2>Galeri Hasil Jahitan</h2>

        <p>
            Beberapa dokumentasi hasil jahitan yang telah dikerjakan
            oleh Kornelia Jahit.
        </p>

    </div>

    <div class="galeri-container">

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

                    <div style="
                        width:100%;
                        height:330px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:60px;
                    ">
                        🖼️
                    </div>

                <?php endif; ?>

                <div class="galeri-info">

                    <h3>
                        <?= htmlspecialchars($row['judul_foto']); ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($row['deskripsi']); ?>
                    </p>

                </div>

            </article>

        <?php endwhile; ?>

    </div>

    <div style="text-align:center;margin-top:35px;">
        <a href="galeri.php" class="btn btn-primary">
            Lihat Semua Galeri →
        </a>
    </div>

</section>

<section class="cta">

    <div>
        <h2>Siap Membuat Pakaian Impian?</h2>

        <p>
            Konsultasikan model, ukuran, dan kebutuhan jahit Anda
            bersama Kornelia Jahit.
        </p>
    </div>

    <?php if ($noWa !== ''): ?>

        <a
            href="https://wa.me/<?= htmlspecialchars($noWa); ?>?text=<?= $pesanWa; ?>"
            target="_blank"
            rel="noopener noreferrer"
            class="btn btn-secondary"
        >
            💬 Pesan via WhatsApp
        </a>

    <?php else: ?>

        <a href="kontak.php" class="btn btn-secondary">
            Hubungi Kami
        </a>

    <?php endif; ?>

</section>

<footer class="footer">

    <p>
        © <?= date('Y'); ?>
        <strong>Kornelia Jahit</strong>.
        Jasa jahit dan permak pakaian.
    </p>

</footer>

</body>
</html>