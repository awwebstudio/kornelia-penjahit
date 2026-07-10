<?php
include "../koneksi.php";
include "cek_login.php";

$error = '';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: galeri.php");
    exit();
}

// Ambil data galeri
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM galeri WHERE id_galeri = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: galeri.php");
    exit();
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul_foto = trim($_POST['judul_foto'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $nama_file = $data['nama_file'];

    if ($judul_foto === '' || $deskripsi === '') {
        $error = 'Judul dan deskripsi wajib diisi.';
    }

    if (
        $error === '' &&
        isset($_FILES['foto']) &&
        $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE
    ) {
        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Terjadi kesalahan saat mengunggah foto.';
        } else {
            $allowedMime = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp'
            ];

            $mimeType = mime_content_type($_FILES['foto']['tmp_name']);

            if (!isset($allowedMime[$mimeType])) {
                $error = 'Format foto harus JPG, PNG, atau WEBP.';
            } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                $error = 'Ukuran foto maksimal 2 MB.';
            } else {
                $newFileName =
                    time() . '_' .
                    random_int(1000, 9999) . '.' .
                    $allowedMime[$mimeType];

                $targetPath = "../img/" . $newFileName;

                if (
                    move_uploaded_file(
                        $_FILES['foto']['tmp_name'],
                        $targetPath
                    )
                ) {
                    $nama_file = $newFileName;
                } else {
                    $error = 'Foto gagal disimpan.';
                }
            }
        }
    }

    if ($error === '') {
        $update = mysqli_prepare(
            $koneksi,
            "UPDATE galeri
             SET judul_foto = ?, deskripsi = ?, nama_file = ?
             WHERE id_galeri = ?"
        );

        mysqli_stmt_bind_param(
            $update,
            "sssi",
            $judul_foto,
            $deskripsi,
            $nama_file,
            $id
        );

        if (mysqli_stmt_execute($update)) {
            if (
                $nama_file !== $data['nama_file'] &&
                !empty($data['nama_file']) &&
                file_exists("../img/" . $data['nama_file'])
            ) {
                unlink("../img/" . $data['nama_file']);
            }

            header("Location: galeri.php");
            exit();
        }

        if (
            $nama_file !== $data['nama_file'] &&
            file_exists("../img/" . $nama_file)
        ) {
            unlink("../img/" . $nama_file);
        }

        $error = 'Data galeri gagal diperbarui.';
    }
}

$fotoPath = "../img/" . $data['nama_file'];

$fotoTersedia =
    !empty($data['nama_file']) &&
    file_exists($fotoPath);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Galeri - Kornelia Jahit</title>

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
    align-items: center;
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
    max-width: 1080px;
    margin: auto;
    padding: 42px 24px 60px;
}

/* HEADER */

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

.id-box {
    min-width: 145px;
    padding: 18px;
    text-align: center;
    border-radius: 18px;
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.14);
}

.id-box span {
    display: block;
    color: #ddd6fe;
    font-size: 12px;
    margin-bottom: 5px;
}

.id-box strong {
    font-size: 28px;
}

/* FORM */

.form-card {
    background: rgba(255,255,255,.97);
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 24px 65px rgba(12,4,25,.28);
}

.form-grid {
    display: grid;
    grid-template-columns: 1.35fr .85fr;
}

.form-section {
    padding: 34px;
}

.preview-section {
    padding: 34px;
    background: linear-gradient(
        160deg,
        #f8f2fc,
        #f3e8ff
    );
    border-left: 1px solid #eadff0;
}

.section-heading {
    color: #2f193d;
    font-size: 20px;
    margin-bottom: 6px;
}

.section-description {
    color: #766b7c;
    font-size: 13px;
    line-height: 1.6;
    margin-bottom: 24px;
}

.error-message {
    margin-bottom: 20px;
    padding: 13px 15px;
    border-radius: 12px;
    color: #be123c;
    background: #fff1f2;
    border: 1px solid #fecdd3;
    font-size: 13px;
    font-weight: 700;
}

