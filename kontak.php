<?php
include 'koneksi.php';

$msg = '';
$msgType = '';

/* ================= PROSES KIRIM PESAN ================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $isi_pesan = trim($_POST['isi_pesan'] ?? '');

    if (
        $nama === '' ||
        $email === '' ||
        $no_hp === '' ||
        $isi_pesan === ''
    ) {
        $msg = 'Semua kolom wajib diisi.';
        $msgType = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = 'Format email tidak valid.';
        $msgType = 'error';
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO pesan
            (nama, email, no_hp, isi_pesan, status, created_at)
            VALUES (?, ?, ?, ?, 'baru', NOW())"
        );

        mysqli_stmt_bind_param(
            $stmt,
            'ssss',
            $nama,
            $email,
            $no_hp,
            $isi_pesan
        );

        if (mysqli_stmt_execute($stmt)) {
            $msg = 'Pesan berhasil dikirim. Kami akan segera menghubungi Anda.';
            $msgType = 'success';

            $_POST = [];
        } else {
            $msg = 'Pesan gagal dikirim. Silakan coba kembali.';
            $msgType = 'error';
        }
    }
}

/* ================= AMBIL PROFIL USAHA ================= */

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM profil_usaha LIMIT 1"
);

if (!$query) {
    die('Query profil gagal: ' . mysqli_error($koneksi));
}

$data = mysqli_fetch_assoc($query) ?: [];

$namaUsaha = $data['nama_usaha'] ?? 'Kornelia Jahit';
$deskripsi = $data['deskripsi']
    ?? 'Jasa jahit dan permak pakaian dengan hasil rapi dan harga terjangkau.';
$alamat = $data['alamat'] ?? 'Alamat belum tersedia';
$emailUsaha = $data['email'] ?? '';
$nomorTampil = $data['no_wa'] ?? '';

$nomorWa = preg_replace('/[^0-9]/', '', $nomorTampil);

if (substr($nomorWa, 0, 1) === '0') {
    $nomorWa = '62' . substr($nomorWa, 1);
}

$pesanWa = urlencode(
    'Halo Kornelia Jahit, saya ingin bertanya mengenai layanan jahit.'
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kontak - <?= htmlspecialchars($namaUsaha); ?></title>

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
            rgba(236,72,153,.24),
            transparent 30%
        ),
        radial-gradient(
            circle at bottom left,
            rgba(59,130,246,.24),
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
    align-items: center;
    justify-content: space-between;
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
    color: #cbd5e1;
    font-size: 11px;
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
    max-width: 1160px;
    margin: auto;
    padding: 65px 24px 80px;
}

.page-header {
    max-width: 720px;
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
    margin-bottom: 12px;
    font-size: clamp(36px, 5vw, 54px);
}

.page-header p {
    color: #ddd6fe;
    line-height: 1.7;
}

/* CONTACT CARD */

.contact-card {
    overflow: hidden;
    display: grid;
    grid-template-columns: .9fr 1.1fr;
    border-radius: 27px;
    background: rgba(255,255,255,.97);
    box-shadow: 0 26px 65px rgba(0,0,0,.28);
}

/* CONTACT INFO */

