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
    // Jika ID tidak ditemukan
    $pesan = "<p style='color: red;'>❌ Data siswa tidak ditemukan.</p>";
}

// --- Proses UPDATE (saat form disubmit) ---
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $tgl_lahir = mysqli_real_escape_string($koneksi, $_POST['tgl_lahir']);
    $jk = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $nama_ayah = mysqli_real_escape_string($koneksi, $_POST['nama_ayah']);
    $nama_ibu = mysqli_real_escape_string($koneksi, $_POST['nama_ibu']);
    $no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp_ortu']);

    // Query UPDATE SQL
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
        $pesan = "<p style='color: green;'>✅ Data berhasil diperbarui!</p>";
        // Refresh data siswa setelah update agar form menampilkan data terbaru
        $result_ambil = mysqli_query($koneksi, $query_ambil);
        $data_siswa = mysqli_fetch_assoc($result_ambil);
    } else {
        $pesan = "<p style='color: red;'>❌ Gagal memperbarui data: " . mysqli_error($koneksi) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Data Siswa | Admin SDN Kampung Bulak 4</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* (Tambahkan CSS styling yang sama seperti form_pendaftaran.php di sini) */
    </style>
</head>

<body>

    <div class="container">
        <h1>Edit Data Siswa</h1>
        <a href="daftar_siswa.php">← Kembali ke Daftar Siswa</a>
        <hr>

        <?php echo $pesan; // Tampilkan pesan berhasil atau error ?>

        <?php if ($data_siswa): ?>
            <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                <fieldset>
                    <legend>Data Calon Siswa (ID: <?php echo $id; ?>)</legend>
                    <table>
                        <tr>
                            <td style="width: 35%;">Nama Lengkap</td>
                            <td>: <input type="text" name="nama_lengkap"
                                    value="<?php echo htmlspecialchars($data_siswa['nama_lengkap']); ?>" required></td>
                        </tr>
                        <tr>
                            <td>Tanggal Lahir</td>
                            <td>: <input type="date" name="tgl_lahir"
                                    value="<?php echo htmlspecialchars($data_siswa['tgl_lahir']); ?>" required></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:
                                <input type="radio" name="jenis_kelamin" value="Laki-laki" required <?php echo ($data_siswa['jenis_kelamin'] == 'Laki-laki') ? 'checked' : ''; ?>> Laki-laki
                                <input type="radio" name="jenis_kelamin" value="Perempuan" <?php echo ($data_siswa['jenis_kelamin'] == 'Perempuan') ? 'checked' : ''; ?>> Perempuan
                            </td>
                        </tr>
                        <tr>
                            <td>Alamat Tinggal</td>
                            <td>: <textarea name="alamat" rows="3"
                                    required><?php echo htmlspecialchars($data_siswa['alamat']); ?></textarea></td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset>
                    <legend>Data Orang Tua / Wali</legend>
                    <table>
                        <tr>
                            <td style="width: 35%;">Nama Ayah</td>
                            <td>: <input type="text" name="nama_ayah"
                                    value="<?php echo htmlspecialchars($data_siswa['nama_ayah']); ?>" required></td>
                        </tr>
                        <tr>
                            <td>Nama Ibu</td>
                            <td>: <input type="text" name="nama_ibu"
                                    value="<?php echo htmlspecialchars($data_siswa['nama_ibu']); ?>" required></td>
                        </tr>
                        <tr>
                            <td>Nomor Telepon Ortu</td>
                            <td>: <input type="text" name="no_telp_ortu"
                                    value="<?php echo htmlspecialchars($data_siswa['no_telp_ortu']); ?>"></td>
                        </tr>
                    </table>
                </fieldset>

                <input type="submit" name="update" value="Simpan Perubahan">
            </form>
        <?php endif; ?>
    </div>
</body>

</html>