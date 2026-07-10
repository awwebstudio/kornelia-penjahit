<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM profil_usaha LIMIT 1"
);

if (!$query) {
    die("Query Error: " . mysqli_error($koneksi));
}

if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);
} else {
    die("Data profil usaha belum tersedia.");
}

$namaUsaha = $data['nama_usaha'] ?? 'Kornelia Jahit';
$alamat = $data['alamat'] ?? 'Alamat belum tersedia';
$mapsLink = trim($data['maps_link'] ?? '');

$noWa = preg_replace('/[^0-9]/', '', $data['no_wa'] ?? '');

if (substr($noWa, 0, 1) === '0') {
    $noWa = '62' . substr($noWa, 1);
}

$pesanWa = urlencode(
    "Halo Kornelia Jahit, saya ingin bertanya mengenai lokasi usaha."
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Lokasi - <?= htmlspecialchars($namaUsaha); ?></title>

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
            rgba(236,72,153,.25),
            transparent 30%
        ),
        radial-gradient(
            circle at bottom left,
            rgba(59,130,246,.25),
            transparent 35%
        ),
        linear-gradient(
            135deg,
            #0f172a,
            #312e81,
            #581c87
        );
    color: white;
}

/* NAVBAR */

.navbar {
    width: 100%;
    padding: 16px 7%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(15,23,42,.94);
    border-bottom: 1px solid rgba(255,255,255,.1);
    backdrop-filter: blur(16px);
    position: sticky;
    top: 0;
    z-index: 20;
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
    font-size: 11px;
    color: #cbd5e1;
}

.back-link {
    padding: 10px 16px;
    border-radius: 11px;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.15);
    color: white;
    text-decoration: none;
    font-size: 13px;
    font-weight: 800;
    transition: .25s;
}

.back-link:hover {
    background: rgba(255,255,255,.18);
    transform: translateY(-2px);
}

/* MAIN */

.main-container {
    width: 100%;
    max-width: 1180px;
    margin: auto;
    padding: 65px 24px 80px;
}

.page-header {
    max-width: 750px;
    margin: 0 auto 38px;
    text-align: center;
}

.page-badge {
    display: inline-block;
    margin-bottom: 13px;
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.15);
    color: #f5d0fe;
    font-size: 12px;
    font-weight: 900;
    letter-spacing: 1px;
}

.page-header h1 {
    margin-bottom: 13px;
    font-size: clamp(36px, 5vw, 54px);
}

.page-header p {
    color: #ddd6fe;
    font-size: 16px;
    line-height: 1.7;
}

/* CONTENT */

.location-card {
    overflow: hidden;
    display: grid;
    grid-template-columns: .75fr 1.4fr;
    border-radius: 26px;
    background: rgba(255,255,255,.96);
    box-shadow: 0 26px 65px rgba(0,0,0,.28);
}

