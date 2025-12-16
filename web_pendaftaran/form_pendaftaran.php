<?php
include 'koneksi.php';

$pesan = "";
$data_baru = null;

if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $tgl_lahir = mysqli_real_escape_string($koneksi, $_POST['tgl_lahir']);
    $jk = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $nama_ayah = mysqli_real_escape_string($koneksi, $_POST['nama_ayah']);
    $nama_ibu = mysqli_real_escape_string($koneksi, $_POST['nama_ibu']);
    $no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp_ortu']);

    $sql = "INSERT INTO calon_siswa (nama_lengkap, tgl_lahir, jenis_kelamin, alamat, nama_ayah, nama_ibu, no_telp_ortu) 
            VALUES ('$nama', '$tgl_lahir', '$jk', '$alamat', '$nama_ayah', '$nama_ibu', '$no_telp')";

    if (mysqli_query($koneksi, $sql)) {
        $last_id = mysqli_insert_id($koneksi);
        $query_data_baru = "SELECT * FROM calon_siswa WHERE id = '$last_id'";
        $result_data_baru = mysqli_query($koneksi, $query_data_baru);

        if (mysqli_num_rows($result_data_baru) > 0) {
            $data_baru = mysqli_fetch_assoc($result_data_baru);
        }
        $pesan = "✅ Pendaftaran berhasil! Data Anda sudah tercatat.";
    } else {
        $pesan = "❌ Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa Baru - SDN Bulak 4</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4b39b5; --bg: #f4f7fe; --text: #333; }
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg); display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        
        .container { background: #fff; width: 100%; max-width: 750px; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: var(--primary); margin: 0; font-size: 24px; }
        
        /* Form Styling */
        fieldset { border: none; padding: 0; margin-bottom: 25px; }
        legend { font-weight: 600; color: var(--primary); border-bottom: 2px solid #eee; width: 100%; padding-bottom: 8px; margin-bottom: 20px; font-size: 14px; text-transform: uppercase; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 7px; font-weight: 500; font-size: 14px; }
        input[type="text"], input[type="date"], textarea { width: 100%; padding: 12px; border: 1.5px solid #ddd; border-radius: 8px; outline: none; transition: 0.3s; background: #fafafa; }
        input:focus, textarea:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 8px rgba(75,57,181,0.1); }
        .radio-group { display: flex; gap: 20px; margin-top: 5px; }
        
        .btn-submit { width: 100%; padding: 15px; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background: #392a8e; transform: translateY(-2px); }

        /* Bukti Print Styling */
        .bukti-box { border: 2px dashed var(--primary); padding: 30px; border-radius: 12px; background: #fdfdff; }
        .table-bukti { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table-bukti td { padding: 10px 5px; border-bottom: 1px solid #eee; font-size: 15px; }
        .btn-print { background: #28a745; color: white; padding: 12px 25px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; margin-top: 15px; }

        @media print {
            body { background: white; padding: 0; }
            .container { box-shadow: none; max-width: 100%; padding: 0; }
            .btn-print, .footer-link, .no-print { display: none !important; }
            .bukti-box { border: 2px solid #000; padding: 20px; }
        }
    </style>
</head>
<body>

<div class="container">
    <?php if ($data_baru): ?>
            <div class="bukti-box">
                <div class="header">
                    <h1 style="color: #000;">BUKTI PENDAFTARAN</h1>
                    <p>SDN Kampung Bulak 4 | TA 2025/2026</p>
                </div>
                <p style="text-align: center; color: green; font-weight: bold;"><?= $pesan; ?></p>
                <hr>
                <table class="table-bukti">
                    <tr><td width="40%">No. Pendaftaran</td><td>: <strong>#<?= $data_baru['id']; ?></strong></td></tr>
                    <tr><td>Nama Lengkap</td><td>: <strong><?= htmlspecialchars($data_baru['nama_lengkap']); ?></strong></td></tr>
                    <tr><td>Tanggal Lahir</td><td>: <?= date('d-m-Y', strtotime($data_baru['tgl_lahir'])); ?></td></tr>
                    <tr><td>Jenis Kelamin</td><td>: <?= $data_baru['jenis_kelamin']; ?></td></tr>
                    <tr><td>Alamat</td><td>: <?= nl2br(htmlspecialchars($data_baru['alamat'])); ?></td></tr>
                    <tr><td>Nama Ayah/Ibu</td><td>: <?= htmlspecialchars($data_baru['nama_ayah']); ?> / <?= htmlspecialchars($data_baru['nama_ibu']); ?></td></tr>
                    <tr><td>No. Telepon</td><td>: <?= htmlspecialchars($data_baru['no_telp_ortu']); ?></td></tr>
                </table>
                <div style="text-align: center;" class="no-print">
                    <button onclick="window.print()" class="btn-print">🖨️ Cetak Bukti Pendaftaran</button>
                    <p><a href="form_pendaftaran.php" style="color: #666; text-decoration: none; font-size: 13px;">← Kembali ke Form</a></p>
                </div>
            </div>

    <?php else: ?>
            <div class="header">
                <h1>🏫 Pendaftaran Siswa Baru</h1>
                <p>SDN Kampung Bulak 4 | Tahun Ajaran 2025/2026</p>
            </div>

            <form action="" method="POST">
                <fieldset>
                    <legend>Data Calon Siswa</legend>
                    <div class="form-group">
                        <label>Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" required placeholder="Nama sesuai akta lahir">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Tanggal Lahir *</label>
                            <input type="date" name="tgl_lahir" required>
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin *</label>
                            <div class="radio-group">
                                <label><input type="radio" name="jenis_kelamin" value="Laki-laki" required> Laki-laki</label>
                                <label><input type="radio" name="jenis_kelamin" value="Perempuan"> Perempuan</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat Tinggal *</label>
                        <textarea name="alamat" rows="2" required placeholder="Alamat lengkap..."></textarea>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Data Orang Tua / Wali</legend>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Nama Ayah *</label>
                            <input type="text" name="nama_ayah" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Ibu *</label>
                            <input type="text" name="nama_ibu" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon Ortu *</label>
                        <input type="text" name="no_telp_ortu" required placeholder="Contoh: 08123456789">
                    </div>
                </fieldset>

                <button type="submit" name="submit" class="btn-submit">Daftar Sekarang</button>
            
                <div class="header" style="margin-top: 20px; font-size: 13px;">
                    <p>Sudah punya akun? <a href="index.php" style="color: var(--primary); font-weight: 600; text-decoration: none;">Login Admin</a></p>
                </div>
            </form>
    <?php endif; ?>
</div>

</body>
</html>