<?php
include "../koneksi.php";
include "cek_login.php";

$profil = mysqli_query(
    $koneksi,
    "SELECT * FROM profil_usaha LIMIT 1"
);

$data = mysqli_fetch_assoc($profil);

if (!$data) {
    die("Data profil usaha belum tersedia.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Profil Usaha - Kornelia Jahit</title>

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
            circle at top left,
            rgba(255,255,255,.12),
            transparent 30%
        ),
        linear-gradient(
            135deg,
            #24123a,
            #4c1d95,
            #7c3aed
        );
    color: white;
}

/* NAVBAR */

.navbar {
    width: 100%;
    padding: 18px 38px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(15, 8, 28, .46);
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
    background: linear-gradient(
        135deg,
        #c084fc,
        #f472b6
    );
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
    gap: 10px;
}

.nav-link {
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 10px;
    color: white;
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
    max-width: 1100px;
    margin: auto;
    padding: 42px 24px 60px;
}

/* HERO */

.page-header {
    margin-bottom: 24px;
    padding: 30px;
    border-radius: 24px;
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.15);
    backdrop-filter: blur(15px);
    box-shadow: 0 22px 55px rgba(12,4,25,.22);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 22px;
}

.page-badge {
    display: inline-block;
    margin-bottom: 10px;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.15);
    font-size: 12px;
    font-weight: 700;
}

.page-header h1 {
    font-size: 32px;
    margin-bottom: 8px;
}

.page-header p {
    color: #e9d5ff;
    line-height: 1.6;
}

.profile-icon {
    min-width: 110px;
    height: 110px;
    border-radius: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    background: linear-gradient(
        135deg,
        #c084fc,
        #f472b6
    );
    box-shadow: 0 16px 36px rgba(0,0,0,.25);
}

/* FORM */

.form-card {
    background: rgba(255,255,255,.97);
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 24px 65px rgba(12,4,25,.28);
}

.form-content {
    padding: 34px;
}

.form-heading {
    margin-bottom: 26px;
}

.form-heading h2 {
    color: #2f193d;
    font-size: 21px;
    margin-bottom: 6px;
}

