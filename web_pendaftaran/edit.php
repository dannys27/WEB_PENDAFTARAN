<?php
session_start();

// Proteksi: Pastikan admin sudah login
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

include 'koneksi.php';

// Cek apakah ID siswa ada di URL
if (!isset($_GET['id'])) {
    header("Location: daftar_siswa.php");
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$data_siswa = null;
$pesan = "";

// Ambil data siswa berdasarkan ID
$query_ambil = "SELECT * FROM calon_siswa WHERE id='$id'";
$result_ambil = mysqli_query($koneksi, $query_ambil);

if (mysqli_num_rows($result_ambil) == 1) {
    $data_siswa = mysqli_fetch_assoc($result_ambil);
} else {
    $pesan = "<div class='alert alert-danger'>❌ Data siswa tidak ditemukan.</div>";
}

// --- Proses UPDATE ---
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $tgl_lahir = mysqli_real_escape_string($koneksi, $_POST['tgl_lahir']);
    $jk = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $nama_ayah = mysqli_real_escape_string($koneksi, $_POST['nama_ayah']);
    $nama_ibu = mysqli_real_escape_string($koneksi, $_POST['nama_ibu']);
    $no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp_ortu']);

    $sql_update = "UPDATE calon_siswa SET 
                    nama_lengkap='$nama', 
                    tgl_lahir='$tgl_lahir', 
                    jenis_kelamin='$jk', 
                    alamat='$alamat', 
                    nama_ayah='$nama_ayah', 
                    nama_ibu='$nama_ibu', 
                    no_telp_ortu='$no_telp' 
                    WHERE id='$id'";

    if (mysqli_query($koneksi, $sql_update)) {
        $pesan = "<div class='alert alert-success'>✅ Data berhasil diperbarui!</div>";
        $result_ambil = mysqli_query($koneksi, $query_ambil);
        $data_siswa = mysqli_fetch_assoc($result_ambil);
    } else {
        $pesan = "<div class='alert alert-danger'>❌ Gagal memperbarui data: " . mysqli_error($koneksi) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Siswa | SDN Bulak 4</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4b39b5;
            --bg: #f4f7fe;
            --secondary: #6c757d;
        }

        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg);
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .container {
            background: #fff;
            width: 100%;
            max-width: 800px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }

        .header h1 {
            color: var(--primary);
            margin: 0;
            font-size: 22px;
        }

        .btn-back {
            text-decoration: none;
            color: var(--secondary);
            font-size: 14px;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-back:hover {
            color: var(--primary);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 500;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        fieldset {
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        legend {
            font-weight: 600;
            color: #555;
            padding: 0 10px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .grid-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group.full {
            grid-column: span 2;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #666;
        }

        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
            background: #fafafa;
        }

        input:focus,
        textarea:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 8px rgba(75, 57, 181, 0.1);
        }

        .radio-group {
            display: flex;
            gap: 20px;
            padding: 10px 0;
        }

        .radio-group label {
            font-weight: 400;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-update {
            width: 100%;
            padding: 15px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-update:hover {
            background: #392a8e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(75, 57, 181, 0.2);
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>✏️ Edit Data Siswa</h1>
            <a href="daftar_siswa.php" class="btn-back">← Kembali ke Daftar</a>
        </div>

        <?php echo $pesan; ?>

        <?php if ($data_siswa): ?>
            <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                <fieldset>
                    <legend>Informasi Personal (ID: <?php echo $id; ?>)</legend>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap"
                            value="<?php echo htmlspecialchars($data_siswa['nama_lengkap']); ?>" required>
                    </div>

                    <div class="grid-form">
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir"
                                value="<?php echo htmlspecialchars($data_siswa['tgl_lahir']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <div class="radio-group">
                                <label><input type="radio" name="jenis_kelamin" value="Laki-laki" required <?php echo ($data_siswa['jenis_kelamin'] == 'Laki-laki') ? 'checked' : ''; ?>> Laki-laki</label>
                                <label><input type="radio" name="jenis_kelamin" value="Perempuan" <?php echo ($data_siswa['jenis_kelamin'] == 'Perempuan') ? 'checked' : ''; ?>> Perempuan</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alamat Tinggal</label>
                        <textarea name="alamat" rows="2"
                            required><?php echo htmlspecialchars($data_siswa['alamat']); ?></textarea>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Informasi Orang Tua</legend>
                    <div class="grid-form">
                        <div class="form-group">
                            <label>Nama Ayah</label>
                            <input type="text" name="nama_ayah"
                                value="<?php echo htmlspecialchars($data_siswa['nama_ayah']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Ibu</label>
                            <input type="text" name="nama_ibu"
                                value="<?php echo htmlspecialchars($data_siswa['nama_ibu']); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon Wali</label>
                        <input type="text" name="no_telp_ortu"
                            value="<?php echo htmlspecialchars($data_siswa['no_telp_ortu']); ?>">
                    </div>
                </fieldset>

                <button type="submit" name="update" class="btn-update">Simpan Perubahan</button>
            </form>
        <?php endif; ?>
    </div>

</body>

</html>