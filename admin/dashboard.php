<?php
include "../koneksi.php";
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| HITUNG DATA DASHBOARD
|--------------------------------------------------------------------------
| Sesuaikan nama tabel jika di database berbeda.
*/

$total_layanan = 0;
$total_galeri = 0;
$total_pesan = 0;

$query_layanan = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM layanan");
if ($query_layanan) {
    $data_layanan = mysqli_fetch_assoc($query_layanan);
    $total_layanan = $data_layanan['total'];
}

$query_galeri = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM galeri");
if ($query_galeri) {
    $data_galeri = mysqli_fetch_assoc($query_galeri);
    $total_galeri = $data_galeri['total'];
}

$query_pesan = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pesan");
if ($query_pesan) {
    $data_pesan = mysqli_fetch_assoc($query_pesan);
    $total_pesan = $data_pesan['total'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin - Kornelia Jahit</title>

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
        radial-gradient(circle at top left, rgba(255,255,255,.12), transparent 30%),
        linear-gradient(135deg, #24123a, #4c1d95, #7c3aed);
    color: white;
}

/* ================= NAVBAR ================= */

.navbar {
    width: 100%;
    padding: 18px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(15, 8, 28, .42);
    border-bottom: 1px solid rgba(255,255,255,.12);
    backdrop-filter: blur(16px);
    position: sticky;
    top: 0;
    z-index: 20;
}

.brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.brand-logo {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #c084fc, #f472b6);
    font-size: 22px;
    box-shadow: 0 8px 20px rgba(0,0,0,.2);
}

.brand-text h2 {
    font-size: 18px;
    margin-bottom: 2px;
}

.brand-text span {
    font-size: 12px;
    color: #ddd6fe;
}

.nav-user {
    display: flex;
    align-items: center;
    gap: 14px;
}

.user-info {
    text-align: right;
}

.user-info strong {
    display: block;
    font-size: 14px;
}

.user-info span {
    font-size: 12px;
    color: #ddd6fe;
}

.logout-top {
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 10px;
    background: rgba(239,68,68,.18);
    border: 1px solid rgba(248,113,113,.35);
    color: #fecaca;
    font-size: 13px;
    font-weight: 700;
    transition: .25s ease;
}

.logout-top:hover {
    background: #ef4444;
    color: white;
    transform: translateY(-2px);
}

/* ================= MAIN ================= */

.main-container {
    width: 100%;
    max-width: 1180px;
    margin: auto;
    padding: 46px 28px 60px;
}

/* ================= HERO ================= */

.hero {
    background:
        linear-gradient(135deg, rgba(255,255,255,.14), rgba(255,255,255,.06));
    border: 1px solid rgba(255,255,255,.16);
    border-radius: 26px;
    padding: 34px;
    box-shadow: 0 24px 60px rgba(10,4,24,.22);
    backdrop-filter: blur(16px);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 25px;
    margin-bottom: 28px;
}

.hero-left h1 {
    font-size: 34px;
    margin-bottom: 10px;
}

.hero-left p {
    color: #e9d5ff;
    line-height: 1.7;
    max-width: 650px;
}

.hero-badge {
    display: inline-block;
    margin-bottom: 12px;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.15);
    color: #f3e8ff;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .5px;
}

.hero-time {
    min-width: 200px;
    padding: 22px;
    border-radius: 20px;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.14);
    text-align: center;
}

.hero-time .time {
    font-size: 34px;
    font-weight: 800;
    margin-bottom: 4px;
}

.hero-time .date {
    color: #ddd6fe;
    font-size: 13px;
}

/* ================= STATISTICS ================= */

.section-title {
    margin-bottom: 16px;
}

.section-title h2 {
    font-size: 22px;
    margin-bottom: 5px;
}

.section-title p {
    font-size: 13px;
    color: #ddd6fe;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-bottom: 32px;
}

.stat-card {
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.14);
    border-radius: 20px;
    padding: 22px;
    backdrop-filter: blur(14px);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: .25s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    background: rgba(255,255,255,.14);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 25px;
    background: linear-gradient(135deg, #a855f7, #ec4899);
    box-shadow: 0 12px 28px rgba(0,0,0,.22);
}

.stat-content span {
    display: block;
    color: #ddd6fe;
    font-size: 13px;
    margin-bottom: 5px;
}

.stat-content strong {
    font-size: 28px;
}

/* ================= MENU GRID ================= */

.menu-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.menu-card {
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.14);
    border-radius: 22px;
    padding: 26px 22px;
    backdrop-filter: blur(14px);
    transition: .3s ease;
    position: relative;
    overflow: hidden;
}

.menu-card::before {
    content: "";
    position: absolute;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    right: -35px;
    top: -35px;
    background: rgba(255,255,255,.08);
}

.menu-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 22px 45px rgba(12,4,25,.28);
    background: rgba(255,255,255,.14);
}

