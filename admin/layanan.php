<?php
include "../koneksi.php";
include "cek_login.php";

// Ambil semua data layanan
$layanan = mysqli_query(
    $koneksi,
    "SELECT * FROM layanan ORDER BY id_layanan DESC"
);

$total_layanan = mysqli_num_rows($layanan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kelola Layanan - Kornelia Jahit</title>

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
        radial-gradient(circle at top left, rgba(255,255,255,.10), transparent 30%),
        linear-gradient(135deg, #24123a, #4c1d95, #7c3aed);
    color: #fff;
}

/* NAVBAR */

.navbar {
    width: 100%;
    padding: 18px 38px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(15, 8, 28, .45);
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
    box-shadow: 0 8px 20px rgba(0,0,0,.22);
}

.brand-text h2 {
    font-size: 18px;
    margin-bottom: 2px;
}

.brand-text span {
    font-size: 12px;
    color: #ddd6fe;
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.nav-link {
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 10px;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.14);
    transition: .25s ease;
}

.nav-link:hover {
    background: rgba(255,255,255,.18);
    transform: translateY(-2px);
}

.logout-link {
    background: rgba(239,68,68,.18);
    color: #fecaca;
    border-color: rgba(248,113,113,.35);
}

.logout-link:hover {
    background: #ef4444;
    color: white;
}

/* MAIN */

.main-container {
    width: 100%;
    max-width: 1240px;
    margin: auto;
    padding: 42px 24px 60px;
}

/* HERO */

.hero {
    padding: 30px;
    margin-bottom: 24px;
    border-radius: 24px;
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.15);
    backdrop-filter: blur(15px);
    box-shadow: 0 22px 55px rgba(12,4,25,.23);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 24px;
}

.hero-badge {
    display: inline-block;
    margin-bottom: 10px;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.15);
    font-size: 12px;
    font-weight: 700;
}

.hero h1 {
    font-size: 32px;
    margin-bottom: 9px;
}

.hero p {
    color: #e9d5ff;
    line-height: 1.6;
}

.total-box {
    min-width: 170px;
    padding: 20px;
    border-radius: 18px;
    text-align: center;
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.14);
}

.total-box span {
    display: block;
    color: #ddd6fe;
    font-size: 13px;
    margin-bottom: 6px;
}

.total-box strong {
    font-size: 34px;
}

/* TOOLBAR */

.toolbar {
    margin-bottom: 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}

