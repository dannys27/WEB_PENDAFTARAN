<?php
include 'koneksi.php';

$pesan = "";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password_mentah = $_POST['password'];
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);

    // 1. Membuat HASH dari password mentah
    $password_hash = password_hash($password_mentah, PASSWORD_DEFAULT);

    // 2. Cek apakah username sudah ada
    $cek_user = mysqli_query($koneksi, "SELECT username FROM user_admin WHERE username = '$username'");

    if (mysqli_num_rows($cek_user) > 0) {
        $pesan = "<p style='color: red;'>❌ Gagal: Username sudah digunakan.</p>";
    } else {
        // 3. Masukkan data admin baru ke database
        $sql = "INSERT INTO user_admin (username, password, nama_lengkap) 
                VALUES ('$username', '$password_hash', '$nama_lengkap')";

        if (mysqli_query($koneksi, $sql)) {
            $pesan = "<p style='color: green;'>✅ Akun admin **$username** berhasil dibuat. Silakan <a href='index.php'>Login</a>.</p>";
            // Kosongkan input setelah sukses
            unset($username, $nama_lengkap);
        } else {
            $pesan = "<p style='color: red;'>❌ Error saat registrasi: " . mysqli_error($koneksi) . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Buat Akun Admin Baru</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .register-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            width: 400px;
            text-align: center;
        }

        h1 {
            color: #007bff;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        .link-login {
            margin-top: 25px;
            font-size: small;
        }
    </style>
</head>

<body>
    <div class="register-box">
        <h1>REGISTRASI AKUN ADMIN</h1>

        <?php echo $pesan; ?>

        <form action="" method="POST">
            <label for="nama_lengkap">Nama Lengkap:</label>
            <input type="text" id="nama_lengkap" name="nama_lengkap"
                value="<?php echo htmlspecialchars($nama_lengkap ?? ''); ?>" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>"
                required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="register">Buat Akun Admin</button>
        </form>

        <div class="link-login">
            <hr>
            <p>Sudah punya akun? <a href="index.php">Kembali ke Login</a></p>
        </div>
    </div>
</body>

</html>