.contact-info {
    padding: 38px;
    background: linear-gradient(160deg, #f8f2fc, #ede9fe);
    color: #312e81;
}

.contact-icon {
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

.contact-info h2 {
    margin-bottom: 11px;
    font-size: 27px;
}

.contact-description {
    margin-bottom: 25px;
    color: #64748b;
    line-height: 1.75;
}

.info-item {
    margin-bottom: 14px;
    padding: 15px 16px;
    border-radius: 14px;
    background: white;
    border: 1px solid #e9d5ff;
}

.info-item span {
    display: block;
    margin-bottom: 5px;
    color: #8b5cf6;
    font-size: 11px;
    font-weight: 900;
    letter-spacing: .7px;
}

.info-item strong,
.info-item a {
    color: #334155;
    font-size: 14px;
    line-height: 1.6;
    text-decoration: none;
    word-break: break-word;
}

.info-item a:hover {
    color: #7c3aed;
}

.whatsapp-button {
    width: 100%;
    min-height: 48px;
    margin-top: 10px;
    padding: 0 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border-radius: 12px;
    background: #22c55e;
    color: white;
    text-decoration: none;
    font-size: 14px;
    font-weight: 900;
    transition: .25s;
    box-shadow: 0 11px 25px rgba(34,197,94,.22);
}

.whatsapp-button:hover {
    background: #16a34a;
    transform: translateY(-2px);
}

/* FORM */

.form-section {
    padding: 38px;
    color: #312e81;
}

.form-section h2 {
    margin-bottom: 7px;
    font-size: 27px;
}

.form-description {
    margin-bottom: 24px;
    color: #64748b;
    font-size: 14px;
    line-height: 1.6;
}

.message {
    margin-bottom: 20px;
    padding: 13px 15px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 800;
}

.message-success {
    background: #dcfce7;
    border: 1px solid #bbf7d0;
    color: #15803d;
}

.message-error {
    background: #fff1f2;
    border: 1px solid #fecdd3;
    color: #be123c;
}

.form-group {
    margin-bottom: 17px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    color: #382442;
    font-size: 13px;
    font-weight: 800;
}

.required {
    color: #e11d48;
}

.form-control {
    width: 100%;
    padding: 13px 15px;
    border: 1px solid #ddd3e2;
    border-radius: 12px;
    background: #fbf9fc;
    color: #2f193d;
    font-family: inherit;
    font-size: 14px;
    outline: none;
    transition: .25s;
}

.form-control:focus {
    border-color: #8b5cf6;
    background: white;
    box-shadow: 0 0 0 4px rgba(139,92,246,.12);
}

textarea.form-control {
    min-height: 135px;
    resize: vertical;
    line-height: 1.6;
}

.submit-button {
    width: 100%;
    min-height: 49px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #7c3aed, #ec4899);
    color: white;
    font-size: 14px;
    font-weight: 900;
    cursor: pointer;
    transition: .25s;
    box-shadow: 0 12px 27px rgba(124,58,237,.24);
}

.submit-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 34px rgba(124,58,237,.32);
}

.footer {
    margin-top: 32px;
    text-align: center;
    color: rgba(255,255,255,.7);
    font-size: 12px;
}

/* RESPONSIVE */

@media (max-width: 850px) {
    .contact-card {
        grid-template-columns: 1fr;
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
        padding: 50px 15px 60px;
    }

    .contact-info,
    .form-section {
        padding: 27px 20px;
    }

    .page-header h1 {
        font-size: 38px;
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
            HUBUNGI KAMI
        </span>

        <h1>Kontak Kornelia Jahit</h1>

        <p>
            Hubungi kami melalui WhatsApp atau kirim pesan melalui
            formulir untuk konsultasi layanan jahit.
        </p>

    </section>

    <section class="contact-card">

        <div class="contact-info">

            <div class="contact-icon">📞</div>

            <h2><?= htmlspecialchars($namaUsaha); ?></h2>

            <p class="contact-description">
                <?= htmlspecialchars($deskripsi); ?>
            </p>

            <div class="info-item">
                <span>ALAMAT</span>
                <strong><?= htmlspecialchars($alamat); ?></strong>
            </div>

            <?php if ($nomorTampil !== ''): ?>

                <div class="info-item">
                    <span>WHATSAPP</span>

                    <a
                        href="https://wa.me/<?= htmlspecialchars($nomorWa); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <?= htmlspecialchars($nomorTampil); ?>
                    </a>
                </div>

            <?php endif; ?>

            <?php if ($emailUsaha !== ''): ?>

                <div class="info-item">
                    <span>EMAIL</span>

                    <a href="mailto:<?= htmlspecialchars($emailUsaha); ?>">
                        <?= htmlspecialchars($emailUsaha); ?>
                    </a>
                </div>

            <?php endif; ?>

            <div class="info-item">
                <span>JAM PELAYANAN</span>
                <strong>Senin–Sabtu, 08.00–18.00</strong>
            </div>

            <?php if ($nomorWa !== ''): ?>

                <a
                    href="https://wa.me/<?= htmlspecialchars($nomorWa); ?>?text=<?= $pesanWa; ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="whatsapp-button"
                >
                    💬 Chat melalui WhatsApp
                </a>

            <?php endif; ?>

        </div>

        <div class="form-section">

            <h2>Kirim Pesan</h2>

            <p class="form-description">
                Isi formulir berikut dan pesan Anda akan masuk
                ke dashboard admin Kornelia Jahit.
            </p>

            <?php if ($msg !== ''): ?>

                <div class="message <?= $msgType === 'success'
                    ? 'message-success'
                    : 'message-error'; ?>">
                    <?= htmlspecialchars($msg); ?>
                </div>

            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label for="nama">
                        Nama
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        class="form-control"
                        value="<?= htmlspecialchars($_POST['nama'] ?? ''); ?>"
                        placeholder="Masukkan nama Anda"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">
                        Email
                        <span class="required">*</span>
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>"
                        placeholder="contoh@email.com"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="no_hp">
                        Nomor HP/WhatsApp
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="no_hp"
                        name="no_hp"
                        class="form-control"
                        value="<?= htmlspecialchars($_POST['no_hp'] ?? ''); ?>"
                        placeholder="Contoh: 081234567890"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="isi_pesan">
                        Pesan
                        <span class="required">*</span>
                    </label>

                    <textarea
                        id="isi_pesan"
                        name="isi_pesan"
                        class="form-control"
                        placeholder="Tuliskan kebutuhan jahit Anda..."
                        required
                    ><?= htmlspecialchars($_POST['isi_pesan'] ?? ''); ?></textarea>
                </div>

                <button type="submit" class="submit-button">
                    📩 Kirim Pesan
                </button>

            </form>

        </div>

    </section>

    <div class="footer">
        © <?= date('Y'); ?> Kornelia Jahit — Kontak
    </div>

</main>

</body>
</html>