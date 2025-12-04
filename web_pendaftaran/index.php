<?php
session_start(); // Wajib di baris 1

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

    // 1. Amankan input dan cari username
    $username = mysqli_real_escape_string($koneksi, $username);
    $query = "SELECT * FROM user_admin WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];

        // 2. VERIFIKASI PASSWORD menggunakan fungsi aman password_verify()
        if (password_verify($password, $hashed_password)) {

            // 3. Login berhasil: buat Sesi
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['nama_lengkap'] = $row['nama_lengkap'];

            // 4. Arahkan ke Halaman Admin
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
    <title>Login Admin SDN Kampung Bulak 4</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .error {
            font-weight: bold;
        }

        .login-box {
            padding: 20px;
            border: 1px solid #ccc;
            width: 400px;
            margin: 50px auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="login-box">
        <center>
            <h1>Login Admin SDN Kampung Bulak 4</h1>
            <?php if ($error != ""): ?>
                <p class="error" style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form action="" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>

                <button type="submit" name="login">Login</button>
            </form>
            <hr>

            <p>Formulir pendaftaran untuk publik: <a href="form_pendaftaran.php">Klik di sini</a></p>

            <p style="margin-top: 15px;">Belum ada akun Admin? <a href="register_admin.php">Buat Akun Admin Baru</a></p>

        </center>
    </div>
</body>

</html>