.location-info {
    padding: 38px;
    background: linear-gradient(160deg, #f8f2fc, #ede9fe);
    color: #312e81;
}

.info-icon {
    width: 65px;
    height: 65px;
    margin-bottom: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 19px;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: white;
    font-size: 29px;
    box-shadow: 0 13px 27px rgba(124,58,237,.25);
}

.location-info h2 {
    margin-bottom: 12px;
    font-size: 26px;
}

.location-info > p {
    margin-bottom: 26px;
    color: #64748b;
    line-height: 1.75;
}

.info-box {
    margin-bottom: 15px;
    padding: 16px;
    border-radius: 14px;
    background: white;
    border: 1px solid #e9d5ff;
}

.info-box span {
    display: block;
    margin-bottom: 5px;
    color: #8b5cf6;
    font-size: 11px;
    font-weight: 900;
    letter-spacing: .7px;
}

.info-box strong {
    color: #334155;
    font-size: 14px;
    line-height: 1.6;
}

.location-actions {
    margin-top: 24px;
    display: flex;
    flex-direction: column;
    gap: 11px;
}

.btn {
    min-height: 47px;
    padding: 0 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border-radius: 12px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 900;
    transition: .25s;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn-primary {
    background: linear-gradient(135deg, #7c3aed, #ec4899);
    color: white;
    box-shadow: 0 11px 24px rgba(124,58,237,.23);
}

.btn-secondary {
    background: #e2e8f0;
    color: #334155;
}

/* MAP */

.map-wrapper {
    min-height: 520px;
    background: #e2e8f0;
}

.map-wrapper iframe {
    width: 100%;
    height: 100%;
    min-height: 520px;
    display: block;
    border: 0;
}

/* FOOTER */

.footer {
    margin-top: 32px;
    text-align: center;
    color: rgba(255,255,255,.7);
    font-size: 12px;
}

/* RESPONSIVE */

@media (max-width: 850px) {
    .location-card {
        grid-template-columns: 1fr;
    }

    .map-wrapper,
    .map-wrapper iframe {
        min-height: 420px;
    }
}

@media (max-width: 620px) {
    .navbar {
        padding: 14px 18px;
    }

    .brand-text span {
        display: none;
    }

    .main-container {
        padding: 50px 16px 60px;
    }

    .location-info {
        padding: 27px 20px;
    }

    .page-header h1 {
        font-size: 37px;
    }

    .back-link {
        padding: 9px 12px;
        font-size: 12px;
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

    <a href="index.php" class="back-link">
        ← Beranda
    </a>

</nav>

<main class="main-container">

    <section class="page-header">

        <span class="page-badge">
            LOKASI USAHA
        </span>

        <h1>Temukan Lokasi Kami</h1>

        <p>
            Kunjungi Kornelia Jahit untuk konsultasi ukuran,
            pemilihan model, atau kebutuhan permak pakaian.
        </p>

    </section>

    <section class="location-card">

        <div class="location-info">

            <div class="info-icon">📍</div>

            <h2><?= htmlspecialchars($namaUsaha); ?></h2>

            <p>
                Kami siap membantu kebutuhan jahit dan permak pakaian
                dengan pelayanan yang ramah dan hasil yang rapi.
            </p>

            <div class="info-box">
                <span>ALAMAT</span>

                <strong>
                    <?= htmlspecialchars($alamat); ?>
                </strong>
            </div>

            <?php if (!empty($data['no_wa'])): ?>

                <div class="info-box">
                    <span>WHATSAPP</span>

                    <strong>
                        <?= htmlspecialchars($data['no_wa']); ?>
                    </strong>
                </div>

            <?php endif; ?>

            <?php if (!empty($data['email'])): ?>

                <div class="info-box">
                    <span>EMAIL</span>

                    <strong>
                        <?= htmlspecialchars($data['email']); ?>
                    </strong>
                </div>

            <?php endif; ?>

            <div class="location-actions">

                <?php if ($mapsLink !== ''): ?>

                    <a
                        href="<?= htmlspecialchars($mapsLink); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-primary"
                    >
                        🗺️ Buka Google Maps
                    </a>

                <?php else: ?>

                    <a
                        href="https://www.google.com/maps?q=-8.313466,123.458469"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-primary"
                    >
                        🗺️ Buka Google Maps
                    </a>

                <?php endif; ?>

                <?php if ($noWa !== ''): ?>

                    <a
                        href="https://wa.me/<?= htmlspecialchars($noWa); ?>?text=<?= $pesanWa; ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-secondary"
                    >
                        💬 Hubungi WhatsApp
                    </a>

                <?php endif; ?>

            </div>

        </div>

        <div class="map-wrapper">

            <iframe
                src="https://www.google.com/maps?q=-8.313466,123.458469&hl=id&z=17&output=embed"
                allowfullscreen
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Lokasi Kornelia Jahit"
            ></iframe>

        </div>

    </section>

    <div class="footer">
        © <?= date('Y'); ?> Kornelia Jahit — Lokasi Usaha
    </div>

</main>

</body>
</html>