.toolbar-left {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 17px;
    border-radius: 11px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 800;
    transition: .25s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn-back {
    background: rgba(255,255,255,.10);
    color: #fff;
    border: 1px solid rgba(255,255,255,.15);
}

.btn-back:hover {
    background: rgba(255,255,255,.18);
}

.btn-add {
    background: linear-gradient(135deg, #c084fc, #f472b6);
    color: white;
    box-shadow: 0 10px 24px rgba(0,0,0,.20);
}

.btn-add:hover {
    box-shadow: 0 14px 30px rgba(0,0,0,.28);
}

.search-box {
    position: relative;
    width: 280px;
    max-width: 100%;
}

.search-box input {
    width: 100%;
    height: 44px;
    padding: 0 16px 0 42px;
    border: 1px solid rgba(255,255,255,.16);
    border-radius: 12px;
    background: rgba(255,255,255,.10);
    color: white;
    outline: none;
}

.search-box input::placeholder {
    color: #ddd6fe;
}

.search-box input:focus {
    background: rgba(255,255,255,.14);
    border-color: rgba(255,255,255,.35);
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
}

/* TABLE */

.table-card {
    background: rgba(255,255,255,.96);
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 24px 60px rgba(12,4,25,.25);
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 980px;
}

thead {
    background: linear-gradient(135deg, #5b21b6, #8b5cf6);
}

th {
    padding: 17px 16px;
    text-align: left;
    color: white;
    font-size: 13px;
    letter-spacing: .2px;
}

td {
    padding: 16px;
    color: #382442;
    font-size: 14px;
    border-bottom: 1px solid #eee8f2;
    vertical-align: middle;
}

tbody tr {
    transition: .2s ease;
}

tbody tr:hover {
    background: #faf7fc;
}

tbody tr:last-child td {
    border-bottom: none;
}

.id-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    height: 34px;
    border-radius: 10px;
    background: #f3e8ff;
    color: #6d28d9;
    font-weight: 800;
}

.service-name {
    font-weight: 800;
    color: #2f193d;
}

.description {
    max-width: 340px;
    color: #716777;
    line-height: 1.55;
}

.price {
    white-space: nowrap;
    font-weight: 800;
    color: #6d28d9;
}

.service-image {
    width: 72px;
    height: 72px;
    object-fit: cover;
    border-radius: 13px;
    border: 3px solid #f3e8ff;
    box-shadow: 0 5px 15px rgba(71,32,93,.15);
}

.no-image {
    display: inline-block;
    padding: 8px 10px;
    border-radius: 9px;
    background: #fff1f2;
    color: #be123c;
    font-size: 12px;
    font-weight: 700;
}

.action-group {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 9px 12px;
    border-radius: 9px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 800;
    transition: .22s ease;
}

.action-btn:hover {
    transform: translateY(-2px);
}

.edit-btn {
    background: #fff7d6;
    color: #a16207;
    border: 1px solid #fde68a;
}

.delete-btn {
    background: #fff1f2;
    color: #be123c;
    border: 1px solid #fecdd3;
}

.empty-state {
    padding: 60px 20px;
    text-align: center;
    color: #746a79;
}

.empty-state .icon {
    font-size: 48px;
    margin-bottom: 12px;
}

.footer {
    margin-top: 28px;
    text-align: center;
    color: rgba(255,255,255,.66);
    font-size: 12px;
}

/* RESPONSIVE */

@media (max-width: 760px) {
    .navbar {
        padding: 15px 18px;
    }

    .brand-text span {
        display: none;
    }

    .nav-actions .nav-link:not(.logout-link) {
        display: none;
    }

    .main-container {
        padding: 28px 15px 45px;
    }

    .hero {
        flex-direction: column;
        align-items: flex-start;
    }

    .total-box {
        width: 100%;
    }

    .toolbar {
        align-items: stretch;
    }

    .toolbar-left,
    .search-box {
        width: 100%;
    }

    .btn {
        flex: 1;
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

    <div class="nav-actions">
        <a href="dashboard.php" class="nav-link">Dashboard</a>
        <a href="logout.php" class="nav-link logout-link">Logout</a>
    </div>

</nav>

<main class="main-container">

    <section class="hero">

        <div>
            <span class="hero-badge">MANAJEMEN LAYANAN</span>

            <h1>Kelola Layanan</h1>

            <p>
                Tambah, ubah, dan hapus daftar layanan jahit
                yang ditampilkan kepada pelanggan.
            </p>
        </div>

        <div class="total-box">
            <span>Total Layanan</span>
            <strong><?= $total_layanan; ?></strong>
        </div>

    </section>

    <section class="toolbar">

        <div class="toolbar-left">
            <a href="dashboard.php" class="btn btn-back">
                ← Dashboard
            </a>

            <a href="layanan_tambah.php" class="btn btn-add">
                ＋ Tambah Layanan
            </a>
        </div>

        <div class="search-box">
            <span class="search-icon">🔎</span>

            <input
                type="text"
                id="searchInput"
                placeholder="Cari layanan..."
            >
        </div>

    </section>

    <section class="table-card">

        <div class="table-responsive">

            <table id="layananTable">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Layanan</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php if ($total_layanan > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($layanan)): ?>

                        <?php
                        $filePath = "../img/" . $row['foto'];
                        ?>

                        <tr>

                            <td>
                                <span class="id-badge">
                                    <?= (int) $row['id_layanan']; ?>
                                </span>
                            </td>

                            <td class="service-name">
                                <?= htmlspecialchars($row['nama_layanan']); ?>
                            </td>

                            <td>
                                <div class="description">
                                    <?= htmlspecialchars($row['deskripsi']); ?>
                                </div>
                            </td>

                            <td class="price">
                                Rp<?= number_format(
                                    $row['harga'],
                                    0,
                                    ',',
                                    '.'
                                ); ?>
                            </td>

                            <td>

                                <?php if (
                                    !empty($row['foto']) &&
                                    file_exists($filePath)
                                ): ?>

                                    <img
                                        src="<?= htmlspecialchars($filePath); ?>"
                                        class="service-image"
                                        alt="<?= htmlspecialchars(
                                            $row['nama_layanan']
                                        ); ?>"
                                    >

                                <?php else: ?>

                                    <span class="no-image">
                                        Tidak ada foto
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <div class="action-group">

                                    <a
                                        href="layanan_edit.php?id=<?= (int) $row['id_layanan']; ?>"
                                        class="action-btn edit-btn"
                                    >
                                        ✏️ Edit
                                    </a>

                                    <a
                                        href="layanan_hapus.php?id=<?= (int) $row['id_layanan']; ?>"
                                        class="action-btn delete-btn"
                                        onclick="return confirm('Yakin ingin menghapus layanan ini?')"
                                    >
                                        🗑️ Hapus
                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6">

                            <div class="empty-state">
                                <div class="icon">✂️</div>
                                <h3>Belum ada layanan</h3>
                                <p>Tambahkan layanan pertama Kornelia Jahit.</p>
                            </div>

                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

    <div class="footer">
        © <?= date('Y'); ?> Kornelia Jahit — Kelola Layanan
    </div>

</main>

<script>
const searchInput = document.getElementById("searchInput");
const rows = document.querySelectorAll(
    "#layananTable tbody tr"
);

searchInput.addEventListener("keyup", function () {
    const keyword = this.value.toLowerCase();

    rows.forEach(function (row) {
        const text = row.textContent.toLowerCase();

        row.style.display = text.includes(keyword)
            ? ""
            : "none";
    });
});
</script>

</body>
</html>