.form-heading p {
    color: #766b7c;
    font-size: 13px;
    line-height: 1.6;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.form-group {
    margin-bottom: 2px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;
    color: #382442;
    font-size: 14px;
    font-weight: 800;
    margin-bottom: 8px;
}

.required {
    color: #e11d48;
}

.form-control {
    width: 100%;
    padding: 13px 15px;
    border-radius: 12px;
    border: 1px solid #ddd3e2;
    background: #fbf9fc;
    color: #2f193d;
    font-size: 14px;
    outline: none;
    transition: .25s ease;
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

.input-help {
    margin-top: 7px;
    color: #85798b;
    font-size: 12px;
    line-height: 1.5;
}

/* PREVIEW */

.preview-card {
    margin-top: 26px;
    padding: 22px;
    border-radius: 18px;
    background: linear-gradient(
        135deg,
        #f8f2fc,
        #f3e8ff
    );
    border: 1px solid #eadff0;
}

.preview-card h3 {
    color: #382442;
    margin-bottom: 12px;
}

.preview-card p {
    color: #766b7c;
    line-height: 1.7;
    font-size: 13px;
}

.preview-list {
    margin-top: 15px;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.preview-item {
    padding: 12px 14px;
    border-radius: 12px;
    background: white;
    border: 1px solid #eee7f2;
}

.preview-item span {
    display: block;
    color: #85798b;
    font-size: 11px;
    margin-bottom: 4px;
}

.preview-item strong {
    color: #3b2545;
    font-size: 13px;
    word-break: break-word;
}

/* ACTIONS */

.form-actions {
    padding: 22px 34px;
    border-top: 1px solid #eee7f2;
    display: flex;
    justify-content: flex-end;
    gap: 11px;
    background: #fcfafc;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 46px;
    padding: 0 19px;
    border: none;
    border-radius: 12px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    transition: .25s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn-cancel {
    background: #eee8f2;
    color: #5f5065;
}

.btn-cancel:hover {
    background: #e3d8e8;
}

.btn-save {
    background: linear-gradient(
        135deg,
        #7c3aed,
        #ec4899
    );
    color: white;
    box-shadow: 0 11px 25px rgba(124,58,237,.24);
}

.btn-save:hover {
    box-shadow: 0 15px 32px rgba(124,58,237,.32);
}

.footer {
    margin-top: 27px;
    text-align: center;
    color: rgba(255,255,255,.66);
    font-size: 12px;
}

/* RESPONSIVE */

@media (max-width: 760px) {
    .navbar {
        padding: 15px 17px;
    }

    .brand-text span {
        display: none;
    }

    .nav-actions .nav-link:not(.logout-link) {
        display: none;
    }

    .main-container {
        padding: 28px 14px 45px;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        padding: 24px;
    }

    .page-header h1 {
        font-size: 27px;
    }

    .profile-icon {
        width: 100%;
        height: 90px;
    }

    .form-content {
        padding: 24px 19px;
    }

    .form-grid,
    .preview-list {
        grid-template-columns: 1fr;
    }

    .form-actions {
        padding: 18px 19px;
        flex-direction: column-reverse;
    }

    .btn {
        width: 100%;
    }
}
</style>
</head>

<body>

<nav class="navbar">

    <div class="brand">
        <div class="brand-logo">🏪</div>

        <div class="brand-text">
            <h2>Kornelia Jahit</h2>
            <span>Administrator Panel</span>
        </div>
    </div>

    <div class="nav-actions">
        <a href="dashboard.php" class="nav-link">
            Dashboard
        </a>

        <a href="logout.php" class="nav-link logout-link">
            Logout
        </a>
    </div>

</nav>

<main class="main-container">

    <section class="page-header">

        <div>
            <span class="page-badge">
                PROFIL USAHA
            </span>

            <h1>Edit Profil Usaha</h1>

            <p>
                Perbarui identitas, alamat, kontak, dan informasi
                usaha Kornelia Jahit.
            </p>
        </div>

        <div class="profile-icon">
            🧵
        </div>

    </section>

    <form
        action="profil_update.php"
        method="post"
        class="form-card"
    >

        <input
            type="hidden"
            name="id"
            value="<?= (int) $data['id_profil']; ?>"
        >

        <div class="form-content">

            <div class="form-heading">
                <h2>Informasi Usaha</h2>

                <p>
                    Pastikan informasi yang ditampilkan kepada pelanggan
                    sudah benar dan mudah dihubungi.
                </p>
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label for="nama_usaha">
                        Nama Usaha
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="nama_usaha"
                        name="nama_usaha"
                        class="form-control"
                        value="<?= htmlspecialchars($data['nama_usaha']); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="no_wa">
                        Nomor WhatsApp
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="no_wa"
                        name="no_wa"
                        class="form-control"
                        value="<?= htmlspecialchars($data['no_wa']); ?>"
                        placeholder="Contoh: 6281234567890"
                        required
                    >

                    <p class="input-help">
                        Gunakan format 62 tanpa tanda “+”.
                    </p>
                </div>

                <div class="form-group full-width">
                    <label for="deskripsi">
                        Deskripsi Usaha
                        <span class="required">*</span>
                    </label>

                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        class="form-control"
                        required
                    ><?= htmlspecialchars($data['deskripsi']); ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="alamat">
                        Alamat Usaha
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="alamat"
                        name="alamat"
                        class="form-control"
                        value="<?= htmlspecialchars($data['alamat']); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($data['email']); ?>"
                        placeholder="contoh@email.com"
                    >
                </div>

                <div class="form-group">
                    <label for="maps_link">
                        Tautan Google Maps
                    </label>

                    <input
                        type="url"
                        id="maps_link"
                        name="maps_link"
                        class="form-control"
                        value="<?= htmlspecialchars($data['maps_link']); ?>"
                        placeholder="https://maps.google.com/..."
                    >
                </div>

            </div>

            <div class="preview-card">

                <h3>Preview Informasi</h3>

                <p>
                    Ringkasan informasi yang sedang digunakan pada
                    website Kornelia Jahit.
                </p>

                <div class="preview-list">

                    <div class="preview-item">
                        <span>Nama Usaha</span>
                        <strong id="previewNama">
                            <?= htmlspecialchars($data['nama_usaha']); ?>
                        </strong>
                    </div>

                    <div class="preview-item">
                        <span>WhatsApp</span>
                        <strong id="previewWa">
                            <?= htmlspecialchars($data['no_wa']); ?>
                        </strong>
                    </div>

                    <div class="preview-item">
                        <span>Email</span>
                        <strong id="previewEmail">
                            <?= htmlspecialchars($data['email']); ?>
                        </strong>
                    </div>

                    <div class="preview-item">
                        <span>Alamat</span>
                        <strong id="previewAlamat">
                            <?= htmlspecialchars($data['alamat']); ?>
                        </strong>
                    </div>

                </div>

            </div>

        </div>

        <div class="form-actions">

            <a href="dashboard.php" class="btn btn-cancel">
                ← Batal
            </a>

            <button type="submit" class="btn btn-save">
                💾 Simpan Perubahan
            </button>

        </div>

    </form>

    <div class="footer">
        © <?= date('Y'); ?> Kornelia Jahit — Profil Usaha
    </div>

</main>

<script>
const namaInput = document.getElementById("nama_usaha");
const waInput = document.getElementById("no_wa");
const emailInput = document.getElementById("email");
const alamatInput = document.getElementById("alamat");

namaInput.addEventListener("input", function () {
    document.getElementById("previewNama").textContent =
        this.value.trim() || "-";
});

waInput.addEventListener("input", function () {
    document.getElementById("previewWa").textContent =
        this.value.trim() || "-";
});

emailInput.addEventListener("input", function () {
    document.getElementById("previewEmail").textContent =
        this.value.trim() || "-";
});

alamatInput.addEventListener("input", function () {
    document.getElementById("previewAlamat").textContent =
        this.value.trim() || "-";
});
</script>

</body>
</html>