.form-group {
    margin-bottom: 19px;
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

.file-input {
    padding: 10px;
}

.file-help {
    margin-top: 7px;
    color: #85798b;
    font-size: 12px;
    line-height: 1.5;
}

/* PREVIEW */

.image-card {
    width: 100%;
    aspect-ratio: 4 / 5;
    border-radius: 19px;
    overflow: hidden;
    background: white;
    border: 4px solid white;
    box-shadow: 0 16px 35px rgba(72,35,94,.18);
    margin-bottom: 18px;
}

.image-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.no-image {
    width: 100%;
    height: 100%;
    min-height: 260px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #8b7893;
    text-align: center;
    padding: 25px;
}

.no-image .icon {
    font-size: 52px;
    margin-bottom: 12px;
}

.preview-title {
    color: #382442;
    font-size: 14px;
    font-weight: 800;
    margin-bottom: 5px;
}

.preview-text {
    color: #7d7182;
    font-size: 12px;
    line-height: 1.55;
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

@media (max-width: 820px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .preview-section {
        border-left: none;
        border-top: 1px solid #eadff0;
    }

    .image-card {
        max-width: 320px;
    }
}

@media (max-width: 620px) {
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

    .id-box {
        width: 100%;
    }

    .form-section,
    .preview-section {
        padding: 24px 19px;
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
        <div class="brand-logo">🖼️</div>

        <div class="brand-text">
            <h2>Kornelia Jahit</h2>
            <span>Administrator Panel</span>
        </div>
    </div>

    <div class="nav-actions">
        <a href="galeri.php" class="nav-link">
            Kelola Galeri
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
                EDIT FOTO GALERI
            </span>

            <h1>Edit Galeri</h1>

            <p>
                Perbarui judul, deskripsi, dan foto dokumentasi
                Kornelia Jahit.
            </p>
        </div>

        <div class="id-box">
            <span>ID Galeri</span>
            <strong>#<?= (int) $data['id_galeri']; ?></strong>
        </div>

    </section>

    <form
        method="post"
        enctype="multipart/form-data"
        class="form-card"
    >

        <div class="form-grid">

            <section class="form-section">

                <h2 class="section-heading">
                    Informasi Foto
                </h2>

                <p class="section-description">
                    Pastikan informasi foto sesuai dengan dokumentasi
                    yang ditampilkan kepada pelanggan.
                </p>

                <?php if ($error !== ''): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">

                    <label for="judul_foto">
                        Judul Foto
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="judul_foto"
                        name="judul_foto"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $_POST['judul_foto'] ??
                            $data['judul_foto']
                        ); ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="deskripsi">
                        Deskripsi
                        <span class="required">*</span>
                    </label>

                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        class="form-control"
                        required
                    ><?= htmlspecialchars(
                        $_POST['deskripsi'] ??
                        $data['deskripsi']
                    ); ?></textarea>

                </div>

                <div class="form-group">

                    <label for="foto">
                        Ganti Foto
                    </label>

                    <input
                        type="file"
                        id="foto"
                        name="foto"
                        class="form-control file-input"
                        accept="image/jpeg,image/png,image/webp"
                    >

                    <p class="file-help">
                        Opsional. Biarkan kosong jika foto tidak ingin
                        diganti. Maksimal 2 MB.
                    </p>

                </div>

            </section>

            <aside class="preview-section">

                <h2 class="section-heading">
                    Preview Foto
                </h2>

                <p class="section-description">
                    Foto yang sedang digunakan akan tampil di sini.
                </p>

                <div class="image-card">

                    <?php if ($fotoTersedia): ?>

                        <img
                            src="<?= htmlspecialchars($fotoPath); ?>"
                            id="imagePreview"
                            alt="<?= htmlspecialchars(
                                $data['judul_foto']
                            ); ?>"
                        >

                    <?php else: ?>

                        <div class="no-image" id="noImage">

                            <div class="icon">🖼️</div>

                            <strong>Tidak ada foto</strong>

                            <span>
                                Pilih foto baru dari perangkat.
                            </span>

                        </div>

                        <img
                            src=""
                            id="imagePreview"
                            alt="Preview foto galeri"
                            style="display:none;"
                        >

                    <?php endif; ?>

                </div>

                <div
                    class="preview-title"
                    id="previewTitle"
                >
                    <?= htmlspecialchars($data['judul_foto']); ?>
                </div>

                <div class="preview-text">
                    Preview akan berubah otomatis setelah memilih foto baru.
                </div>

            </aside>

        </div>

        <div class="form-actions">

            <a href="galeri.php" class="btn btn-cancel">
                ← Batal
            </a>

            <button type="submit" class="btn btn-save">
                💾 Simpan Perubahan
            </button>

        </div>

    </form>

    <div class="footer">
        © <?= date('Y'); ?> Kornelia Jahit — Edit Galeri
    </div>

</main>

<script>
const fileInput = document.getElementById("foto");
const imagePreview = document.getElementById("imagePreview");
const noImage = document.getElementById("noImage");
const titleInput = document.getElementById("judul_foto");
const previewTitle = document.getElementById("previewTitle");

titleInput.addEventListener("input", function () {
    previewTitle.textContent =
        this.value.trim() || "Foto Galeri";
});

fileInput.addEventListener("change", function () {
    const file = this.files[0];

    if (!file) {
        return;
    }

    if (!file.type.startsWith("image/")) {
        alert("File harus berupa gambar.");
        this.value = "";
        return;
    }

    if (file.size > 2 * 1024 * 1024) {
        alert("Ukuran foto maksimal 2 MB.");
        this.value = "";
        return;
    }

    const reader = new FileReader();

    reader.onload = function (event) {
        imagePreview.src = event.target.result;
        imagePreview.style.display = "block";

        if (noImage) {
            noImage.style.display = "none";
        }
    };

    reader.readAsDataURL(file);
});
</script>

</body>
</html>