.menu-icon {
    width: 58px;
    height: 58px;
    border-radius: 18px;
    background: linear-gradient(135deg, #c084fc, #f472b6);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 27px;
    margin-bottom: 18px;
    box-shadow: 0 12px 28px rgba(0,0,0,.22);
}

.menu-card h3 {
    font-size: 18px;
    margin-bottom: 9px;
}

.menu-card p {
    min-height: 60px;
    color: #e9d5ff;
    font-size: 13px;
    line-height: 1.6;
    margin-bottom: 18px;
}

.menu-link {
    width: 100%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 15px;
    border-radius: 12px;
    text-decoration: none;
    background: white;
    color: #6d28d9;
    font-size: 13px;
    font-weight: 800;
    transition: .25s ease;
}

.menu-link:hover {
    background: #f3e8ff;
    transform: translateY(-2px);
}

/* ================= FOOTER ================= */

.footer {
    margin-top: 38px;
    text-align: center;
    color: rgba(255,255,255,.65);
    font-size: 12px;
}

/* ================= RESPONSIVE ================= */

@media (max-width: 1000px) {
    .menu-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 760px) {
    .navbar {
        padding: 16px 20px;
    }

    .user-info {
        display: none;
    }

    .main-container {
        padding: 30px 18px 45px;
    }

    .hero {
        flex-direction: column;
        align-items: flex-start;
    }

    .hero-time {
        width: 100%;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 560px) {
    .menu-grid {
        grid-template-columns: 1fr;
    }

    .hero-left h1 {
        font-size: 27px;
    }

    .brand-text span {
        display: none;
    }
}
</style>
</head>

<body>

<nav class="navbar">

    <div class="brand">
        <div class="brand-logo">✂️</div>

        <div class="brand-text">
            <h2>Kornelia Jahit</h2>
            <span>Administrator Panel</span>
        </div>
    </div>

    <div class="nav-user">

        <div class="user-info">
            <strong><?= htmlspecialchars($_SESSION['admin']); ?></strong>
            <span>Administrator</span>
        </div>

        <a href="logout.php" class="logout-top">
            Logout
        </a>

    </div>

</nav>

<main class="main-container">

    <section class="hero">

        <div class="hero-left">

            <span class="hero-badge">DASHBOARD ADMIN</span>

            <h1>
                Selamat datang,
                <?= htmlspecialchars($_SESSION['admin']); ?> 👋
            </h1>

            <p>
                Kelola layanan, galeri, profil usaha, dan pesan pelanggan
                Kornelia Jahit melalui satu dashboard yang terintegrasi.
            </p>

        </div>

        <div class="hero-time">
            <div class="time" id="clock">00:00</div>
            <div class="date" id="dateText">Memuat tanggal...</div>
        </div>

    </section>

    <div class="section-title">
        <h2>Ringkasan Data</h2>
        <p>Informasi terbaru dari website Kornelia Jahit.</p>
    </div>

    <section class="stats-grid">

        <div class="stat-card">
            <div class="stat-icon">✂️</div>

            <div class="stat-content">
                <span>Total Layanan</span>
                <strong><?= $total_layanan; ?></strong>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🖼️</div>

            <div class="stat-content">
                <span>Total Galeri</span>
                <strong><?= $total_galeri; ?></strong>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">💬</div>

            <div class="stat-content">
                <span>Pesan Pelanggan</span>
                <strong><?= $total_pesan; ?></strong>
            </div>
        </div>

    </section>

    <div class="section-title">
        <h2>Menu Pengelolaan</h2>
        <p>Pilih menu untuk mengelola isi website.</p>
    </div>

    <section class="menu-grid">

        <article class="menu-card">

            <div class="menu-icon">✂️</div>

            <h3>Kelola Layanan</h3>

            <p>
                Tambah, ubah, atau hapus daftar layanan jahit yang tersedia.
            </p>

            <a href="layanan.php" class="menu-link">
                Buka Layanan →
            </a>

        </article>

        <article class="menu-card">

            <div class="menu-icon">🖼️</div>

            <h3>Kelola Galeri</h3>

            <p>
                Kelola foto hasil jahitan dan dokumentasi usaha.
            </p>

            <a href="galeri.php" class="menu-link">
                Buka Galeri →
            </a>

        </article>

        <article class="menu-card">

            <div class="menu-icon">🏪</div>

            <h3>Profil Usaha</h3>

            <p>
                Perbarui nama usaha, alamat, kontak, dan informasi lainnya.
            </p>

            <a href="profil.php" class="menu-link">
                Edit Profil →
            </a>

        </article>

        <article class="menu-card">

            <div class="menu-icon">📩</div>

            <h3>Pesan Pelanggan</h3>

            <p>
                Lihat dan kelola pesan yang dikirim oleh pelanggan.
            </p>

            <a href="pesan.php" class="menu-link">
                Lihat Pesan →
            </a>

        </article>

    </section>

    <div class="footer">
        © <?= date('Y'); ?> Kornelia Jahit — Admin Dashboard
    </div>

</main>

<script>
function updateClock() {
    const now = new Date();

    const time = now.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit"
    });

    const date = now.toLocaleDateString("id-ID", {
        weekday: "long",
        day: "numeric",
        month: "long",
        year: "numeric"
    });

    document.getElementById("clock").textContent = time;
    document.getElementById("dateText").textContent = date;
}

updateClock();
setInterval(updateClock, 1000);
</script>

</body>
</html>