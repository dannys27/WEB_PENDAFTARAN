<?php
include 'koneksi.php'; // Hubungkan ke database

$pesan = "";
$data_baru = null; // Variabel untuk menampung data yang baru disimpan

// Cek apakah formulir telah disubmit
if (isset($_POST['submit'])) {
    // Ambil dan amankan data dari formulir
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $tgl_lahir = mysqli_real_escape_string($koneksi, $_POST['tgl_lahir']);
    $jk = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $nama_ayah = mysqli_real_escape_string($koneksi, $_POST['nama_ayah']);
    $nama_ibu = mysqli_real_escape_string($koneksi, $_POST['nama_ibu']);
    $no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp_ortu']);

    // Query SQL untuk memasukkan data ke tabel calon_siswa
    $sql = "INSERT INTO calon_siswa (nama_lengkap, tgl_lahir, jenis_kelamin, alamat, nama_ayah, nama_ibu, no_telp_ortu) 
            VALUES ('$nama', '$tgl_lahir', '$jk', '$alamat', '$nama_ayah', '$nama_ibu', '$no_telp')";

    // Eksekusi query
    if (mysqli_query($koneksi, $sql)) {

        // Ambil ID terakhir yang dimasukkan
        $last_id = mysqli_insert_id($koneksi);

        // Ambil data lengkap yang baru disimpan untuk ditampilkan/cetak
        $query_data_baru = "SELECT * FROM calon_siswa WHERE id = '$last_id'";
        $result_data_baru = mysqli_query($koneksi, $query_data_baru);

        if (mysqli_num_rows($result_data_baru) > 0) {
            $data_baru = mysqli_fetch_assoc($result_data_baru);
        }

        $pesan = "<p style='color: green; font-weight: bold;'>✅ Pendaftaran berhasil! Data Anda sudah tercatat. Silakan cetak bukti ini.</p>";

    } else {
        $pesan = "<p style='color: red; font-weight: bold;'>❌ Error: Pendaftaran gagal. " . mysqli_error($koneksi) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Calon Siswa Baru SDN Kampung Bulak 4</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* CSS Khusus untuk tampilan cetak */
        @media print {

            .tombol-cetak,
            .footer-link,
            .header-form,
            .message-area:not(.success-msg) {
                display: none;
                /* Sembunyikan elemen non-data saat dicetak */
            }

            .cetak-area {
                border: none !important;
                padding: 0 !important;
                box-shadow: none !important;
            }

            .bukti-print {
                border: 2px solid #333;
                /* Beri border untuk bukti */
                padding: 20px;
                margin: 0;
            }

            .bukti-print h2 {
                color: #000 !important;
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="container cetak-area">

        <?php if ($data_baru): ?>

            <div class="bukti-print">
                <div class="message-area"><?php echo $pesan; ?></div>
                <h1 style="color: #0056b3;">BUKTI PENDAFTARAN SISWA BARU</h1>
                <h2>SDN Kampung Bulak 4</h2>
                <hr>

                <p>Nomor Pendaftaran: **<?php echo htmlspecialchars($data_baru['id']); ?>** (Disimpan:
                    <?php echo htmlspecialchars($data_baru['tanggal_daftar']); ?>)</p>

                <table class="table-data" style="width: 100%;">
                    <tr>
                        <td style="width: 40%;">Nama Lengkap Calon Siswa</td>
                        <td>: **<?php echo htmlspecialchars($data_baru['nama_lengkap']); ?>**</td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir</td>
                        <td>: <?php echo date('d-m-Y', strtotime(htmlspecialchars($data_baru['tgl_lahir']))); ?></td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>: <?php echo htmlspecialchars($data_baru['jenis_kelamin']); ?></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: <?php echo nl2br(htmlspecialchars($data_baru['alamat'])); ?></td>
                    </tr>
                    <tr>
                        <td>Nama Ayah</td>
                        <td>: <?php echo htmlspecialchars($data_baru['nama_ayah']); ?></td>
                    </tr>
                    <tr>
                        <td>Nama Ibu</td>
                        <td>: <?php echo htmlspecialchars($data_baru['nama_ibu']); ?></td>
                    </tr>
                    <tr>
                        <td>Nomor Telepon Ortu</td>
                        <td>: <?php echo htmlspecialchars($data_baru['no_telp_ortu']); ?></td>
                    </tr>
                </table>
                <hr>

                <div class="tombol-cetak" style="text-align: center;">
                    <button onclick="window.print()" style="background-color: #007bff; width: auto; padding: 10px 30px;">
                        🖨️ Cetak Bukti Pendaftaran
                    </button>
                    <p style="font-size: small; color: #555;">Simpan bukti ini sebagai konfirmasi pendaftaran awal.</p>
                </div>
            </div>

            <div class="footer-link" style="text-align: center; margin-top: 20px;">
                <a href="form_pendaftaran.php">← Kembali ke Form Pendaftaran Kosong</a> |
                <a href="index.php">Login Admin</a>
            </div>


        <?php else: ?>

            <div class="header-form">
                <h1>Pendaftaran Siswa Baru SDN Kampung Bulak 4</h1>
                <h2>Tahun Ajaran <?php echo date('Y') . '/' . (date('Y') + 1); ?></h2>
                <hr>
            </div>

            <div class="message-area"><?php echo $pesan; // Tampilkan pesan error jika ada ?></div>

            <form action="" method="POST">
                <fieldset>
                    <legend>Data Calon Siswa</legend>
                    <table>
                        <tr>
                            <td style="width: 35%;">Nama Lengkap *</td>
                            <td>: <input type="text" name="nama_lengkap" required></td>
                        </tr>
                        <tr>
                            <td>Tanggal Lahir *</td>
                            <td>: <input type="date" name="tgl_lahir" required></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin *</td>
                            <td>:
                                <input type="radio" name="jenis_kelamin" value="Laki-laki" required> Laki-laki
                                <input type="radio" name="jenis_kelamin" value="Perempuan"> Perempuan
                            </td>
                        </tr>
                        <tr>
                            <td>Alamat Tinggal *</td>
                            <td>: <textarea name="alamat" rows="3" required></textarea></td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset>
                    <legend>Data Orang Tua / Wali</legend>
                    <table>
                        <tr>
                            <td style="width: 35%;">Nama Ayah *</td>
                            <td>: <input type="text" name="nama_ayah" required></td>
                        </tr>
                        <tr>
                            <td>Nama Ibu *</td>
                            <td>: <input type="text" name="nama_ibu" required></td>
                        </tr>
                        <tr>
                            <td>Nomor Telepon Ortu</td>
                            <td>: <input type="text" name="no_telp_ortu"></td>
                        </tr>
                    </table>
                </fieldset>

                <input type="submit" name="submit" value="Daftar Sekarang">
            </form>
            <hr>
            <p style="text-align: center; font-size: small;">* Wajib diisi. Untuk melihat data pendaftar, silakan <a
                    href="index.php">Login Admin</a>.</p>

        <?php endif; ?>
    </div>

</body>

</html>