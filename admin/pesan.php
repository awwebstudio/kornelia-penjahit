<?php
include "../koneksi.php";
include "cek_login.php";

// Ambil semua data pesan terbaru
$pesan = mysqli_query($koneksi, "SELECT * FROM pesan ORDER BY id_pesan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Pesan User</title>
<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    min-height:100vh;
    background:
    radial-gradient(circle at top left,rgba(255,255,255,.12),transparent 30%),
    linear-gradient(135deg,#24123a,#4c1d95,#7c3aed);
    color:white;
}

.container{
    width:92%;
    max-width:1400px;
    margin:auto;
    padding:35px 0;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.title h1{
    font-size:35px;
    margin-bottom:8px;
}

.title p{
    color:#ddd6fe;
}

.stat-box{
    background:rgba(255,255,255,.12);
    backdrop-filter:blur(18px);
    padding:22px 30px;
    border-radius:18px;
    text-align:center;
    min-width:180px;
}

.stat-box h2{
    font-size:38px;
}

.stat-box span{
    color:#ddd6fe;
    font-size:14px;
}

.action{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
    flex-wrap:wrap;
    gap:15px;
}

.button-back{
    display:inline-block;
    padding:12px 20px;
    text-decoration:none;
    color:white;
    background:#374151;
    border-radius:12px;
    font-weight:bold;
    transition:.3s;
}

.button-back:hover{
    background:#111827;
}

.search-box input{
    width:280px;
    max-width:100%;
    padding:12px 15px;
    border:none;
    border-radius:12px;
    outline:none;
    background:rgba(255,255,255,.12);
    color:white;
}

.search-box input::placeholder{
    color:#ddd;
}

.card{
    background:white;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 18px 45px rgba(0,0,0,.25);
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#5b21b6;
    color:white;
    padding:16px;
    text-align:left;
}

td{
    padding:15px;
    color:#333;
    border-bottom:1px solid #eee;
}

tbody tr:hover{
    background:#faf5ff;
}

.badge-id{
    background:#ede9fe;
    color:#5b21b6;
    padding:8px 12px;
    border-radius:10px;
    font-weight:bold;
}

.status-baru{
    background:#fee2e2;
    color:#dc2626;
    padding:7px 12px;
    border-radius:8px;
    font-weight:bold;
    display:inline-block;
}

.status-dibaca{
    background:#dcfce7;
    color:#15803d;
    padding:7px 12px;
    border-radius:8px;
    font-weight:bold;
    display:inline-block;
}

.action-group{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

.btn{
    padding:8px 12px;
    border-radius:8px;
    color:white;
    text-decoration:none;
    font-size:13px;
    font-weight:bold;
    transition:.3s;
}

.btn:hover{
    transform:translateY(-2px);
}

.btn-read{
    background:#f59e0b;
}

.btn-wa{
    background:#22c55e;
}

.btn-delete{
    background:#ef4444;
}

.footer{
    text-align:center;
    margin-top:25px;
    color:#ddd;
    font-size:13px;
}

@media(max-width:900px){

.header{
    flex-direction:column;
    align-items:flex-start;
    gap:20px;
}

.action{
    flex-direction:column;
    align-items:flex-start;
}

.search-box input{
    width:100%;
}

.card{
    overflow:auto;
}

table{
    min-width:1100px;
}

}

</style>
</head>
<body>

<?php
$total_pesan = mysqli_num_rows($pesan);
?>

<div class="container">

    <div class="header">

        <div class="title">
            <h1>📩 Kelola Pesan Pelanggan</h1>
            <p>
                Lihat, tandai, balas, dan hapus pesan yang masuk
                dari pelanggan Kornelia Jahit.
            </p>
        </div>

        <div class="stat-box">
            <h2><?= $total_pesan; ?></h2>
            <span>Total Pesan</span>
        </div>

    </div>

    <div class="action">

        <a href="dashboard.php" class="button-back">
            ← Kembali ke Dashboard
        </a>

        <div class="search-box">
            <input
                type="text"
                id="searchInput"
                placeholder="Cari nama, email, atau pesan..."
            >
        </div>

    </div>

    <div class="card">

        <table id="pesanTable">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>No. WhatsApp</th>
                    <th>Email</th>
                    <th>Pesan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php if ($total_pesan > 0): ?>

                <?php while ($row = mysqli_fetch_assoc($pesan)): ?>

                    <?php
                    $nomorWa = preg_replace(
                        '/[^0-9]/',
                        '',
                        $row['no_hp']
                    );

                    if (substr($nomorWa, 0, 1) === '0') {
                        $nomorWa = '62' . substr($nomorWa, 1);
                    }

                    $nama = htmlspecialchars($row['nama']);

                    $pesanWa = urlencode(
                        "Halo {$row['nama']}, terima kasih telah menghubungi Kornelia Jahit. "
                        . "Kami sudah menerima pesan Anda dan akan segera membantu."
                    );

                    $status = $row['status'] ?? 'baru';
                    ?>

                    <tr>

                        <td>
                            <span class="badge-id">
                                <?= (int) $row['id_pesan']; ?>
                            </span>
                        </td>

                        <td>
                            <strong>
                                <?= $nama; ?>
                            </strong>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['no_hp']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['email'] ?: '-'); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['isi_pesan']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['created_at']); ?>
                        </td>

                        <td>
                            <span class="<?= $status === 'baru'
                                ? 'status-baru'
                                : 'status-dibaca'; ?>">
                                <?= ucfirst(htmlspecialchars($status)); ?>
                            </span>
                        </td>

                        <td>

                            <div class="action-group">

                                <a
                                    href="pesan_baca.php?id=<?= (int) $row['id_pesan']; ?>"
                                    class="btn btn-read"
                                >
                                    👁️ Baca
                                </a>

                                <a
                                    href="https://wa.me/<?= $nomorWa; ?>?text=<?= $pesanWa; ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-wa"
                                >
                                    💬 Balas WA
                                </a>

                                <a
                                    href="pesan_hapus.php?id=<?= (int) $row['id_pesan']; ?>"
                                    class="btn btn-delete"
                                    onclick="return confirm('Yakin ingin menghapus pesan ini?');"
                                >
                                    🗑️ Hapus
                                </a>

                            </div>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="8" style="text-align:center;padding:55px;">
                        <div style="font-size:48px;margin-bottom:12px;">📭</div>
                        <h3 style="color:#382442;margin-bottom:8px;">
                            Belum ada pesan
                        </h3>
                        <p style="color:#766b7c;">
                            Pesan pelanggan akan tampil di halaman ini.
                        </p>
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

    <div class="footer">
        © <?= date('Y'); ?> Kornelia Jahit — Kelola Pesan Pelanggan
    </div>

</div>

<script>
const searchInput = document.getElementById("searchInput");
const messageRows = document.querySelectorAll(
    "#pesanTable tbody tr"
);

searchInput.addEventListener("keyup", function () {
    const keyword = this.value.toLowerCase();

    messageRows.forEach(function (row) {
        const text = row.textContent.toLowerCase();

        row.style.display = text.includes(keyword)
            ? ""
            : "none";
    });
});
</script>

</body>
</html>