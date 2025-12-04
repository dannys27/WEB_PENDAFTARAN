<?php
session_start();
// Proteksi: Hanya admin yang sudah login yang boleh mengakses laporan ini
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

include 'koneksi.php';

// Ambil data admin (opsional untuk ditampilkan)
$nama_admin = $_SESSION['nama_lengkap'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pendaftar - Siap Cetak</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* CSS KHUSUS UNTUK CETAK */
        .header-laporan {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-laporan h1 {
            color: #333;
            margin-bottom: 5px;
        }

        .info-admin {
            text-align: right;
            font-size: 0.9em;
            margin-bottom: 20px;
        }

        .tombol-print {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Media Print: Ini adalah CSS yang aktif hanya saat perintah cetak dijalankan */
        @media print {

            /* Sembunyikan tombol saat mencetak */
            .tombol-print,
            .back-link {
                display: none;
            }

            /* Atur margin halaman saat dicetak */
            @page {
                margin: 1cm;
            }

            /* Pastikan background header tabel muncul saat dicetak */
            th {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                /* Wajib untuk mencetak warna background */
                color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="header-laporan">
            <h1>LAPORAN DATA CALON SISWA</h1>
            <h2>SDN KAMPUNG BULAK 4</h2>
            <hr>
        </div>

        <div class="info-admin">
            Dicetak oleh: **<?php echo htmlspecialchars($nama_admin); ?>** | Waktu:
            **<?php echo date('d-m-Y H:i:s'); ?>**
        </div>

        <div class="tombol-print">
            <button onclick="window.print()">🖨️ Cetak Laporan Ini</button>
        </div>

        <table border="1" style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Tgl Lahir</th>
                    <th>J/K</th>
                    <th>Alamat</th>
                    <th>Nama Ayah/Ibu</th>
                    <th>No Telp</th>
                    <th>Waktu Daftar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query untuk mengambil semua data calon siswa
                $sql = "SELECT * FROM calon_siswa ORDER BY tanggal_daftar DESC";
                $result = mysqli_query($koneksi, $sql);

                $no = 1;
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
                        echo "<td>" . date('d-m-Y', strtotime($row['tgl_lahir'])) . "</td>";
                        echo "<td>" . $row['jenis_kelamin'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_ayah']) . " / " . htmlspecialchars($row['nama_ibu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['no_telp_ortu']) . "</td>";
                        echo "<td>" . $row['tanggal_daftar'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align: center;'>Belum ada data pendaftar.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="back-link" style="text-align: center; margin-top: 30px;">
            <a href="daftar_siswa.php">← Kembali ke Halaman Admin</a>
        </div>

    </div>
</body>

</html>