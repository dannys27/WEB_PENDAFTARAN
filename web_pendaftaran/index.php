<?php
session_start();
include 'koneksi.php';

$error = "";

// Jika admin sudah login, langsung arahkan ke halaman daftar siswa
if (isset($_SESSION['login'])) {
    header("Location: daftar_siswa.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Amankan input dan cari username
    $username = mysqli_real_escape_string($koneksi, $username);
    // Pastikan nama tabel benar (tadi di screenshot terlihat 'user_admin')
    $query = "SELECT * FROM user_admin WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];

        // Verifikasi password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['nama_lengkap'] = $row['nama_lengkap'];

            header("Location: daftar_siswa.php");
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SDN Bulak 4</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="login-body">

    <div class="login-container">
        <h2>🏫 SDN Bulak 4</h2>
        <p>Silakan login untuk mengelola data siswa</p>

        <?php if ($error): ?>
            <div
                style="background: #ffe0e0; color: #e74c3c; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 0.85rem;">
                ❌ <?= $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group-login">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username Anda" required autofocus>
            </div>

            <div class="form-group-login">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password Anda" required>
            </div>

            <button type="submit" name="login" class="btn-login">Masuk Sekarang</button>
        </form>

        <div class="login-footer">
            <p>Formulir pendaftaran publik: <a href="form_pendaftaran.php">Klik di sini</a></p>
            <p style="margin-top: 10px;">Belum punya akun? <a href="register_admin.php">Buat Akun Baru</a></p>
        </div>
    </div>

</body>

</html>