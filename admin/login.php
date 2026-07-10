<?php
include "../koneksi.php";
session_start();

// Kalau user sudah login, redirect ke dashboard
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}

// Ambil pesan error jika ada
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login Admin - Kornelia Jahit</title>

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    min-height: 100vh;
    font-family: "Segoe UI", Arial, sans-serif;
    background:
        radial-gradient(circle at top left, rgba(255,255,255,.18), transparent 35%),
        linear-gradient(135deg, #3b1d5a, #6d3a8f, #c77dba);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 24px;
    overflow-x: hidden;
}

.decor {
    position: fixed;
    border-radius: 50%;
    background: rgba(255,255,255,.10);
    filter: blur(2px);
    pointer-events: none;
}

.decor.one {
    width: 240px;
    height: 240px;
    top: -70px;
    left: -70px;
}

.decor.two {
    width: 320px;
    height: 320px;
    bottom: -120px;
    right: -100px;
}

.login-wrapper {
    width: 100%;
    max-width: 430px;
    position: relative;
    z-index: 2;
}

.login-card {
    background: rgba(255,255,255,.96);
    padding: 38px 34px;
    border-radius: 24px;
    box-shadow: 0 24px 70px rgba(20, 8, 35, .35);
    border: 1px solid rgba(255,255,255,.55);
    backdrop-filter: blur(12px);
}

.brand {
    text-align: center;
    margin-bottom: 28px;
}

.brand-logo {
    width: 76px;
    height: 76px;
    margin: 0 auto 14px;
    border-radius: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
    background: linear-gradient(135deg, #6d3a8f, #c77dba);
    color: white;
    box-shadow: 0 12px 30px rgba(109, 58, 143, .35);
}

.brand h1 {
    font-size: 28px;
    color: #2f193d;
    margin-bottom: 7px;
}

.brand p {
    color: #766b7c;
    font-size: 14px;
    line-height: 1.6;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #382442;
    font-size: 14px;
    font-weight: 700;
}

.input-box {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #846092;
    font-size: 17px;
    pointer-events: none;
}

.input-box input {
    width: 100%;
    height: 50px;
    padding: 0 46px 0 45px;
    border: 1px solid #ddd3e2;
    border-radius: 14px;
    background: #fbf9fc;
    color: #2e2034;
    font-size: 15px;
    outline: none;
    transition: .25s ease;
}

.input-box input:focus {
    border-color: #8b4fa3;
    background: white;
    box-shadow: 0 0 0 4px rgba(139,79,163,.12);
}

.toggle-password {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    color: #846092;
    font-size: 16px;
    cursor: pointer;
    padding: 4px;
}

.login-btn {
    margin-top: 8px;
    width: 100%;
    height: 50px;
    border: none;
    border-radius: 14px;
    background: linear-gradient(135deg, #6d3a8f, #b45ea8);
    color: white;
    font-size: 15px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 12px 24px rgba(109,58,143,.25);
    transition: .25s ease;
}

.login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 30px rgba(109,58,143,.35);
}

.login-btn:active {
    transform: translateY(0);
}

.error-msg {
    margin-top: 16px;
    padding: 12px 14px;
    border-radius: 12px;
    background: #fff0f2;
    color: #c6283d;
    border: 1px solid #ffd2d9;
    font-size: 14px;
    font-weight: 700;
    text-align: center;
}

.reset-link {
    display: block;
    margin-top: 20px;
    text-align: center;
    text-decoration: none;
    color: #7a3f91;
    font-size: 14px;
    font-weight: 700;
}

.reset-link:hover {
    text-decoration: underline;
}

.back-link {
    display: block;
    margin-top: 14px;
    text-align: center;
    color: #6f6873;
    font-size: 13px;
    text-decoration: none;
}

.back-link:hover {
    color: #4d2c5d;
}

.footer-text {
    margin-top: 20px;
    text-align: center;
    color: rgba(255,255,255,.82);
    font-size: 13px;
}

@media (max-width: 480px) {
    body {
        padding: 16px;
    }

    .login-card {
        padding: 30px 22px;
        border-radius: 20px;
    }

    .brand h1 {
        font-size: 24px;
    }
}
</style>
</head>

<body>

<div class="decor one"></div>
<div class="decor two"></div>

<div class="login-wrapper">

    <form
        method="post"
        action="proses_login.php"
        autocomplete="off"
        class="login-card"
    >

        <div class="brand">
            <div class="brand-logo">✂️</div>

            <h1>Login Admin</h1>

            <p>
                Masuk ke panel pengelolaan website
                <strong>Kornelia Jahit</strong>
            </p>
        </div>

        <div class="form-group">
            <label for="username">Username</label>

            <div class="input-box">
                <span class="input-icon">👤</span>

                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Masukkan username"
                    autocomplete="off"
                    required
                >
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>

            <div class="input-box">
                <span class="input-icon">🔒</span>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    autocomplete="new-password"
                    required
                >

                <button
                    type="button"
                    class="toggle-password"
                    id="togglePassword"
                    aria-label="Tampilkan password"
                >
                    👁️
                </button>
            </div>
        </div>

        <button type="submit" class="login-btn">
            Masuk ke Dashboard
        </button>

        <?php if (!empty($error)): ?>
            <div class="error-msg">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <a href="lupa_password.php" class="reset-link">
            Lupa atau ganti password?
        </a>

        <a href="../index.php" class="back-link">
            ← Kembali ke halaman utama
        </a>

    </form>

    <div class="footer-text">
        © <?= date('Y'); ?> Kornelia Jahit
    </div>

</div>

<script>
const form = document.querySelector(".login-card");
const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");

window.addEventListener("load", function () {
    form.reset();
});

togglePassword.addEventListener("click", function () {
    const isPassword = passwordInput.type === "password";

    passwordInput.type = isPassword ? "text" : "password";
    togglePassword.textContent = isPassword ? "🙈" : "👁️";
});
</script>

</body>
</html>